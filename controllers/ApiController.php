<?php
/**
 * API Controller
 * Handles all AJAX requests for dynamic content
 * Location: /controllers/ApiController.php
 */

class ApiController extends Controller {
    
    private $doctorModel;
    private $appointmentModel;
    private $patientModel;
    private $notificationModel;
    
    public function __construct() {
        parent::__construct();
        
        // Load models
        require_once 'models/DoctorModel.php';
        require_once 'models/AppointmentModel.php';
        require_once 'models/PatientModel.php';
        require_once 'models/NotificationModel.php';
        
        $this->doctorModel = new DoctorModel();
        $this->appointmentModel = new AppointmentModel();
        $this->patientModel = new PatientModel();
        $this->notificationModel = new NotificationModel();
    }
    
    /**
     * Get doctors by department (for booking form)
     */
    public function getDoctorsByDepartment() {
        // Set header for JSON response
        header('Content-Type: application/json');
        
        // Get department ID from request
        $departmentId = $_GET['department_id'] ?? 0;
        
        if (!$departmentId) {
            echo json_encode([
                'success' => false,
                'message' => 'Department ID required'
            ]);
            return;
        }
        
        // Get doctors for this department
        $doctors = $this->doctorModel->getByDepartment($departmentId);
        
        // Return as JSON
        echo json_encode([
            'success' => true,
            'data' => $doctors
        ]);
    }
    
    /**
     * Check doctor availability for a specific date
     */
    public function checkDoctorAvailability() {
        header('Content-Type: application/json');
        
        $doctorId = $_GET['doctor_id'] ?? 0;
        $date = $_GET['date'] ?? '';
        
        if (!$doctorId || !$date) {
            echo json_encode([
                'success' => false,
                'message' => 'Doctor ID and date required'
            ]);
            return;
        }
        
        // Get available time slots
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
     * Get patient progress data for charts
     */
    public function getPatientProgress($patientId) {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
            return;
        }
        
        $metric = $_GET['metric'] ?? 'weight';
        $period = $_GET['period'] ?? 30;
        
        // Get progress data based on metric
        switch ($metric) {
            case 'weight':
                $data = $this->patientModel->getVitalsHistory($patientId, $period);
                break;
            case 'blood_pressure':
                $data = $this->patientModel->getVitalsHistory($patientId, $period);
                break;
            default:
                $data = $this->patientModel->getProgressData($patientId, $metric);
        }
        
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
    
    /**
     * Get user notifications
     */
    public function getNotifications() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Not logged in'
            ]);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Get unread notifications
        $notifications = $this->notificationModel->getUnread($userId);
        $count = $this->notificationModel->getUnreadCount($userId);
        
        echo json_encode([
            'success' => true,
            'data' => [
                'notifications' => $notifications,
                'unread_count' => $count
            ]
        ]);
    }
    
    /**
     * Mark notification as read
     */
    public function markNotificationRead($id) {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Not logged in'
            ]);
            return;
        }
        
        $result = $this->notificationModel->markAsRead($id);
        
        echo json_encode([
            'success' => $result
        ]);
    }
    
    /**
     * Search patients (for doctor)
     */
    public function searchPatients() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'doctor') {
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
            return;
        }
        
        $keyword = $_GET['keyword'] ?? '';
        
        if (strlen($keyword) < 2) {
            echo json_encode([
                'success' => false,
                'message' => 'Keyword too short'
            ]);
            return;
        }
        
        $doctorId = $this->doctorModel->getByUserId($_SESSION['user_id'])['id'];
        $patients = $this->doctorModel->searchPatients($doctorId, $keyword);
        
        echo json_encode([
            'success' => true,
            'data' => $patients
        ]);
    }
}