<?php
require_once dirname(__DIR__) . '/models/Patient.php';
require_once dirname(__DIR__) . '/models/Doctor.php';
require_once dirname(__DIR__) . '/models/Appointment.php';
require_once dirname(__DIR__) . '/models/Visit.php';
require_once dirname(__DIR__) . '/progress/ProgressAnalyzer.php';

class PatientController
{
    // =========================
    // PATIENT DASHBOARD
    // =========================
    public function dashboard()
    {
        $this->checkPatient();

        $patientId = $_SESSION['user_id'];

        $patientModel = new Patient();
        $visitModel   = new Visit();

        $visitCount = $visitModel->countByPatient($patientId);
        $visits     = $visitModel->getByPatient($patientId);

        // Progress analysis (rule-based)
        $analyzer = new ProgressAnalyzer();
        $progressStatus = $analyzer->getStatus($visitCount);

        require_once dirname(__DIR__) . '/views/patient/dashboard.php';
    }

    // =========================
    // DOCTOR APPOINTMENT / CHANNELING
    // =========================
    public function appointment()
    {
        $this->checkPatient();

        $doctorModel      = new Doctor();
        $appointmentModel = new Appointment();

        // Filter doctors by disease/specialization
        if (isset($_GET['disease']) && $_GET['disease'] !== '') {
            $doctors = $doctorModel->searchBySpecialization($_GET['disease']);
        } else {
            $doctors = $doctorModel->getAll();
        }

        // Handle appointment request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointmentModel->create(
                $_SESSION['user_id'],
                $_POST['doctor_id'],
                $_POST['appointment_date']
            );

            header("Location: index.php?page=patient");
            exit;
        }

        require_once dirname(__DIR__) . '/views/patient/appointment.php';
    }

    // =========================
    // DOCTOR PROFILE
    // =========================
    public function doctorProfile()
    {
        $this->checkPatient();

        if (!isset($_GET['id'])) {
            header("Location: index.php?page=appointment");
            exit;
        }

        $doctorId = $_GET['id'];

        $doctorModel      = new Doctor();
        $appointmentModel = new Appointment();

        $doctor = $doctorModel->getById($doctorId);
        $appointmentCount = $appointmentModel->countByDoctor($doctorId);

        require_once dirname(__DIR__) . '/views/patient/doctor_profile.php';
    }

    // =========================
    // ACCESS CONTROL (PATIENT)
    // =========================
    private function checkPatient()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'patient') {
            header("Location: index.php?page=login");
            exit;
        }
    }
}
