<?php
require_once dirname(__DIR__) . '/models/Appointment.php';
require_once dirname(__DIR__) . '/models/Visit.php';


class DoctorController
{
    // =========================
    // DOCTOR DASHBOARD
    // =========================
    public function dashboard()
    {
        $this->checkDoctor();

        $appointmentModel = new Appointment();

        $appointmentCount = $appointmentModel->countAll();
        $pendingCount     = $appointmentModel->countByStatus('Pending');
        $approvedCount    = $appointmentModel->countByStatus('Approved');

        require_once dirname(__DIR__) . '/views/doctor/dashboard.php';
    }

    // =========================
    // VIEW ALL APPOINTMENTS
    // =========================
    public function appointments()
    {
        $this->checkDoctor();

        $appointmentModel = new Appointment();
        $appointments = $appointmentModel->getAll();

        require_once dirname(__DIR__) . '/views/doctor/appointments.php';
    }

    // =========================
    // APPROVE / REJECT APPOINTMENTS
    // =========================
    public function approveAppointments()
    {
        $this->checkDoctor();

        $appointmentModel = new Appointment();

        // Update status if requested
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $appointmentModel->updateStatus($_GET['id'], $_GET['status']);
            header("Location: index.php?page=doctorApprove");
            exit;
        }

        // Load appointment list
        $appointments = $appointmentModel->getAll();

        require_once dirname(__DIR__) . '/views/doctor/approve_appointments.php';
    }

    // =========================
    // ADD VISIT RECORD (DOCTOR)
    // =========================
    public function addVisit()
    {
        $this->checkDoctor();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $visitModel = new Visit();
            $visitModel->addVisit(
                 $_POST['patient_id'],
                 $_SESSION['user_id'], // doctor_id
                 $_POST['notes']
);


            header("Location: index.php?page=doctorHistory");
            exit;
        }
    }

    // =========================
    // VIEW PATIENT HISTORY
    // =========================
    public function patientHistory()
    {
        $this->checkDoctor();

        $history = null;

        if (isset($_GET['search'])) {
            $visitModel = new Visit();
            $history = $visitModel->searchHistory($_GET['search']);
        }

        require_once dirname(__DIR__) . '/views/doctor/patient_history.php';
    }

    // =========================
    // ACCESS CONTROL (DOCTOR)
    // =========================
    private function checkDoctor()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
            header("Location: index.php?page=login");
            exit;
        }
    }


    // =========================
    // VISITS PATIEONTS HISTORY
    // =========================

    public function visits()
{
    // Doctor must be logged in
    $this->checkDoctor();

    require_once dirname(__DIR__) . '/models/Visit.php';

    $visitModel = new Visit();
    $doctorId = $_SESSION['user_id'];

    // Get visits handled by this doctor
    $visits = $visitModel->getByDoctor($doctorId);

    // Load view
    require_once dirname(__DIR__) . '/views/doctor/visits.php';
}

}
