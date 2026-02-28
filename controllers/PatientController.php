<?php
/**
 * Patient Controller
 * Handles all patient-related operations
 */

class PatientController extends Controller {
    
    private $patientModel;
    private $appointmentModel;
    private $doctorModel;
    
    public function __construct() {
    parent::__construct();
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'Please login to access this page';
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
    
    // Check if user has patient role
    if ($_SESSION['user_role'] !== 'patient') {
        $_SESSION['error'] = 'Access denied. Patient only area.';
        
        // Redirect to appropriate dashboard based on role
        if ($_SESSION['user_role'] === 'admin') {
            header('Location: ' . BASE_URL . 'admin/dashboard');
        } elseif ($_SESSION['user_role'] === 'doctor') {
            header('Location: ' . BASE_URL . 'doctor/dashboard');
        } else {
            header('Location: ' . BASE_URL);
        }
        exit();
    }
    
    // Load models
    require_once 'models/PatientModel.php';
    require_once 'models/AppointmentModel.php';
    require_once 'models/DoctorModel.php';
    
    $this->patientModel = new PatientModel();
    $this->appointmentModel = new AppointmentModel();
    $this->doctorModel = new DoctorModel();
}

    /**
     * Patient Dashboard
     */
    public function dashboard() {
        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        if (!$patient) {
            $this->setFlash('Patient record not found', 'error');
            $this->redirect('logout');
            return;
        }
        
        $data = [
            'title' => 'Patient Dashboard',
            'patient' => $patient,
            'upcoming_appointments' => $this->patientModel->getUpcomingAppointments($patient['id']),
            'recent_prescriptions' => $this->patientModel->getPrescriptions($patient['id']),
            'stats' => $this->patientModel->getPatientStats($patient['id']),
            'recent_vitals' => $this->patientModel->getVitalsHistory($patient['id'], 5)
        ];
        
        $this->render('patient/dashboard', $data);
    }

    /**
     * Book appointment page
     */
    public function bookAppointment() {
        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        // Get all departments
        $departments = $this->db->getConnection()->query("SELECT * FROM departments WHERE is_active = 1")->fetchAll();
        
        // Get all available doctors
        $doctors = $this->doctorModel->getAvailableDoctors();
        
        $data = [
            'title' => 'Book Appointment',
            'patient' => $patient,
            'departments' => $departments,
            'doctors' => $doctors,
            'min_date' => date('Y-m-d'),
            'max_date' => date('Y-m-d', strtotime('+30 days')),
            'csrf_token' => $this->generateCsrf() // Now this works!
        ];
        
        $this->render('patient/book-appointment', $data);
    }

    /**
     * Process appointment booking
     */
    public function doBookAppointment() {
        if (!$this->isPost()) {
            $this->redirect('patient/book-appointment');
            return;
        }

        // Validate CSRF token
        $token = $_POST['csrf_token'] ?? '';
        if (!$this->validateCsrf($token)) {
            $this->setFlash('Invalid security token. Please try again.', 'error');
            $this->redirect('patient/book-appointment');
            return;
        }

        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        $doctorId = $_POST['doctor_id'] ?? '';
        $appointmentDate = $_POST['appointment_date'] ?? '';
        $appointmentTime = $_POST['appointment_time'] ?? '';
        $symptoms = $_POST['symptoms'] ?? '';

        // Validate
        if (empty($doctorId) || empty($appointmentDate) || empty($appointmentTime) || empty($symptoms)) {
            $this->setFlash('Please fill in all fields', 'error');
            $this->redirect('patient/book-appointment');
            return;
        }

        // Check if doctor is available
        $isAvailable = $this->appointmentModel->checkAvailability(
            $doctorId, 
            $appointmentDate, 
            $appointmentTime
        );

        if (!$isAvailable) {
            $this->setFlash('Selected time slot is not available', 'error');
            $this->redirect('patient/book-appointment');
            return;
        }

        // Create appointment
        $appointmentData = [
            'patient_id' => $patient['id'],
            'doctor_id' => $doctorId,
            'appointment_date' => $appointmentDate,
            'appointment_time' => $appointmentTime,
            'symptoms' => $symptoms,
            'status' => 'pending',
            'created_by' => $userId
        ];

        $appointmentId = $this->appointmentModel->create($appointmentData);

        if ($appointmentId) {
            $this->setFlash('Appointment booked successfully!', 'success');
            $this->redirect('patient/my-appointments');
        } else {
            $this->setFlash('Failed to book appointment', 'error');
            $this->redirect('patient/book-appointment');
        }
    }

    /**
     * Check doctor availability (AJAX)
     */
    public function checkAvailability() {
        if (!$this->isAjax()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $doctorId = $_GET['doctor_id'] ?? '';
        $date = $_GET['date'] ?? '';

        if (!$doctorId || !$date) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            return;
        }

        $availableSlots = $this->appointmentModel->getAvailableTimeSlots($doctorId, $date);
        
        echo json_encode([
            'success' => true,
            'data' => [
                'available_slots' => $availableSlots,
                'date' => $date,
                'doctor_id' => $doctorId
            ]
        ]);
    }

    /**
     * View all appointments
     */
    public function myAppointments() {
        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        $appointments = $this->patientModel->getAppointments($patient['id']);
        
        $data = [
            'title' => 'My Appointments',
            'appointments' => $appointments
        ];
        
        $this->render('patient/my-appointments', $data);
    }

    /**
     * View single appointment
     */
    public function viewAppointment($id) {
        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        $appointment = $this->appointmentModel->getAppointmentWithDetails($id);
        
        if (!$appointment || $appointment['patient_id'] != $patient['id']) {
            $this->setFlash('Appointment not found', 'error');
            $this->redirect('patient/my-appointments');
            return;
        }
        
        $data = [
            'title' => 'Appointment Details',
            'appointment' => $appointment
        ];
        
        $this->render('patient/view-appointment', $data);
    }

    /**
     * Cancel appointment
     */
    public function cancelAppointment($id) {
        if (!$this->isPost()) {
            $this->redirect('patient/my-appointments');
            return;
        }

        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        $appointment = $this->appointmentModel->find($id);
        
        if (!$appointment || $appointment['patient_id'] != $patient['id']) {
            $this->setFlash('Appointment not found', 'error');
            $this->redirect('patient/my-appointments');
            return;
        }

        $reason = $_POST['cancellation_reason'] ?? '';
        $result = $this->appointmentModel->cancel($id, $userId, $reason);

        if ($result) {
            $this->setFlash('Appointment cancelled successfully', 'success');
        } else {
            $this->setFlash('Failed to cancel appointment', 'error');
        }

        $this->redirect('patient/my-appointments');
    }

    /**
     * View medical history
     */
    public function medicalHistory() {
        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        $history = $this->patientModel->getMedicalHistory($patient['id']);
        
        $data = [
            'title' => 'Medical History',
            'history' => $history,
            'patient' => $patient
        ];
        
        $this->render('patient/medical-history', $data);
    }

    /**
     * View single medical record
     */
    public function viewMedicalRecord($id) {
        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        require_once 'models/MedicalRecordModel.php';
        $medicalRecordModel = new MedicalRecordModel();
        $record = $medicalRecordModel->getWithDetails($id);
        
        if (!$record || $record['patient_id'] != $patient['id']) {
            $this->setFlash('Medical record not found', 'error');
            $this->redirect('patient/medical-history');
            return;
        }
        
        $data = [
            'title' => 'Medical Record Details',
            'record' => $record
        ];
        
        $this->render('patient/view-medical-record', $data);
    }

    /**
     * View prescriptions
     */
    public function prescriptions() {
        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        $prescriptions = $this->patientModel->getPrescriptions($patient['id']);
        
        $data = [
            'title' => 'My Prescriptions',
            'prescriptions' => $prescriptions
        ];
        
        $this->render('patient/prescriptions', $data);
    }

    /**
     * View single prescription
     */
    public function viewPrescription($id) {
        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        require_once 'models/PrescriptionModel.php';
        $prescriptionModel = new PrescriptionModel();
        $prescription = $prescriptionModel->getWithDetails($id);
        
        if (!$prescription || $prescription['patient_id'] != $patient['id']) {
            $this->setFlash('Prescription not found', 'error');
            $this->redirect('patient/prescriptions');
            return;
        }
        
        $data = [
            'title' => 'Prescription Details',
            'prescription' => $prescription
        ];
        
        $this->render('patient/view-prescription', $data);
    }

    /**
     * View progress charts
     */
    public function progressCharts() {
        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        $data = [
            'title' => 'My Health Progress',
            'patient' => $patient,
            'include_chart' => true
        ];
        
        $this->render('patient/progress-charts', $data);
    }

    /**
     * Get chart data (AJAX)
     */
    public function getChartData() {
        if (!$this->isAjax()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        $metric = $_GET['metric'] ?? 'weight';
        $period = (int)($_GET['period'] ?? 30);
        
        if ($metric === 'vitals') {
            $data = $this->patientModel->getVitalsHistory($patient['id'], $period);
        } else {
            $data = $this->patientModel->getProgressData($patient['id'], $metric);
        }
        
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * View profile
     */
    public function profile() {
        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);
        
        $data = [
            'title' => 'My Profile',
            'patient' => $patient,
            'user' => $this->getCurrentUser()
        ];
        
        $this->render('patient/profile', $data);
    }

    /**
     * Update profile
     */
    public function updateProfile() {
        if (!$this->isPost()) {
            $this->redirect('patient/profile');
            return;
        }

        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);

        // Update user basic info
        $userData = [
            'full_name' => $_POST['full_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? ''
        ];

        require_once 'models/UserModel.php';
        $userModel = new UserModel();
        $userModel->updateProfile($userId, $userData);

        $this->setFlash('Profile updated successfully', 'success');
        $this->redirect('patient/profile');
    }

    /**
     * Update medical info
     */
    public function updateMedicalInfo() {
        if (!$this->isPost()) {
            $this->redirect('patient/profile');
            return;
        }

        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);

        $patientData = [
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'gender' => $_POST['gender'] ?? '',
            'blood_group' => $_POST['blood_group'] ?? '',
            'allergies' => $_POST['allergies'] ?? '',
            'chronic_conditions' => $_POST['chronic_conditions'] ?? ''
        ];

        $this->patientModel->updateProfile($patient['id'], $patientData);

        $this->setFlash('Medical information updated successfully', 'success');
        $this->redirect('patient/profile');
    }

    /**
     * Update emergency contact
     */
    public function updateEmergencyContact() {
        if (!$this->isPost()) {
            $this->redirect('patient/profile');
            return;
        }

        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);

        $patientData = [
            'emergency_contact_name' => $_POST['emergency_contact_name'] ?? '',
            'emergency_contact_phone' => $_POST['emergency_contact_phone'] ?? '',
            'emergency_contact_relation' => $_POST['emergency_contact_relation'] ?? ''
        ];

        $this->patientModel->updateProfile($patient['id'], $patientData);

        $this->setFlash('Emergency contact updated successfully', 'success');
        $this->redirect('patient/profile');
    }

    /**
     * Change password
     */
    public function changePassword() {
        if (!$this->isPost()) {
            $this->redirect('patient/profile');
            return;
        }

        $userId = $this->getCurrentUserId();
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate
        if ($newPassword !== $confirmPassword) {
            $this->setFlash('New passwords do not match', 'error');
            $this->redirect('patient/profile');
            return;
        }

        if (strlen($newPassword) < 6) {
            $this->setFlash('Password must be at least 6 characters', 'error');
            $this->redirect('patient/profile');
            return;
        }

        require_once 'models/UserModel.php';
        $userModel = new UserModel();
        
        // Verify current password
        if (!$userModel->verifyPassword($userId, $currentPassword)) {
            $this->setFlash('Current password is incorrect', 'error');
            $this->redirect('patient/profile');
            return;
        }

        // Update password
        if ($userModel->updatePassword($userId, $newPassword)) {
            $this->setFlash('Password changed successfully', 'success');
        } else {
            $this->setFlash('Failed to change password', 'error');
        }

        $this->redirect('patient/profile');
    }

    /**
     * Add feedback for appointment
     */
    public function addFeedback($appointmentId) {
        if (!$this->isPost()) {
            $this->redirect('patient/my-appointments');
            return;
        }

        $userId = $this->getCurrentUserId();
        $patient = $this->patientModel->getByUserId($userId);

        $appointment = $this->appointmentModel->find($appointmentId);
        
        if (!$appointment || $appointment['patient_id'] != $patient['id']) {
            $this->setFlash('Appointment not found', 'error');
            $this->redirect('patient/my-appointments');
            return;
        }

        $rating = $_POST['rating'] ?? 5;
        $feedback = $_POST['feedback_text'] ?? '';
        $isAnonymous = isset($_POST['is_anonymous']) ? 1 : 0;

        $data = [
            'patient_id' => $patient['id'],
            'doctor_id' => $appointment['doctor_id'],
            'appointment_id' => $appointmentId,
            'rating' => $rating,
            'feedback_text' => $feedback,
            'is_anonymous' => $isAnonymous
        ];

        $db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO feedback (patient_id, doctor_id, appointment_id, rating, feedback_text, is_anonymous, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $data['patient_id'],
            $data['doctor_id'],
            $data['appointment_id'],
            $data['rating'],
            $data['feedback_text'],
            $data['is_anonymous']
        ]);

        if ($result) {
            $this->setFlash('Thank you for your feedback!', 'success');
        } else {
            $this->setFlash('Failed to submit feedback', 'error');
        }

        $this->redirect('patient/my-appointments');
    }
}