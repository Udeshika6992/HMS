<!-- index.php -->
<?php include 'header.php'; ?>
<style>

    a {
        text-decoration: none;
        color: #007bff;
    }
    a:hover {
        color: #0056b3;
    }

    /* Banner */
    .banner {
        background: url('assists/home/hospital_bg.jpg') center center no-repeat;
        background-size: cover;
        padding: 250px 0;
        text-align: center;
        color: white;
        position: relative;
    }
    .banner h1 {
        font-size: 4rem;
        font-weight: bold;
        opacity: 0;
        animation: fadeInUp 1.5s ease-out forwards;
    }

    /* Main Sections */
    .section-box {
        background: #f1f5f9;
        padding: 40px;
        border-radius: 8px;
        transition: 0.3s;
        height: 100%;
    }
    .section-box:hover {
        transform: scale(1.03);
        background: #e2e8f0;
    }
    .section-box h4 {
        font-weight: bold;
        margin-bottom: 15px;
    }

    /* About Section */
    .section img {
        width: 100%;
        border-radius: 10px;
    }

    /* Popup message */
    .popup-message {
        position: fixed;
        top: 20%; 
        left: 50%; 
        transform: translate(-50%, -50%); 
        z-index: 1000;
        padding: 15px;
        border-radius: 5px;
        border: 2px solid #000;
        opacity: 0; 
        transition: opacity 0.5s ease, top 0.5s ease;
        width: 400px; 
        text-align: center;
        font-weight: bold;
    }
    .popup-message.success { background: #d4edda; color: #155724; }
    .popup-message.error { background: #f8d7da; color: #721c24; }
    .show { opacity: 1; }

    @keyframes fadeInUp {
        0% { transform: translateY(30px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

</style>

<!-- Banner -->
<section class="banner">
    <h1>Hospital Management System</h1>
</section>

<!-- HMS Panels Overview -->
<section class="py-5 text-center">
    <div class="container">
        <h2 class="mb-4">System Panels</h2>
        <div class="row">

            <!-- Admin -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="section-box">
                    <h4>Admin Panel</h4>
                    <p>Manage doctors, patients, appointments, departments, roles & reports.</p>
                    <a href="admin/login.php" class="btn btn-primary mt-2">Login</a>
                </div>
            </div>

            <!-- Doctor -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="section-box">
                    <h4>Doctor Panel</h4>
                    <p>View appointments, add diagnosis, issue prescriptions, view lab reports.</p>
                    <a href="doctor/login.php" class="btn btn-success mt-2">Login</a>
                </div>
            </div>

            <!-- Patient -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="section-box">
                    <h4>Patient Panel</h4>
                    <p>Book appointments, view medical history, lab results & prescriptions.</p>
                    <a href="patient/login.php" class="btn btn-info mt-2">Login</a>
                </div>
            </div>

            <!-- Staff (Nurse / Reception / Lab) -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="section-box">
                    <h4>Staff Panel</h4>
                    <p>For nurses, receptionists & lab technicians to update patient records.</p>
                    <a href="staff/login.php" class="btn btn-warning mt-2">Login</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- About the Hospital -->
<section class="section bg-light py-5">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6 mb-3">
                <img src="assists/home/hospital_about.jpg" alt="Hospital Image">
            </div>

            <div class="col-md-6">
                <h3>About Our Hospital System</h3>
                <p>
                    This Hospital Management System is designed to streamline medical operations,
                    improve patient care, and support administrative tasks.
                </p>
                <a href="about.php" class="btn btn-danger mt-3">Learn More</a>
            </div>
        </div>
    </div>
</section>

<!-- Footer Script and Popup -->
<script>

function showPopup(message, type) {
    const popup = document.createElement('div');
    popup.className = `popup-message ${type}`;
    popup.textContent = message;
    document.body.appendChild(popup);

    setTimeout(() => popup.classList.add('show'), 10);
    setTimeout(() => {
        popup.classList.remove('show');
        setTimeout(() => popup.remove(), 500);
    }, 3000);
}

<?php if (isset($_SESSION['success'])): ?>
    showPopup("<?php echo $_SESSION['success']; ?>", "success");
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    showPopup("<?php echo $_SESSION['error']; ?>", "error");
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

</script>

<?php include 'footer.php'; ?>
