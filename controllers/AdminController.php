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
// ADD DOCTOR (ADMIN)
// =========================
public function addDoctor()
{
    $this->checkAdmin();

    require_once dirname(__DIR__) . '/models/User.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name  = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if ($name && $email && $password) {
            $userModel = new User();
            $userModel->create($name, $email, $password, 'doctor');

            header("Location: index.php?page=manageDoctors");
            exit;
        }

        $error = "All fields are required";
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

// =========================
// EDIT DOCTOR
// =========================
public function editDoctor()
{
    $this->checkAdmin();

    require_once dirname(__DIR__) . '/models/User.php';

    $userModel = new User();

    if (!isset($_GET['id'])) {
        header("Location: index.php?page=manageDoctors");
        exit;
    }

    $doctorId = (int)$_GET['id'];
    $doctor = $userModel->getDoctorById($doctorId);

    if (!$doctor) {
        header("Location: index.php?page=manageDoctors");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name  = trim($_POST['name']);
        $email = trim($_POST['email']);

        if ($name && $email) {
            $userModel->updateDoctor($doctorId, $name, $email);
            header("Location: index.php?page=manageDoctors");
            exit;
        }

        $error = "All fields are required";
    }

    require_once dirname(__DIR__) . '/views/admin/edit_doctor.php';
}
require_once dirname(__DIR__) . '/models/Doctor.php';

class AdminController
{
    private function checkAdmin()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }
    }

    // 📌 VIEW ALL DOCTORS
    public function manageDoctor()
    {
        $this->checkAdmin();
        $doctorModel = new Doctor();
        $doctors = $doctorModel->getAll();
        require_once dirname(__DIR__) . '/views/admin/manage_doctor.php';
    }

    // ➕ ADD DOCTOR
    public function addDoctor()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $doctorModel = new Doctor();
            $doctorModel->create(
                $_POST['name'],
                $_POST['email'],
                $_POST['specialization']
            );
            header("Location: index.php?page=manageDoctor");
            exit;
        }

        require_once dirname(__DIR__) . '/views/admin/add_doctor.php';
    }

    // ✏️ EDIT DOCTOR
    public function editDoctor()
    {
        $this->checkAdmin();
        $doctorModel = new Doctor();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $doctorModel->update(
                $_POST['id'],
                $_POST['name'],
                $_POST['email'],
                $_POST['specialization']
            );
            header("Location: index.php?page=manageDoctor");
            exit;
        }

        $doctor = $doctorModel->getById($_GET['id']);
        require_once dirname(__DIR__) . '/views/admin/edit_doctor.php';
    }

    // ❌ DELETE DOCTOR
    public function deleteDoctor()
    {
        $this->checkAdmin();
        $doctorModel = new Doctor();
        $doctorModel->delete($_GET['id']);
        header("Location: index.php?page=manageDoctor");
        exit;
    }
}


}
