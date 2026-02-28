<?php
/**
 * Doctor Controller
 * Handles all doctor-related operations
 * Location: /controllers/DoctorController.php
 */
class DoctorController extends Controller {
    
    private $doctorModel;
    private $appointmentModel;
    private $patientModel;
    private $medicalRecordModel;
    
    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in and is doctor
        if (!$this->isLoggedIn() || $this->getCurrentUserRole() !== 'doctor') {
            $this->setFlash('Please login to access this page', 'error');
            $this->redirect('login');
            return;
        }
        
        // Initialize models
        $this->doctorModel = $this->model('Doctor');
        $this->appointmentModel = $this->model('Appointment');
        $this->patientModel = $this->model('Patient');
        $this->medicalRecordModel = $this->model('MedicalRecord');
    }

    /**
     * Doctor Dashboard
     */
    public function dashboard() {
        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        if (!$doctor) {
            $this->setFlash('Doctor record not found', 'error');
            $this->redirect('logout');
            return;
        }
        
        $data = [
            'title' => 'Doctor Dashboard',
            'doctor' => $doctor,
            'today_appointments' => $this->doctorModel->getTodayAppointments($doctor['id']),
            'upcoming_appointments' => $this->doctorModel->getUpcomingAppointments($doctor['id']),
            'stats' => $this->doctorModel->getDoctorStats($doctor['id']),
            'recent_patients' => $this->doctorModel->getRecentPatients($doctor['id'], 5),
            'monthly_stats' => $this->doctorModel->getMonthlyStats($doctor['id'])
        ];
        
        $this->render('doctor/dashboard', $data);
    }

    /**
     * View appointments
     */
    public function appointments() {
        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        $date = $this->get('date', date('Y-m-d'));
        $status = $this->get('status');
        
        $appointments = $this->doctorModel->getAppointments($doctor['id'], $date, $status);
        
        $data = [
            'title' => 'Appointments',
            'appointments' => $appointments,
            'selected_date' => $date,
            'selected_status' => $status,
            'stats' => [
                'total' => count($appointments),
                'pending' => count(array_filter($appointments, function($a) { return $a['status'] == 'pending'; })),
                'confirmed' => count(array_filter($appointments, function($a) { return $a['status'] == 'confirmed'; })),
                'completed' => count(array_filter($appointments, function($a) { return $a['status'] == 'completed'; }))
            ]
        ];
        
        $this->render('doctor/appointments', $data);
    }

    /**
     * View single appointment
     */
    public function viewAppointment($id) {
        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        $appointment = $this->appointmentModel->getAppointmentWithDetails($id);
        
        if (!$appointment || $appointment['doctor_id'] != $doctor['id']) {
            $this->setFlash('Appointment not found', 'error');
            $this->redirect('doctor/appointments');
            return;
        }
        
        // Get patient medical history
        $medicalHistory = $this->doctorModel->getPatientMedicalHistory($appointment['patient_id']);
        
        $data = [
            'title' => 'Appointment Details',
            'appointment' => $appointment,
            'medical_history' => $medicalHistory,
            'patient' => $this->doctorModel->getPatientDetails($doctor['id'], $appointment['patient_id'])
        ];
        
        $this->render('doctor/view-appointment', $data);
    }

    /**
     * Update appointment status
     */
    public function updateAppointmentStatus($id) {
        if (!$this->isPost()) {
            $this->redirect('doctor/appointments');
            return;
        }

        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        $appointment = $this->appointmentModel->find($id);
        
        if (!$appointment || $appointment['doctor_id'] != $doctor['id']) {
            if ($this->isAjax()) {
                $this->jsonError('Appointment not found');
            } else {
                $this->setFlash('Appointment not found', 'error');
                $this->redirect('doctor/appointments');
            }
            return;
        }

        $status = $this->post('status');
        $notes = $this->post('notes');

        $result = $this->appointmentModel->updateStatus($id, $status, $notes);

        if ($this->isAjax()) {
            if ($result) {
                $this->jsonSuccess('Appointment status updated successfully');
            } else {
                $this->jsonError('Failed to update appointment status');
            }
        } else {
            if ($result) {
                $this->setFlash('Appointment status updated successfully', 'success');
            } else {
                $this->setFlash('Failed to update appointment status', 'error');
            }
            $this->redirect('doctor/view-appointment/' . $id);
        }
    }

    /**
     * View patients list
     */
    public function patients() {
        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        $patients = $this->doctorModel->getPatients($doctor['id']);
        
        $data = [
            'title' => 'My Patients',
            'patients' => $patients
        ];
        
        $this->render('doctor/patients', $data);
    }

    /**
     * View single patient
     */
    public function viewPatient($patientId) {
        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        $patient = $this->doctorModel->getPatientDetails($doctor['id'], $patientId);
        
        if (!$patient) {
            $this->setFlash('Patient not found', 'error');
            $this->redirect('doctor/patients');
            return;
        }
        
        $medicalHistory = $this->doctorModel->getPatientMedicalHistory($patientId);
        $vitals = $this->patientModel->getVitalsHistory($patientId, 10);
        
        $data = [
            'title' => 'Patient Details',
            'patient' => $patient,
            'medical_history' => $medicalHistory,
            'vitals' => $vitals
        ];
        
        $this->render('doctor/view-patient', $data);
    }

    /**
     * Add medical record
     */
    public function addMedicalRecord($appointmentId) {
        if (!$this->isPost()) {
            $this->redirect('doctor/appointments');
            return;
        }

        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        $appointment = $this->appointmentModel->find($appointmentId);
        
        if (!$appointment || $appointment['doctor_id'] != $doctor['id']) {
            if ($this->isAjax()) {
                $this->jsonError('Appointment not found');
            } else {
                $this->setFlash('Appointment not found', 'error');
                $this->redirect('doctor/appointments');
            }
            return;
        }

        $data = [
            'patient_id' => $appointment['patient_id'],
            'doctor_id' => $doctor['id'],
            'appointment_id' => $appointmentId,
            'record_date' => date('Y-m-d'),
            'visit_type' => $this->post('visit_type'),
            'chief_complaint' => $this->post('chief_complaint'),
            'symptoms' => $this->post('symptoms'),
            'diagnosis' => $this->post('diagnosis'),
            'treatment_plan' => $this->post('treatment_plan'),
            'doctor_notes' => $this->post('doctor_notes'),
            'follow_up_required' => $this->post('follow_up_required') ? 1 : 0,
            'follow_up_date' => $this->post('follow_up_date') ?: null
        ];

        $recordId = $this->medicalRecordModel->create($data);

        if ($recordId) {
            // Update appointment status to completed
            $this->appointmentModel->updateStatus($appointmentId, 'completed', 'Consultation completed');

            // Add vitals if provided
            if ($this->post('blood_pressure_systolic') || $this->post('weight')) {
                $vitalsData = [
                    'patient_id' => $appointment['patient_id'],
                    'doctor_id' => $doctor['id'],
                    'appointment_id' => $appointmentId,
                    'record_date' => date('Y-m-d'),
                    'blood_pressure_systolic' => $this->post('blood_pressure_systolic') ?: null,
                    'blood_pressure_diastolic' => $this->post('blood_pressure_diastolic') ?: null,
                    'heart_rate' => $this->post('heart_rate') ?: null,
                    'temperature' => $this->post('temperature') ?: null,
                    'weight' => $this->post('weight') ?: null,
                    'height' => $this->post('height') ?: null,
                    'blood_sugar_fasting' => $this->post('blood_sugar_fasting') ?: null,
                    'oxygen_saturation' => $this->post('oxygen_saturation') ?: null,
                    'notes' => $this->post('vitals_notes')
                ];
                $this->db->insert('patient_vitals', $vitalsData);
            }

            // Add prescriptions if provided
            $medicines = $this->post('medicine_name');
            if (!empty($medicines)) {
                foreach ($medicines as $key => $medicine) {
                    if (!empty($medicine)) {
                        $prescriptionData = [
                            'medical_record_id' => $recordId,
                            'patient_id' => $appointment['patient_id'],
                            'doctor_id' => $doctor['id'],
                            'medicine_name' => $medicine,
                            'dosage' => $this->post('dosage')[$key],
                            'frequency' => $this->post('frequency')[$key],
                            'duration' => $this->post('duration')[$key],
                            'instructions' => $this->post('instructions')[$key],
                            'quantity' => $this->post('quantity')[$key] ?: null,
                            'refills' => $this->post('refills')[$key] ?: 0
                        ];
                        $this->db->insert('prescriptions', $prescriptionData);
                    }
                }
            }

            if ($this->isAjax()) {
                $this->jsonSuccess('Medical record added successfully', ['record_id' => $recordId]);
            } else {
                $this->setFlash('Medical record added successfully', 'success');
            }
        } else {
            if ($this->isAjax()) {
                $this->jsonError('Failed to add medical record');
            } else {
                $this->setFlash('Failed to add medical record', 'error');
            }
        }

        $this->redirect('doctor/view-appointment/' . $appointmentId);
    }

    /**
     * Add prescription
     */
    public function addPrescription($patientId) {
        if (!$this->isPost()) {
            $this->redirect('doctor/patients');
            return;
        }

        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);

        $medicines = $this->post('medicine_name');
        
        if (!empty($medicines)) {
            foreach ($medicines as $key => $medicine) {
                if (!empty($medicine)) {
                    $prescriptionData = [
                        'patient_id' => $patientId,
                        'doctor_id' => $doctor['id'],
                        'medicine_name' => $medicine,
                        'dosage' => $this->post('dosage')[$key],
                        'frequency' => $this->post('frequency')[$key],
                        'duration' => $this->post('duration')[$key],
                        'instructions' => $this->post('instructions')[$key],
                        'quantity' => $this->post('quantity')[$key] ?: null,
                        'refills' => $this->post('refills')[$key] ?: 0,
                        'start_date' => date('Y-m-d')
                    ];
                    $this->db->insert('prescriptions', $prescriptionData);
                }
            }
            
            if ($this->isAjax()) {
                $this->jsonSuccess('Prescriptions added successfully');
            } else {
                $this->setFlash('Prescriptions added successfully', 'success');
            }
        } else {
            if ($this->isAjax()) {
                $this->jsonError('No prescriptions added');
            } else {
                $this->setFlash('No prescriptions added', 'error');
            }
        }

        $this->redirect('doctor/view-patient/' . $patientId);
    }

    /**
     * Add patient vitals
     */
    public function addVitals($patientId) {
        if (!$this->isPost()) {
            $this->redirect('doctor/patients');
            return;
        }

        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);

        $vitalsData = [
            'patient_id' => $patientId,
            'doctor_id' => $doctor['id'],
            'record_date' => date('Y-m-d'),
            'blood_pressure_systolic' => $this->post('blood_pressure_systolic') ?: null,
            'blood_pressure_diastolic' => $this->post('blood_pressure_diastolic') ?: null,
            'heart_rate' => $this->post('heart_rate') ?: null,
            'temperature' => $this->post('temperature') ?: null,
            'weight' => $this->post('weight') ?: null,
            'height' => $this->post('height') ?: null,
            'blood_sugar_fasting' => $this->post('blood_sugar_fasting') ?: null,
            'blood_sugar_random' => $this->post('blood_sugar_random') ?: null,
            'oxygen_saturation' => $this->post('oxygen_saturation') ?: null,
            'respiratory_rate' => $this->post('respiratory_rate') ?: null,
            'notes' => $this->post('notes')
        ];

        $result = $this->db->insert('patient_vitals', $vitalsData);

        if ($this->isAjax()) {
            if ($result) {
                $this->jsonSuccess('Vitals added successfully');
            } else {
                $this->jsonError('Failed to add vitals');
            }
        } else {
            if ($result) {
                $this->setFlash('Vitals added successfully', 'success');
            } else {
                $this->setFlash('Failed to add vitals', 'error');
            }
        }

        $this->redirect('doctor/view-patient/' . $patientId);
    }

    /**
     * Add progress tracking
     */
    public function addProgress($patientId) {
        if (!$this->isPost()) {
            $this->redirect('doctor/patients');
            return;
        }

        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);

        $progressData = [
            'patient_id' => $patientId,
            'doctor_id' => $doctor['id'],
            'tracking_date' => date('Y-m-d'),
            'metric_name' => $this->post('metric_name'),
            'metric_value' => $this->post('metric_value'),
            'metric_unit' => $this->post('metric_unit'),
            'notes' => $this->post('notes')
        ];

        $result = $this->db->insert('progress_tracking', $progressData);

        if ($this->isAjax()) {
            if ($result) {
                $this->jsonSuccess('Progress data added successfully');
            } else {
                $this->jsonError('Failed to add progress data');
            }
        } else {
            if ($result) {
                $this->setFlash('Progress data added successfully', 'success');
            } else {
                $this->setFlash('Failed to add progress data', 'error');
            }
        }

        $this->redirect('doctor/view-patient/' . $patientId);
    }

    /**
     * View schedule
     */
    public function schedule() {
        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        $data = [
            'title' => 'My Schedule',
            'doctor' => $doctor,
            'available_days' => explode(',', $doctor['available_days'] ?? 'Mon,Tue,Wed,Thu,Fri')
        ];
        
        $this->render('doctor/schedule', $data);
    }

    /**
     * Update schedule
     */
    public function updateSchedule() {
        if (!$this->isPost()) {
            $this->redirect('doctor/schedule');
            return;
        }

        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);

        $scheduleData = [
            'available_days' => implode(',', $this->post('available_days', [])),
            'available_time_start' => $this->post('available_time_start'),
            'available_time_end' => $this->post('available_time_end'),
            'max_patients_per_day' => (int)$this->post('max_patients_per_day')
        ];

        $result = $this->doctorModel->updateSchedule($doctor['id'], $scheduleData);

        if ($result) {
            $this->setFlash('Schedule updated successfully', 'success');
        } else {
            $this->setFlash('Failed to update schedule', 'error');
        }

        $this->redirect('doctor/schedule');
    }

    /**
     * Profile
     */
    public function profile() {
        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        $data = [
            'title' => 'My Profile',
            'doctor' => $doctor,
            'user' => $this->getCurrentUser()
        ];
        
        $this->render('doctor/profile', $data);
    }

    /**
     * Update profile
     */
    public function updateProfile() {
        if (!$this->isPost()) {
            $this->redirect('doctor/profile');
            return;
        }

        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);

        // Update user basic info
        $userData = [
            'full_name' => $this->post('full_name'),
            'phone' => $this->post('phone'),
            'address' => $this->post('address')
        ];

        $userModel = $this->model('User');
        $userModel->updateProfile($userId, $userData);

        // Update doctor specific info
        $doctorData = [
            'specialization' => $this->post('specialization'),
            'qualification' => $this->post('qualification'),
            'experience_years' => (int)$this->post('experience_years'),
            'bio' => $this->post('bio')
        ];

        $this->doctorModel->update($doctor['id'], $doctorData);

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadFile(
                $_FILES['profile_image'],
                PROFILE_PATH,
                ['allowed_types' => ['jpg', 'jpeg', 'png'], 'create_thumb' => true]
            );
            
            if ($uploadResult['success']) {
                $userModel->update($userId, ['profile_image' => $uploadResult['filename']]);
            }
        }

        $this->setFlash('Profile updated successfully', 'success');
        $this->redirect('doctor/profile');
    }

    /**
     * Change Password
     */
    public function changePassword() {
        if (!$this->isPost()) {
            $this->redirect('doctor/profile');
            return;
        }

        $userId = $this->getCurrentUserId();
        $currentPassword = $this->post('current_password');
        $newPassword = $this->post('new_password');
        $confirmPassword = $this->post('confirm_password');

        // Validate
        if ($newPassword !== $confirmPassword) {
            $this->setFlash('New passwords do not match', 'error');
            $this->redirect('doctor/profile');
            return;
        }

        if (strlen($newPassword) < 6) {
            $this->setFlash('Password must be at least 6 characters', 'error');
            $this->redirect('doctor/profile');
            return;
        }

        $userModel = $this->model('User');
        
        // Verify current password
        if (!$userModel->verifyPassword($userId, $currentPassword)) {
            $this->setFlash('Current password is incorrect', 'error');
            $this->redirect('doctor/profile');
            return;
        }

        // Update password
        if ($userModel->changePassword($userId, $newPassword)) {
            $this->setFlash('Password changed successfully', 'success');
        } else {
            $this->setFlash('Failed to change password', 'error');
        }

        $this->redirect('doctor/profile');
    }

    /**
     * Search patients (AJAX)
     */
    public function searchPatients() {
        if (!$this->isAjax()) {
            $this->jsonError('Invalid request');
            return;
        }

        $keyword = $this->get('keyword');
        
        if (strlen($keyword) < 2) {
            $this->jsonError('Keyword too short');
            return;
        }

        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        $patients = $this->doctorModel->searchPatients($doctor['id'], $keyword);
        
        $this->jsonSuccess('Patients found', $patients);
    }

    /**
     * Get patient progress data (AJAX)
     */
    public function getPatientProgress($patientId) {
        if (!$this->isAjax()) {
            $this->jsonError('Invalid request');
            return;
        }

        $userId = $this->getCurrentUserId();
        $doctor = $this->doctorModel->getByUserId($userId);
        
        $metric = $this->get('metric', 'all');
        $period = (int)$this->get('period', 30);
        
        if ($metric === 'vitals') {
            $data = $this->patientModel->getVitalsHistory($patientId, $period);
        } else {
            $data = $this->patientModel->getProgressData($patientId, $metric);
        }
        
        $this->jsonSuccess('Data retrieved', $data);
    }

    /**
     * JSON Success Response - FIXED: Added this method to ensure it exists
     */
    protected function jsonSuccess($message = 'Success', $data = null, $statusCode = 200) {
        $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * JSON Error Response - FIXED: Added this method to ensure it exists
     */
    protected function jsonError($message = 'Error', $errors = null, $statusCode = 400) {
        $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}