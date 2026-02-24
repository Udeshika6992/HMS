<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/Progress.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../patterns/factory/UserFactory.php';

class AdminController extends Controller
{
    /**
     * @var User $userModel
     */
    private $userModel;
    
    /**
     * @var Patient $patientModel
     */
    private $patientModel;
    
    /**
     * @var Doctor $doctorModel
     */
    private $doctorModel;
    
    /**
     * @var Admin $adminModel
     */
    private $adminModel;
    
    /**
     * @var Appointment $appointmentModel
     */
    private $appointmentModel;
    
    /**
     * @var Department $departmentModel
     */
    private $departmentModel;
    
    /**
     * @var Progress $progressModel
     */
    private $progressModel;
    
    /**
     * @var Report $reportModel
     */
    private $reportModel;
    
    /**
     * @var UserFactory $userFactory
     */
    private $userFactory;
    
    /**
     * Constructor - Initialize models and check authentication
     */
    public function __construct()
    {
        parent::__construct();
        
        // Initialize models
        $this->userModel = new User();
        $this->patientModel = new Patient();
        $this->doctorModel = new Doctor();
        $this->adminModel = new Admin();
        $this->appointmentModel = new Appointment();
        $this->departmentModel = new Department();
        $this->progressModel = new Progress();
        $this->reportModel = new Report();
        $this->userFactory = new UserFactory();
        
        // Check if user is logged in and is admin
        $this->checkAdminAuth();
    }
    
    /**
     * Check if current user is authenticated as admin
     */
    private function checkAdminAuth()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            $this->setFlash('error', 'Please login to access admin panel');
            $this->redirect('/auth/login');
            return;
        }
        
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            $this->setFlash('error', 'You do not have permission to access admin panel');
            
            // Redirect based on role
            switch ($_SESSION['user_role']) {
                case 'doctor':
                    $this->redirect('/doctor/dashboard');
                    break;
                case 'patient':
                    $this->redirect('/patient/dashboard');
                    break;
                default:
                    $this->redirect('/auth/login');
            }
            return;
        }
    }
    
    /**
     * ========================================
     * DASHBOARD METHODS
     * ========================================
     */
    
    /**
     * Admin Dashboard
     * Shows statistics and overview
     */
    public function dashboard()
    {
        try {
            // Get statistics
            $stats = [
                'total_admins' => $this->adminModel->count(),
                'total_doctors' => $this->doctorModel->count(),
                'total_patients' => $this->patientModel->count(),
                'total_appointments' => $this->appointmentModel->count(),
                'total_departments' => $this->departmentModel->count(),
                'today_appointments' => $this->appointmentModel->countByDate(date('Y-m-d')),
                'pending_appointments' => $this->appointmentModel->countByStatus('pending'),
                'completed_appointments' => $this->appointmentModel->countByStatus('completed')
            ];
            
            // Get recent appointments
            $recentAppointments = $this->appointmentModel->getRecentAppointments(5);
            
            // Get recent patients
            $recentPatients = $this->patientModel->getRecentPatients(5);
            
            // Get appointment statistics for chart
            $appointmentStats = $this->appointmentModel->getWeeklyStats();
            
            $data = [
                'stats' => $stats,
                'recentAppointments' => $recentAppointments,
                'recentPatients' => $recentPatients,
                'appointmentStats' => $appointmentStats,
                'pageTitle' => 'Admin Dashboard',
                'currentPage' => 'dashboard'
            ];
            
            $this->view('admin/dashboard', $data);
            
        } catch (Exception $e) {
            error_log("Admin Dashboard Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading dashboard: ' . $e->getMessage());
            $this->view('admin/dashboard', ['pageTitle' => 'Admin Dashboard']);
        }
    }
    
    /**
     * ========================================
     * ADMIN MANAGEMENT METHODS
     * ========================================
     */
    
    /**
     * Manage Admins - List all admins
     */
    public function manageAdmins()
    {
        try {
            // Get all admins
            $admins = $this->adminModel->getAllAdmins();
            
            $data = [
                'admins' => $admins,
                'pageTitle' => 'Manage Admins',
                'currentPage' => 'admins'
            ];
            
            $this->view('admin/manage_admins', $data);
            
        } catch (Exception $e) {
            error_log("Manage Admins Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading admins: ' . $e->getMessage());
            $this->redirect('/admin/dashboard');
        }
    }
    
    /**
     * Add new admin
     */
    public function addAdmin()
    {
        if (!$this->isPost()) {
            $this->redirect('/admin/manage-admins');
            return;
        }
        
        try {
            $data = $this->getPostData();
            
            // Validate required fields
            $errors = $this->validateRequired($data, ['name', 'email', 'password', 'confirm_password']);
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                $this->redirect('/admin/manage-admins');
                return;
            }
            
            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->setFlash('error', 'Invalid email format');
                $this->redirect('/admin/manage-admins');
                return;
            }
            
            // Check if passwords match
            if ($data['password'] !== $data['confirm_password']) {
                $this->setFlash('error', 'Passwords do not match');
                $this->redirect('/admin/manage-admins');
                return;
            }
            
            // Check password strength
            if (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
                $this->setFlash('error', 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters');
                $this->redirect('/admin/manage-admins');
                return;
            }
            
            // Check if email already exists
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser) {
                $this->setFlash('error', 'Email already exists');
                $this->redirect('/admin/manage-admins');
                return;
            }
            
            // Prepare data for factory
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'admin'
            ];
            
            // Create admin using factory
            $result = $this->userFactory->create($userData);
            
            if ($result) {
                $this->setFlash('success', 'Admin added successfully!');
                
                // Log the action
                $this->logAction('add_admin', "Added admin: {$data['email']}");
            } else {
                $this->setFlash('error', 'Failed to add admin');
            }
            
            $this->redirect('/admin/manage-admins');
            
        } catch (Exception $e) {
            error_log("Add Admin Error: " . $e->getMessage());
            $this->setFlash('error', 'Error adding admin: ' . $e->getMessage());
            $this->redirect('/admin/manage-admins');
        }
    }
    
    /**
     * Update admin
     * @param int $id Admin ID
     */
    public function updateAdmin($id)
    {
        if (!$this->isPost()) {
            $this->redirect('/admin/manage-admins');
            return;
        }
        
        try {
            $data = $this->getPostData();
            
            // Validate required fields
            $errors = $this->validateRequired($data, ['name', 'email']);
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                $this->redirect('/admin/manage-admins');
                return;
            }
            
            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->setFlash('error', 'Invalid email format');
                $this->redirect('/admin/manage-admins');
                return;
            }
            
            // Check if email exists for another user
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $id) {
                $this->setFlash('error', 'Email already exists for another user');
                $this->redirect('/admin/manage-admins');
                return;
            }
            
            // Prepare update data
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email']
            ];
            
            // Update password if provided
            if (!empty($data['password'])) {
                if ($data['password'] !== $data['confirm_password']) {
                    $this->setFlash('error', 'Passwords do not match');
                    $this->redirect('/admin/manage-admins');
                    return;
                }
                
                if (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
                    $this->setFlash('error', 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters');
                    $this->redirect('/admin/manage-admins');
                    return;
                }
                
                $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            // Update admin
            $result = $this->adminModel->updateAdmin($id, $updateData);
            
            if ($result) {
                $this->setFlash('success', 'Admin updated successfully!');
                
                // Log the action
                $this->logAction('update_admin', "Updated admin ID: {$id}");
            } else {
                $this->setFlash('error', 'Failed to update admin');
            }
            
            $this->redirect('/admin/manage-admins');
            
        } catch (Exception $e) {
            error_log("Update Admin Error: " . $e->getMessage());
            $this->setFlash('error', 'Error updating admin: ' . $e->getMessage());
            $this->redirect('/admin/manage-admins');
        }
    }
    
    /**
     * Delete admin
     * @param int $id Admin ID
     */
    public function deleteAdmin($id)
    {
        try {
            // Prevent deleting yourself
            if ($id == $_SESSION['user_id']) {
                $this->setFlash('error', 'You cannot delete your own account');
                $this->redirect('/admin/manage-admins');
                return;
            }
            
            // Check if admin exists
            $admin = $this->adminModel->find($id);
            if (!$admin) {
                $this->setFlash('error', 'Admin not found');
                $this->redirect('/admin/manage-admins');
                return;
            }
            
            // Delete admin
            $result = $this->adminModel->deleteAdmin($id);
            
            if ($result) {
                $this->setFlash('success', 'Admin deleted successfully!');
                
                // Log the action
                $this->logAction('delete_admin', "Deleted admin ID: {$id}, Email: {$admin['email']}");
            } else {
                $this->setFlash('error', 'Failed to delete admin');
            }
            
            $this->redirect('/admin/manage-admins');
            
        } catch (Exception $e) {
            error_log("Delete Admin Error: " . $e->getMessage());
            $this->setFlash('error', 'Error deleting admin: ' . $e->getMessage());
            $this->redirect('/admin/manage-admins');
        }
    }
    
    /**
     * ========================================
     * DOCTOR MANAGEMENT METHODS
     * ========================================
     */
    
    /**
     * Manage Doctors - List all doctors
     */
    public function manageDoctors()
    {
        try {
            // Get all doctors with details
            $doctors = $this->doctorModel->getAllDoctors();
            
            // Get departments for dropdown
            $departments = $this->departmentModel->all();
            
            $data = [
                'doctors' => $doctors,
                'departments' => $departments,
                'pageTitle' => 'Manage Doctors',
                'currentPage' => 'doctors'
            ];
            
            $this->view('admin/manage_doctors', $data);
            
        } catch (Exception $e) {
            error_log("Manage Doctors Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading doctors: ' . $e->getMessage());
            $this->redirect('/admin/dashboard');
        }
    }
    
    /**
     * Add new doctor
     */
    public function addDoctor()
    {
        if (!$this->isPost()) {
            $this->redirect('/admin/manage-doctors');
            return;
        }
        
        try {
            $data = $this->getPostData();
            
            // Validate required fields
            $errors = $this->validateRequired($data, [
                'name', 'email', 'password', 'confirm_password', 
                'specialization', 'department_id'
            ]);
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                $this->redirect('/admin/manage-doctors');
                return;
            }
            
            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->setFlash('error', 'Invalid email format');
                $this->redirect('/admin/manage-doctors');
                return;
            }
            
            // Check passwords match
            if ($data['password'] !== $data['confirm_password']) {
                $this->setFlash('error', 'Passwords do not match');
                $this->redirect('/admin/manage-doctors');
                return;
            }
            
            // Check password strength
            if (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
                $this->setFlash('error', 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters');
                $this->redirect('/admin/manage-doctors');
                return;
            }
            
            // Check if email exists
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser) {
                $this->setFlash('error', 'Email already exists');
                $this->redirect('/admin/manage-doctors');
                return;
            }
            
            // Prepare data for factory
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'doctor',
                'specialization' => $data['specialization'],
                'qualification' => $data['qualification'] ?? '',
                'experience' => $data['experience'] ?? 0,
                'department_id' => $data['department_id'],
                'available_days' => $data['available_days'] ?? 'Monday,Tuesday,Wednesday,Thursday,Friday',
                'available_time_start' => $data['available_time_start'] ?? '09:00:00',
                'available_time_end' => $data['available_time_end'] ?? '17:00:00'
            ];
            
            // Create doctor using factory
            $result = $this->userFactory->create($userData);
            
            if ($result) {
                $this->setFlash('success', 'Doctor added successfully!');
                
                // Log the action
                $this->logAction('add_doctor', "Added doctor: {$data['email']}");
            } else {
                $this->setFlash('error', 'Failed to add doctor');
            }
            
            $this->redirect('/admin/manage-doctors');
            
        } catch (Exception $e) {
            error_log("Add Doctor Error: " . $e->getMessage());
            $this->setFlash('error', 'Error adding doctor: ' . $e->getMessage());
            $this->redirect('/admin/manage-doctors');
        }
    }
    
    /**
     * Update doctor
     * @param int $id Doctor ID
     */
    public function updateDoctor($id)
    {
        if (!$this->isPost()) {
            $this->redirect('/admin/manage-doctors');
            return;
        }
        
        try {
            $data = $this->getPostData();
            
            // Validate required fields
            $errors = $this->validateRequired($data, ['name', 'email', 'specialization', 'department_id']);
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                $this->redirect('/admin/manage-doctors');
                return;
            }
            
            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->setFlash('error', 'Invalid email format');
                $this->redirect('/admin/manage-doctors');
                return;
            }
            
            // Check if email exists for another user
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $id) {
                $this->setFlash('error', 'Email already exists for another user');
                $this->redirect('/admin/manage-doctors');
                return;
            }
            
            // Update user data
            $userData = [
                'name' => $data['name'],
                'email' => $data['email']
            ];
            
            // Update password if provided
            if (!empty($data['password'])) {
                if ($data['password'] !== $data['confirm_password']) {
                    $this->setFlash('error', 'Passwords do not match');
                    $this->redirect('/admin/manage-doctors');
                    return;
                }
                
                $userData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            // Update doctor data
            $doctorData = [
                'specialization' => $data['specialization'],
                'qualification' => $data['qualification'] ?? '',
                'experience' => $data['experience'] ?? 0,
                'department_id' => $data['department_id'],
                'available_days' => $data['available_days'] ?? 'Monday,Tuesday,Wednesday,Thursday,Friday',
                'available_time_start' => $data['available_time_start'] ?? '09:00:00',
                'available_time_end' => $data['available_time_end'] ?? '17:00:00'
            ];
            
            // Update doctor
            $result = $this->doctorModel->updateDoctor($id, $userData, $doctorData);
            
            if ($result) {
                $this->setFlash('success', 'Doctor updated successfully!');
                
                // Log the action
                $this->logAction('update_doctor', "Updated doctor ID: {$id}");
            } else {
                $this->setFlash('error', 'Failed to update doctor');
            }
            
            $this->redirect('/admin/manage-doctors');
            
        } catch (Exception $e) {
            error_log("Update Doctor Error: " . $e->getMessage());
            $this->setFlash('error', 'Error updating doctor: ' . $e->getMessage());
            $this->redirect('/admin/manage-doctors');
        }
    }
    
    /**
     * Delete doctor
     * @param int $id Doctor ID
     */
    public function deleteDoctor($id)
    {
        try {
            // Check if doctor exists
            $doctor = $this->doctorModel->find($id);
            if (!$doctor) {
                $this->setFlash('error', 'Doctor not found');
                $this->redirect('/admin/manage-doctors');
                return;
            }
            
            // Delete doctor
            $result = $this->doctorModel->deleteDoctor($id);
            
            if ($result) {
                $this->setFlash('success', 'Doctor deleted successfully!');
                
                // Log the action
                $this->logAction('delete_doctor', "Deleted doctor ID: {$id}, Email: {$doctor['email']}");
            } else {
                $this->setFlash('error', 'Failed to delete doctor');
            }
            
            $this->redirect('/admin/manage-doctors');
            
        } catch (Exception $e) {
            error_log("Delete Doctor Error: " . $e->getMessage());
            $this->setFlash('error', 'Error deleting doctor: ' . $e->getMessage());
            $this->redirect('/admin/manage-doctors');
        }
    }
    
    /**
     * ========================================
     * PATIENT MANAGEMENT METHODS
     * ========================================
     */
    
    /**
     * Manage Patients - List all patients
     */
    public function managePatients()
    {
        try {
            // Get all patients with details
            $patients = $this->patientModel->getAllPatients();
            
            $data = [
                'patients' => $patients,
                'pageTitle' => 'Manage Patients',
                'currentPage' => 'patients'
            ];
            
            $this->view('admin/manage_patients', $data);
            
        } catch (Exception $e) {
            error_log("Manage Patients Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading patients: ' . $e->getMessage());
            $this->redirect('/admin/dashboard');
        }
    }
    
    /**
     * Add new patient
     */
    public function addPatient()
    {
        if (!$this->isPost()) {
            $this->redirect('/admin/manage-patients');
            return;
        }
        
        try {
            $data = $this->getPostData();
            
            // Validate required fields
            $errors = $this->validateRequired($data, ['name', 'email', 'password', 'confirm_password', 'phone']);
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                $this->redirect('/admin/manage-patients');
                return;
            }
            
            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->setFlash('error', 'Invalid email format');
                $this->redirect('/admin/manage-patients');
                return;
            }
            
            // Check passwords match
            if ($data['password'] !== $data['confirm_password']) {
                $this->setFlash('error', 'Passwords do not match');
                $this->redirect('/admin/manage-patients');
                return;
            }
            
            // Check if email exists
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser) {
                $this->setFlash('error', 'Email already exists');
                $this->redirect('/admin/manage-patients');
                return;
            }
            
            // Prepare data for factory
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'patient',
                'phone' => $data['phone'],
                'gender' => $data['gender'] ?? '',
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'address' => $data['address'] ?? '',
                'blood_group' => $data['blood_group'] ?? '',
                'emergency_contact' => $data['emergency_contact'] ?? '',
                'emergency_name' => $data['emergency_name'] ?? ''
            ];
            
            // Create patient using factory
            $result = $this->userFactory->create($userData);
            
            if ($result) {
                $this->setFlash('success', 'Patient added successfully!');
                
                // Log the action
                $this->logAction('add_patient', "Added patient: {$data['email']}");
            } else {
                $this->setFlash('error', 'Failed to add patient');
            }
            
            $this->redirect('/admin/manage-patients');
            
        } catch (Exception $e) {
            error_log("Add Patient Error: " . $e->getMessage());
            $this->setFlash('error', 'Error adding patient: ' . $e->getMessage());
            $this->redirect('/admin/manage-patients');
        }
    }
    
    /**
     * Update patient
     * @param int $id Patient ID
     */
    public function updatePatient($id)
    {
        if (!$this->isPost()) {
            $this->redirect('/admin/manage-patients');
            return;
        }
        
        try {
            $data = $this->getPostData();
            
            // Validate required fields
            $errors = $this->validateRequired($data, ['name', 'email', 'phone']);
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                $this->redirect('/admin/manage-patients');
                return;
            }
            
            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->setFlash('error', 'Invalid email format');
                $this->redirect('/admin/manage-patients');
                return;
            }
            
            // Check if email exists for another user
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $id) {
                $this->setFlash('error', 'Email already exists for another user');
                $this->redirect('/admin/manage-patients');
                return;
            }
            
            // Prepare update data
            $userData = [
                'name' => $data['name'],
                'email' => $data['email']
            ];
            
            // Update password if provided
            if (!empty($data['password'])) {
                if ($data['password'] !== $data['confirm_password']) {
                    $this->setFlash('error', 'Passwords do not match');
                    $this->redirect('/admin/manage-patients');
                    return;
                }
                
                $userData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            $patientData = [
                'phone' => $data['phone'],
                'gender' => $data['gender'] ?? '',
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'address' => $data['address'] ?? '',
                'blood_group' => $data['blood_group'] ?? '',
                'emergency_contact' => $data['emergency_contact'] ?? '',
                'emergency_name' => $data['emergency_name'] ?? ''
            ];
            
            // Update patient
            $result = $this->patientModel->updatePatient($id, $userData, $patientData);
            
            if ($result) {
                $this->setFlash('success', 'Patient updated successfully!');
                
                // Log the action
                $this->logAction('update_patient', "Updated patient ID: {$id}");
            } else {
                $this->setFlash('error', 'Failed to update patient');
            }
            
            $this->redirect('/admin/manage-patients');
            
        } catch (Exception $e) {
            error_log("Update Patient Error: " . $e->getMessage());
            $this->setFlash('error', 'Error updating patient: ' . $e->getMessage());
            $this->redirect('/admin/manage-patients');
        }
    }
    
    /**
     * Delete patient
     * @param int $id Patient ID
     */
    public function deletePatient($id)
    {
        try {
            // Check if patient exists
            $patient = $this->patientModel->find($id);
            if (!$patient) {
                $this->setFlash('error', 'Patient not found');
                $this->redirect('/admin/manage-patients');
                return;
            }
            
            // Delete patient
            $result = $this->patientModel->deletePatient($id);
            
            if ($result) {
                $this->setFlash('success', 'Patient deleted successfully!');
                
                // Log the action
                $this->logAction('delete_patient', "Deleted patient ID: {$id}, Email: {$patient['email']}");
            } else {
                $this->setFlash('error', 'Failed to delete patient');
            }
            
            $this->redirect('/admin/manage-patients');
            
        } catch (Exception $e) {
            error_log("Delete Patient Error: " . $e->getMessage());
            $this->setFlash('error', 'Error deleting patient: ' . $e->getMessage());
            $this->redirect('/admin/manage-patients');
        }
    }
    
    /**
     * ========================================
     * DEPARTMENT MANAGEMENT METHODS
     * ========================================
     */
    
    /**
     * Manage Departments
     */
    public function manageDepartments()
    {
        try {
            // Get all departments
            $departments = $this->departmentModel->all();
            
            $data = [
                'departments' => $departments,
                'pageTitle' => 'Manage Departments',
                'currentPage' => 'departments'
            ];
            
            $this->view('admin/manage_departments', $data);
            
        } catch (Exception $e) {
            error_log("Manage Departments Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading departments: ' . $e->getMessage());
            $this->redirect('/admin/dashboard');
        }
    }
    
    /**
     * Add department
     */
    public function addDepartment()
    {
        if (!$this->isPost()) {
            $this->redirect('/admin/manage-departments');
            return;
        }
        
        try {
            $data = $this->getPostData();
            
            // Validate required fields
            if (empty($data['name'])) {
                $this->setFlash('error', 'Department name is required');
                $this->redirect('/admin/manage-departments');
                return;
            }
            
            // Check if department exists
            $existing = $this->departmentModel->findByName($data['name']);
            if ($existing) {
                $this->setFlash('error', 'Department already exists');
                $this->redirect('/admin/manage-departments');
                return;
            }
            
            // Create department
            $result = $this->departmentModel->create([
                'name' => $data['name'],
                'description' => $data['description'] ?? ''
            ]);
            
            if ($result) {
                $this->setFlash('success', 'Department added successfully!');
                
                // Log the action
                $this->logAction('add_department', "Added department: {$data['name']}");
            } else {
                $this->setFlash('error', 'Failed to add department');
            }
            
            $this->redirect('/admin/manage-departments');
            
        } catch (Exception $e) {
            error_log("Add Department Error: " . $e->getMessage());
            $this->setFlash('error', 'Error adding department: ' . $e->getMessage());
            $this->redirect('/admin/manage-departments');
        }
    }
    
    /**
     * Update department
     * @param int $id Department ID
     */
    public function updateDepartment($id)
    {
        if (!$this->isPost()) {
            $this->redirect('/admin/manage-departments');
            return;
        }
        
        try {
            $data = $this->getPostData();
            
            // Validate required fields
            if (empty($data['name'])) {
                $this->setFlash('error', 'Department name is required');
                $this->redirect('/admin/manage-departments');
                return;
            }
            
            // Check if name exists for another department
            $existing = $this->departmentModel->findByName($data['name']);
            if ($existing && $existing['id'] != $id) {
                $this->setFlash('error', 'Department name already exists');
                $this->redirect('/admin/manage-departments');
                return;
            }
            
            // Update department
            $result = $this->departmentModel->update($id, [
                'name' => $data['name'],
                'description' => $data['description'] ?? ''
            ]);
            
            if ($result) {
                $this->setFlash('success', 'Department updated successfully!');
                
                // Log the action
                $this->logAction('update_department', "Updated department ID: {$id}");
            } else {
                $this->setFlash('error', 'Failed to update department');
            }
            
            $this->redirect('/admin/manage-departments');
            
        } catch (Exception $e) {
            error_log("Update Department Error: " . $e->getMessage());
            $this->setFlash('error', 'Error updating department: ' . $e->getMessage());
            $this->redirect('/admin/manage-departments');
        }
    }
    
    /**
     * Delete department
     * @param int $id Department ID
     */
    public function deleteDepartment($id)
    {
        try {
            // Check if department has doctors
            $doctorCount = $this->doctorModel->countByDepartment($id);
            if ($doctorCount > 0) {
                $this->setFlash('error', "Cannot delete department with {$doctorCount} doctors assigned");
                $this->redirect('/admin/manage-departments');
                return;
            }
            
            // Delete department
            $result = $this->departmentModel->delete($id);
            
            if ($result) {
                $this->setFlash('success', 'Department deleted successfully!');
                
                // Log the action
                $this->logAction('delete_department', "Deleted department ID: {$id}");
            } else {
                $this->setFlash('error', 'Failed to delete department');
            }
            
            $this->redirect('/admin/manage-departments');
            
        } catch (Exception $e) {
            error_log("Delete Department Error: " . $e->getMessage());
            $this->setFlash('error', 'Error deleting department: ' . $e->getMessage());
            $this->redirect('/admin/manage-departments');
        }
    }
    
    /**
     * ========================================
     * APPOINTMENT MANAGEMENT METHODS
     * ========================================
     */
    
    /**
     * Manage Appointments
     */
    public function manageAppointments()
    {
        try {
            // Get filters
            $filters = [
                'date' => $_GET['date'] ?? null,
                'status' => $_GET['status'] ?? null,
                'doctor_id' => $_GET['doctor_id'] ?? null,
                'patient_id' => $_GET['patient_id'] ?? null
            ];
            
            // Get appointments with filters
            $appointments = $this->appointmentModel->getAppointmentsWithDetails($filters);
            
            // Get doctors for filter dropdown
            $doctors = $this->doctorModel->getAllDoctors();
            
            $data = [
                'appointments' => $appointments,
                'doctors' => $doctors,
                'filters' => $filters,
                'pageTitle' => 'Manage Appointments',
                'currentPage' => 'appointments'
            ];
            
            $this