<?php
/**
 * Admin Controller
 */

class AdminController extends Controller {
    
    private $userModel;
    private $doctorModel;
    private $patientModel;
    private $appointmentModel;
    private $departmentModel;
    private $settingModel;
    
    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in and is admin
        if (!$this->isLoggedIn() || $this->getCurrentUserRole() !== 'admin') {
            $this->setFlash('Please login to access this page', 'error');
            $this->redirect('login');
            return;
        }
        
        // Load models
        require_once 'models/UserModel.php';
        require_once 'models/DoctorModel.php';
        require_once 'models/PatientModel.php';
        require_once 'models/AppointmentModel.php';
        require_once 'models/DepartmentModel.php';
        require_once 'models/SettingModel.php';
        
        $this->userModel = new UserModel();
        $this->doctorModel = new DoctorModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->departmentModel = new DepartmentModel();
        $this->settingModel = new SettingModel();
    }

    /**
     * Admin Dashboard
     */
    public function dashboard() {
        // Get statistics
        $stats = [
            'total_users' => $this->userModel->count(),
            'total_doctors' => $this->userModel->count("role = 'doctor'"),
            'total_patients' => $this->userModel->count("role = 'patient'"),
            'total_appointments' => $this->appointmentModel->count(),
            'today_appointments' => $this->appointmentModel->count(
                "appointment_date = CURDATE() AND status NOT IN ('cancelled', 'no_show')"
            ),
            'total_departments' => $this->departmentModel->count("is_active = 1"),
            'recent_users' => $this->userModel->getRecent(5),
            'recent_appointments' => $this->appointmentModel->getByDateRange(
                date('Y-m-d', strtotime('-7 days')),
                date('Y-m-d')
            )
        ];

        $data = [
            'title' => 'Admin Dashboard',
            'stats' => $stats
        ];

        $this->render('admin/dashboard', $data, 'admin-layout');
    }

    /**
     * User Management
     */
    public function users() {
        $page = (int)($this->get('page', 1));
        $search = $this->get('search');
        $role = $this->get('role');
        
        if ($search) {
            $users = $this->userModel->search($search);
            $total = count($users);
            $pagination = $this->getPaginationData($total, $page, 10);
            $users = array_slice($users, $pagination['offset'], $pagination['per_page']);
        } else {
            $result = $this->userModel->paginate($page, 10);
            $users = $result['data'] ?? [];
            $pagination = [
                'total' => $result['total'] ?? 0,
                'page' => $page,
                'per_page' => 10,
                'total_pages' => $result['total_pages'] ?? 1,
                'has_previous' => $page > 1,
                'has_next' => $page < ($result['total_pages'] ?? 1),
                'previous_page' => $page - 1,
                'next_page' => $page + 1,
                'offset' => ($page - 1) * 10
            ];
        }

        $data = [
            'title' => 'User Management',
            'users' => $users,
            'pagination' => $pagination,
            'search' => $search,
            'role' => $role
        ];

        $this->render('admin/users', $data, 'admin-layout');
    }

    /**
     * Create User Form
     */
    public function createUser() {
        if ($this->isPost()) {
            // Get CSRF token from POST
            $csrfToken = $this->post('csrf_token');
            
            // Validate CSRF token
            if (!$this->validateCsrf($csrfToken)) {
                $this->setFlash('Invalid security token. Please try again.', 'error');
                $this->redirect('admin/users/create');
                return;
            }

            $data = [
                'username' => $this->post('username'),
                'email' => $this->post('email'),
                'password' => $this->post('password'),
                'confirm_password' => $this->post('confirm_password'),
                'full_name' => $this->post('full_name'),
                'phone' => $this->post('phone'),
                'address' => $this->post('address'),
                'role' => $this->post('role')
            ];

            // Validate
            $errors = [];

            if (strlen($data['username']) < 3) {
                $errors[] = 'Username must be at least 3 characters';
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email format';
            }
            if (strlen($data['password']) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            }
            if ($data['password'] !== $data['confirm_password']) {
                $errors[] = 'Passwords do not match';
            }
            if (empty($data['full_name'])) {
                $errors[] = 'Full name is required';
            }

            // Check if username exists
            if ($this->userModel->findByUsername($data['username'])) {
                $errors[] = 'Username already taken';
            }

            // Check if email exists
            if ($this->userModel->findByEmail($data['email'])) {
                $errors[] = 'Email already registered';
            }

            if (!empty($errors)) {
                $_SESSION['form_errors'] = $errors;
                $_SESSION['form_data'] = $data;
                $this->setFlash('Please correct the errors below', 'error');
                $this->redirect('admin/users/create');
                return;
            }

            // Create user
            $userId = $this->userModel->createUser($data);

            if ($userId) {
                // Create role-specific record
                if ($data['role'] === 'doctor') {
                    $doctorData = [
                        'user_id' => $userId,
                        'specialization' => $this->post('specialization'),
                        'qualification' => $this->post('qualification'),
                        'experience_years' => (int)$this->post('experience_years'),
                        'license_number' => $this->post('license_number'),
                        'department_id' => $this->post('department_id') ?: null,
                        'available_days' => 'Mon,Tue,Wed,Thu,Fri',
                        'available_time_start' => '09:00:00',
                        'available_time_end' => '17:00:00',
                        'max_patients_per_day' => 20,
                        'is_available' => 1
                    ];
                    $this->doctorModel->create($doctorData);
                } elseif ($data['role'] === 'patient') {
                    $patientData = [
                        'user_id' => $userId,
                        'registration_date' => date('Y-m-d')
                    ];
                    $this->patientModel->create($patientData);
                }

                $this->logActivity('create_user', 'users', $userId);
                $this->setFlash('User created successfully', 'success');
                $this->redirect('admin/users');
            } else {
                $this->setFlash('Failed to create user', 'error');
                $this->redirect('admin/users/create');
            }
            return;
        }

        $departments = $this->departmentModel->all();
        $data = [
            'title' => 'Create User',
            'departments' => $departments,
            'csrf_token' => $this->generateCsrf()
        ];

        $this->render('admin/create-user', $data, 'admin-layout');
    }

    /**
     * Edit User
     */
    public function editUser($id) {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            $this->setFlash('User not found', 'error');
            $this->redirect('admin/users');
            return;
        }

        if ($this->isPost()) {
            // Validate CSRF token
            $csrfToken = $this->post('csrf_token');
            if (!$this->validateCsrf($csrfToken)) {
                $this->setFlash('Invalid security token. Please try again.', 'error');
                $this->redirect('admin/users/edit/' . $id);
                return;
            }

            $data = [
                'full_name' => $this->post('full_name'),
                'phone' => $this->post('phone'),
                'address' => $this->post('address'),
                'is_active' => $this->post('is_active') ? 1 : 0
            ];

            // Check if email is being changed
            $email = $this->post('email');
            if ($email !== $user['email']) {
                if ($this->userModel->findByEmail($email)) {
                    $this->setFlash('Email already exists', 'error');
                    $this->redirect('admin/users/edit/' . $id);
                    return;
                }
                $data['email'] = $email;
            }

            // Check if username is being changed
            $username = $this->post('username');
            if ($username !== $user['username']) {
                if ($this->userModel->findByUsername($username)) {
                    $this->setFlash('Username already exists', 'error');
                    $this->redirect('admin/users/edit/' . $id);
                    return;
                }
                $data['username'] = $username;
            }

            $result = $this->userModel->update($id, $data);

            if ($result) {
                // Update role-specific data
                if ($user['role'] === 'doctor') {
                    $doctor = $this->doctorModel->getByUserId($id);
                    if ($doctor) {
                        $doctorData = [
                            'specialization' => $this->post('specialization'),
                            'qualification' => $this->post('qualification'),
                            'experience_years' => (int)$this->post('experience_years'),
                            'license_number' => $this->post('license_number'),
                            'department_id' => $this->post('department_id') ?: null
                        ];
                        $this->doctorModel->update($doctor['id'], $doctorData);
                    }
                } elseif ($user['role'] === 'patient') {
                    $patient = $this->patientModel->getByUserId($id);
                    if ($patient) {
                        $patientData = [
                            'date_of_birth' => $this->post('date_of_birth'),
                            'gender' => $this->post('gender'),
                            'blood_group' => $this->post('blood_group'),
                            'emergency_contact_name' => $this->post('emergency_contact_name'),
                            'emergency_contact_phone' => $this->post('emergency_contact_phone'),
                            'allergies' => $this->post('allergies'),
                            'chronic_conditions' => $this->post('chronic_conditions')
                        ];
                        $this->patientModel->update($patient['id'], $patientData);
                    }
                }

                $this->logActivity('update_user', 'users', $id);
                $this->setFlash('User updated successfully', 'success');
                $this->redirect('admin/users');
            } else {
                $this->setFlash('Failed to update user', 'error');
                $this->redirect('admin/users/edit/' . $id);
            }
            return;
        }

        // Get role-specific details
        $details = null;
        if ($user['role'] === 'doctor') {
            $details = $this->doctorModel->getByUserId($id);
        } elseif ($user['role'] === 'patient') {
            $details = $this->patientModel->getByUserId($id);
        }

        // Get departments for doctor
        $departments = $this->departmentModel->all();

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'details' => $details,
            'departments' => $departments,
            'csrf_token' => $this->generateCsrf()
        ];

        $this->render('admin/edit-user', $data, 'admin-layout');
    }

    /**
     * Delete User
     */
    public function deleteUser($id) {
        if (!$this->isPost()) {
            $this->redirect('admin/users');
            return;
        }

        // Validate CSRF token
        if (!$this->validateCsrf($this->post('csrf_token'))) {
            $this->setFlash('Invalid security token', 'error');
            $this->redirect('admin/users');
            return;
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            $this->setFlash('User not found', 'error');
            $this->redirect('admin/users');
            return;
        }

        // Don't allow deleting own account
        if ($id == $this->getCurrentUserId()) {
            $this->setFlash('You cannot delete your own account', 'error');
            $this->redirect('admin/users');
            return;
        }

        $result = $this->userModel->delete($id);

        if ($result) {
            $this->logActivity('delete_user', 'users', $id);
            $this->setFlash('User deleted successfully', 'success');
        } else {
            $this->setFlash('Failed to delete user', 'error');
        }

        $this->redirect('admin/users');
    }

    /**
     * Toggle User Status
     */
    public function toggleUserStatus($id) {
        if (!$this->isPost()) {
            $this->redirect('admin/users');
            return;
        }

        // Validate CSRF token
        if (!$this->validateCsrf($this->post('csrf_token'))) {
            $this->setFlash('Invalid security token', 'error');
            $this->redirect('admin/users');
            return;
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            $this->setFlash('User not found', 'error');
            $this->redirect('admin/users');
            return;
        }

        // Don't allow toggling own status
        if ($id == $this->getCurrentUserId()) {
            $this->setFlash('You cannot change your own status', 'error');
            $this->redirect('admin/users');
            return;
        }

        $result = $this->userModel->toggleStatus($id);

        if ($result) {
            $this->logActivity('toggle_user_status', 'users', $id);
            $this->setFlash('User status updated successfully', 'success');
        } else {
            $this->setFlash('Failed to update user status', 'error');
        }

        $this->redirect('admin/users');
    }

    /**
     * Doctor Management - FIXED VERSION
     */
    public function doctors() {
        // Method 1: Try to get doctors from UserModel
        $doctors = $this->userModel->getAllDoctors();
        
        // If empty, try direct database query
        if (empty($doctors)) {
            $sql = "SELECT u.*, d.*, dep.department_name 
                    FROM users u 
                    LEFT JOIN doctors d ON u.id = d.user_id 
                    LEFT JOIN departments dep ON d.department_id = dep.id
                    WHERE u.role = 'doctor'";
            $doctors = $this->db->fetchAll($sql);
        }
        
        // If still empty, get at least the users with doctor role
        if (empty($doctors)) {
            $sql = "SELECT * FROM users WHERE role = 'doctor'";
            $doctorUsers = $this->db->fetchAll($sql);
            
            foreach ($doctorUsers as $user) {
                $doctors[] = [
                    'id' => $user['id'],
                    'user_id' => $user['id'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'profile_image' => $user['profile_image'],
                    'specialization' => 'General Physician',
                    'department_name' => 'Not Assigned',
                    'is_available' => 1
                ];
            }
        }
        
        $data = [
            'title' => 'Doctor Management',
            'doctors' => $doctors
        ];

        $this->render('admin/doctors', $data, 'admin-layout');
    }
    /**
 * View Doctor Schedule
 */
public function doctorSchedule($id) {
    $doctor = $this->userModel->find($id);
    
    if (!$doctor || $doctor['role'] !== 'doctor') {
        $this->setFlash('Doctor not found', 'error');
        $this->redirect('admin/doctors');
        return;
    }
    
    $doctorDetails = $this->doctorModel->getByUserId($id);
    
    $data = [
        'title' => 'Doctor Schedule',
        'doctor' => $doctor,
        'schedule' => $doctorDetails
    ];
    
    $this->render('admin/doctor-schedule', $data, 'admin-layout');
}

/**
 * View Doctor Appointments
 */
public function doctorAppointments($id) {
    $doctor = $this->userModel->find($id);
    
    if (!$doctor || $doctor['role'] !== 'doctor') {
        $this->setFlash('Doctor not found', 'error');
        $this->redirect('admin/doctors');
        return;
    }
    
    $appointments = $this->appointmentModel->getByDoctorId($id);
    
    $data = [
        'title' => 'Doctor Appointments',
        'doctor' => $doctor,
        'appointments' => $appointments
    ];
    
    $this->render('admin/doctor-appointments', $data, 'admin-layout');
}

/**
 * Delete Doctor
 */
public function deleteDoctor($id) {
    if (!$this->isPost()) {
        $this->redirect('admin/doctors');
        return;
    }

    if (!$this->validateCsrf($this->post('csrf_token'))) {
        $this->setFlash('Invalid security token', 'error');
        $this->redirect('admin/doctors');
        return;
    }

    $doctor = $this->userModel->find($id);
    
    if (!$doctor || $doctor['role'] !== 'doctor') {
        $this->setFlash('Doctor not found', 'error');
        $this->redirect('admin/doctors');
        return;
    }

    if ($id == $this->getCurrentUserId()) {
        $this->setFlash('You cannot delete your own account', 'error');
        $this->redirect('admin/doctors');
        return;
    }

    $this->db->beginTransaction();
    
    try {
        $doctorRecord = $this->doctorModel->getByUserId($id);
        if ($doctorRecord) {
            $this->doctorModel->delete($doctorRecord['id']);
        }
        $this->userModel->delete($id);
        $this->db->commit();
        $this->setFlash('Doctor deleted successfully', 'success');
    } catch (Exception $e) {
        $this->db->rollback();
        $this->setFlash('Failed to delete doctor', 'error');
    }

    $this->redirect('admin/doctors');
}
    /**
     * Patient Management
     */
    public function patients() {
        $page = (int)($this->get('page', 1));
        $search = $this->get('search');
        
        if ($search) {
            $patients = $this->patientModel->search($search);
            $total = count($patients);
            $pagination = $this->getPaginationData($total, $page, 10);
            $patients = array_slice($patients, $pagination['offset'], $pagination['per_page']);
        } else {
            $result = $this->patientModel->paginate($page, 10);
            $patients = $result['data'] ?? [];
            $pagination = [
                'total' => $result['total'] ?? 0,
                'page' => $page,
                'per_page' => 10,
                'total_pages' => $result['total_pages'] ?? 1,
                'has_previous' => $page > 1,
                'has_next' => $page < ($result['total_pages'] ?? 1),
                'previous_page' => $page - 1,
                'next_page' => $page + 1,
                'offset' => ($page - 1) * 10
            ];
        }

        $data = [
            'title' => 'Patient Management',
            'patients' => $patients,
            'pagination' => $pagination,
            'search' => $search
        ];

        $this->render('admin/patients', $data, 'admin-layout');
    }

    /**
     * View Patient Details
     */
    public function viewPatient($id) {
        $patient = $this->patientModel->getPatientWithDetails($id);
        
        if (!$patient) {
            $this->setFlash('Patient not found', 'error');
            $this->redirect('admin/patients');
            return;
        }

        $appointments = $this->patientModel->getAppointments($id);
        $medicalHistory = $this->patientModel->getMedicalHistory($id);
        $prescriptions = $this->patientModel->getPrescriptions($id);

        $data = [
            'title' => 'Patient Details',
            'patient' => $patient,
            'appointments' => $appointments,
            'medical_history' => $medicalHistory,
            'prescriptions' => $prescriptions
        ];

        $this->render('admin/view-patient', $data, 'admin-layout');
    }

    /**
     * Department Management
     */
    public function departments() {
        $departments = $this->departmentModel->getWithHeadDoctor();
        
        $data = [
            'title' => 'Department Management',
            'departments' => $departments
        ];

        $this->render('admin/departments', $data, 'admin-layout');
    }

    /**
     * Create Department
     */
    public function createDepartment() {
        if ($this->isPost()) {
            // Validate CSRF token
            if (!$this->validateCsrf($this->post('csrf_token'))) {
                $this->setFlash('Invalid security token', 'error');
                $this->redirect('admin/departments/create');
                return;
            }

            $data = [
                'department_name' => $this->post('department_name'),
                'description' => $this->post('description'),
                'floor_number' => $this->post('floor_number'),
                'extension_number' => $this->post('extension_number'),
                'head_doctor_id' => $this->post('head_doctor_id') ?: null,
                'is_active' => 1
            ];

            $result = $this->departmentModel->create($data);

            if ($result) {
                $this->logActivity('create_department', 'departments', $result);
                $this->setFlash('Department created successfully', 'success');
                $this->redirect('admin/departments');
            } else {
                $this->setFlash('Failed to create department', 'error');
                $this->redirect('admin/departments/create');
            }
            return;
        }

        // Get doctors for head doctor selection
        $doctors = $this->userModel->getAllDoctors();

        $data = [
            'title' => 'Create Department',
            'doctors' => $doctors,
            'csrf_token' => $this->generateCsrf()
        ];

        $this->render('admin/create-department', $data, 'admin-layout');
    }

    /**
     * Edit Department
     */
    public function editDepartment($id) {
        $department = $this->departmentModel->find($id);
        
        if (!$department) {
            $this->setFlash('Department not found', 'error');
            $this->redirect('admin/departments');
            return;
        }

        if ($this->isPost()) {
            // Validate CSRF token
            if (!$this->validateCsrf($this->post('csrf_token'))) {
                $this->setFlash('Invalid security token', 'error');
                $this->redirect('admin/departments/edit/' . $id);
                return;
            }

            $data = [
                'department_name' => $this->post('department_name'),
                'description' => $this->post('description'),
                'floor_number' => $this->post('floor_number'),
                'extension_number' => $this->post('extension_number'),
                'head_doctor_id' => $this->post('head_doctor_id') ?: null,
                'is_active' => $this->post('is_active') ? 1 : 0
            ];

            $result = $this->departmentModel->update($id, $data);

            if ($result) {
                $this->logActivity('update_department', 'departments', $id);
                $this->setFlash('Department updated successfully', 'success');
                $this->redirect('admin/departments');
            } else {
                $this->setFlash('Failed to update department', 'error');
                $this->redirect('admin/departments/edit/' . $id);
            }
            return;
        }

        // Get doctors for head doctor selection
        $doctors = $this->userModel->getAllDoctors();

        $data = [
            'title' => 'Edit Department',
            'department' => $department,
            'doctors' => $doctors,
            'csrf_token' => $this->generateCsrf()
        ];

        $this->render('admin/edit-department', $data, 'admin-layout');
    }

    /**
     * Delete Department
     */
    public function deleteDepartment($id) {
        if (!$this->isPost()) {
            $this->redirect('admin/departments');
            return;
        }

        // Validate CSRF token
        if (!$this->validateCsrf($this->post('csrf_token'))) {
            $this->setFlash('Invalid security token', 'error');
            $this->redirect('admin/departments');
            return;
        }

        $department = $this->departmentModel->find($id);
        
        if (!$department) {
            $this->setFlash('Department not found', 'error');
            $this->redirect('admin/departments');
            return;
        }

        // Check if department has doctors
        $doctorCount = $this->doctorModel->count('department_id = ?', [$id]);
        if ($doctorCount > 0) {
            $this->setFlash('Cannot delete department with assigned doctors', 'error');
            $this->redirect('admin/departments');
            return;
        }

        $result = $this->departmentModel->delete($id);

        if ($result) {
            $this->logActivity('delete_department', 'departments', $id);
            $this->setFlash('Department deleted successfully', 'success');
        } else {
            $this->setFlash('Failed to delete department', 'error');
        }

        $this->redirect('admin/departments');
    }

    /**
     * Appointment Management
     */
    public function appointments() {
        $date = $this->get('date', date('Y-m-d'));
        $status = $this->get('status');
        $doctorId = $this->get('doctor_id') ? (int)$this->get('doctor_id') : null;
        
        $appointments = $this->appointmentModel->getByDateRange($date, $date, $doctorId);
        
        if ($status) {
            $filtered = [];
            foreach ($appointments as $appointment) {
                if ($appointment['status'] == $status) {
                    $filtered[] = $appointment;
                }
            }
            $appointments = $filtered;
        }

        // Get doctors for filter
        $doctors = $this->userModel->getAllDoctors();

        $data = [
            'title' => 'Appointment Management',
            'appointments' => $appointments,
            'selected_date' => $date,
            'selected_status' => $status,
            'selected_doctor' => $doctorId,
            'doctors' => $doctors
        ];

        $this->render('admin/appointments', $data, 'admin-layout');
    }

    /**
     * View Appointment Details
     */
    public function viewAppointment($id) {
        $appointment = $this->appointmentModel->getAppointmentWithDetails($id);
        
        if (!$appointment) {
            $this->setFlash('Appointment not found', 'error');
            $this->redirect('admin/appointments');
            return;
        }

        $data = [
            'title' => 'Appointment Details',
            'appointment' => $appointment
        ];

        $this->render('admin/view-appointment', $data, 'admin-layout');
    }

    /**
     * Cancel Appointment
     */
    public function cancelAppointment($id) {
        if (!$this->isPost()) {
            $this->redirect('admin/appointments');
            return;
        }

        // Validate CSRF token
        if (!$this->validateCsrf($this->post('csrf_token'))) {
            $this->setFlash('Invalid security token', 'error');
            $this->redirect('admin/appointments');
            return;
        }

        $appointment = $this->appointmentModel->find($id);
        
        if (!$appointment) {
            $this->setFlash('Appointment not found', 'error');
            $this->redirect('admin/appointments');
            return;
        }

        $reason = $this->post('cancellation_reason');
        $result = $this->appointmentModel->cancel($id, $this->getCurrentUserId(), $reason);

        if ($result) {
            $this->logActivity('cancel_appointment', 'appointments', $id);
            $this->setFlash('Appointment cancelled successfully', 'success');
        } else {
            $this->setFlash('Failed to cancel appointment', 'error');
        }

        $this->redirect('admin/appointments');
    }

    /**
     * Reports
     */
    public function reports() {
        $reportType = $this->get('type', 'appointments');
        $startDate = $this->get('start_date', date('Y-m-01'));
        $endDate = $this->get('end_date', date('Y-m-t'));
        
        $data = [
            'title' => 'Reports',
            'report_type' => $reportType,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        switch ($reportType) {
            case 'appointments':
                $data['appointments'] = $this->appointmentModel->getByDateRange($startDate, $endDate);
                $data['stats'] = $this->appointmentModel->getStats($startDate, $endDate);
                break;
                
            case 'patients':
                $data['patients'] = $this->patientModel->getRecent(100);
                $data['gender_stats'] = $this->patientModel->countByGender();
                $data['blood_group_stats'] = $this->patientModel->countByBloodGroup();
                break;
                
            case 'doctors':
                $data['doctors'] = $this->userModel->getAllDoctors();
                foreach ($data['doctors'] as &$doctor) {
                    $doctor['appointment_count'] = $this->appointmentModel->count(
                        'doctor_id = ? AND appointment_date BETWEEN ? AND ?',
                        [$doctor['id'], $startDate, $endDate]
                    );
                }
                break;
        }

        $this->render('admin/reports', $data, 'admin-layout');
    }

    /**
     * Export Report
     */
    public function exportReport($type) {
        $startDate = $this->get('start_date', date('Y-m-01'));
        $endDate = $this->get('end_date', date('Y-m-t'));
        $format = $this->get('format', 'csv');

        // Generate report data
        switch ($type) {
            case 'appointments':
                $data = $this->appointmentModel->getByDateRange($startDate, $endDate);
                $filename = "appointments_{$startDate}_to_{$endDate}";
                break;
                
            case 'patients':
                $data = $this->patientModel->getRecent(1000);
                $filename = "patients_{$startDate}_to_{$endDate}";
                break;
                
            default:
                $this->setFlash('Invalid report type', 'error');
                $this->redirect('admin/reports');
                return;
        }

        if ($format === 'csv') {
            $this->exportToCSV($data, $filename);
        } else {
            $this->setFlash('Export format not supported', 'error');
            $this->redirect('admin/reports');
        }
    }

    /**
     * Export data to CSV
     */
    private function exportToCSV($data, $filename) {
        if (empty($data)) {
            $this->setFlash('No data to export', 'error');
            $this->redirect('admin/reports');
            return;
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, array_keys($data[0]));
        
        // Add data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit();
    }

    /**
     * Settings
     */
    public function settings() {
        if ($this->isPost()) {
            // Validate CSRF token
            if (!$this->validateCsrf($this->post('csrf_token'))) {
                $this->setFlash('Invalid security token', 'error');
                $this->redirect('admin/settings');
                return;
            }

            // Update settings
            $settings = [
                'hospital_name' => $this->post('hospital_name'),
                'address' => $this->post('address'),
                'phone' => $this->post('phone'),
                'email' => $this->post('email'),
                'working_hours' => $this->post('working_hours'),
                'appointment_duration' => (int)$this->post('appointment_duration'),
                'max_appointments_per_day' => (int)$this->post('max_appointments_per_day')
            ];

            foreach ($settings as $key => $value) {
                $this->settingModel->set($key, $value);
            }

            $this->setFlash('Settings updated successfully', 'success');
            $this->redirect('admin/settings');
            return;
        }

        // Get current settings
        $settings = $this->settingModel->getAll();

        $data = [
            'title' => 'Settings',
            'settings' => $settings,
            'csrf_token' => $this->generateCsrf()
        ];

        $this->render('admin/settings', $data, 'admin-layout');
    }

    /**
     * Profile
     */
    public function profile() {
        $user = $this->getCurrentUser();
        
        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'csrf_token' => $this->generateCsrf()
        ];

        $this->render('admin/profile', $data, 'admin-layout');
    }

    /**
     * Update Profile
     */
    public function updateProfile() {
        if (!$this->isPost()) {
            $this->redirect('admin/profile');
            return;
        }

        // Validate CSRF token
        if (!$this->validateCsrf($this->post('csrf_token'))) {
            $this->setFlash('Invalid security token', 'error');
            $this->redirect('admin/profile');
            return;
        }

        $userId = $this->getCurrentUserId();

        $data = [
            'full_name' => $this->post('full_name'),
            'phone' => $this->post('phone'),
            'address' => $this->post('address')
        ];

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/profiles/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
            $uploadFile = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
                $data['profile_image'] = $filename;
                
                // Delete old image
                $user = $this->userModel->find($userId);
                if ($user && $user['profile_image'] != 'default-avatar.png') {
                    $oldFile = $uploadDir . $user['profile_image'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
            }
        }

        $result = $this->userModel->updateProfile($userId, $data);

        if ($result) {
            $this->setFlash('Profile updated successfully', 'success');
        } else {
            $this->setFlash('Failed to update profile', 'error');
        }

        $this->redirect('admin/profile');
    }

    /**
     * Change Password
     */
    public function changePassword() {
        if (!$this->isPost()) {
            $this->redirect('admin/profile');
            return;
        }

        // Validate CSRF token
        if (!$this->validateCsrf($this->post('csrf_token'))) {
            $this->setFlash('Invalid security token', 'error');
            $this->redirect('admin/profile');
            return;
        }

        $userId = $this->getCurrentUserId();
        $currentPassword = $this->post('current_password');
        $newPassword = $this->post('new_password');
        $confirmPassword = $this->post('confirm_password');

        // Validate
        if ($newPassword !== $confirmPassword) {
            $this->setFlash('New passwords do not match', 'error');
            $this->redirect('admin/profile');
            return;
        }

        if (strlen($newPassword) < 6) {
            $this->setFlash('Password must be at least 6 characters', 'error');
            $this->redirect('admin/profile');
            return;
        }

        // Verify current password
        if (!$this->userModel->verifyPassword($userId, $currentPassword)) {
            $this->setFlash('Current password is incorrect', 'error');
            $this->redirect('admin/profile');
            return;
        }

        // Update password
        if ($this->userModel->changePassword($userId, $newPassword)) {
            $this->setFlash('Password changed successfully', 'success');
        } else {
            $this->setFlash('Failed to change password', 'error');
        }

        $this->redirect('admin/profile');
    }
}