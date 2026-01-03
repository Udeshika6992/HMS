<?php
require_once dirname(__DIR__) . '/models/User.php';
require_once dirname(__DIR__) . '/models/Patient.php';
require_once dirname(__DIR__) . '/models/Doctor.php';
require_once dirname(__DIR__) . '/models/Appointment.php';
require_once dirname(__DIR__) . '/models/Visit.php';

class AdminController
{
    // =========================
    // ADMIN DASHBOARD
    // =========================
    public function dashboard()
    {
        $this->checkAdmin();

        $userModel = new User();
        $patientModel = new Patient();
        $doctorModel = new Doctor();
        $appointmentModel = new Appointment();

        $totalUsers       = $userModel->countAll();
        $patientCount     = $patientModel->countAll();
        $doctorCount      = $doctorModel->countAll();
        $appointmentCount = $appointmentModel->countAll();

        require_once dirname(__DIR__) . '/views/admin/dashboard.php';
    }

    // =========================
    // MANAGE USERS
    // =========================
    public function users()
    {
        $this->checkAdmin();

        $userModel = new User();
        $users = $userModel->getAll();

        require_once dirname(__DIR__) . '/views/admin/users.php';
    }

    // =========================
    // DELETE USER
    // =========================
    public function deleteUser()
    {
        $this->checkAdmin();

        if (isset($_GET['id'])) {
            $userModel = new User();
            $userModel->delete($_GET['id']);
        }

        header("Location: index.php?page=adminUsers");
        exit;
    }

    // =========================
    // VIEW PATIENT HISTORY
    // =========================
    public function patientHistory()
    {
        $this->checkAdmin();

        $history = null;

        if (isset($_GET['search'])) {
            $visitModel = new Visit();
            $history = $visitModel->searchHistory($_GET['search']);
        }

        require_once dirname(__DIR__) . '/views/admin/patient_history.php';
    }

    // =========================
    // ACCESS CONTROL
    // =========================
    private function checkAdmin()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }
    }

    // =========================
    // ADD DOCTOR (ADMIN ONLY)
   // =========================
   public function addDoctor()
   {
    $this->checkAdmin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userModel = new User();

        $name  = $_POST['name'];
        $email = $_POST['email'];
        $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $userModel->create($name, $email, $pass, 'doctor');

        header("Location: index.php?page=adminUsers");
        exit;
    }

    require_once dirname(__DIR__) . '/views/admin/add_doctor.php';
}

// =========================
// VIEW ALL DOCTORS
// =========================
public function manageDoctors()
{
    $this->checkAdmin();

    require_once dirname(__DIR__) . '/models/User.php';

    $userModel = new User();

    // Get only doctors
    $doctors = $userModel->getDoctors();

    require_once dirname(__DIR__) . '/views/admin/manage_doctors.php';
}


}
