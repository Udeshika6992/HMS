<?php
session_start(); 
?>
<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        /* Navbar Styles */
        .navbar {
            background-color: #42536d; /* Professional hospital tone */
            padding: 20px 0;    
        }

        .navbar-brand .first-line {
            color: #00e1ff;
            display: block;
            font-family: Arial, sans-serif;
            font-size: 40px;
            font-weight: bold;
        }

        .navbar-brand .second-line {
            color: white;
            display: block;
            font-family: Arial, sans-serif;
            font-size: 20px;
        }

        .navbar-nav .nav-link {
            color: white;
            transition: 0.3s;
            font-size: 18px;
        }
        .navbar-nav .nav-link:hover {
            color: #00e1ff;
        }

        .navbar-toggler {
            border-color: #00e1ff;
        }

        .btn-login {
            background-color: #00c6ff;
            border-color: #00c6ff;
            color: white;
            margin-left: 10px;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: white;
            color: #00c6ff;
        }

        /* Line Under Navbar */
        .red-line {
            height: 4px;
            background-color: #7a7a7a;
            width: 100%;
        }

        /* Animation */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            <span class="first-line">HMS</span>
            <span class="second-line">Hospital Management System</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About Hospital</a></li>
                <li class="nav-item"><a class="nav-link" href="services.php">Our Services</a></li>
                <li class="nav-item"><a class="nav-link" href="departments.php">Departments</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true): ?>
                    <li>
                        <a class="btn btn-login" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li>
                        <a class="btn btn-login" href="login.php">Login</a>
                    </li>
                <?php endif; ?>

            </ul>

        </div>
    </div>
</nav>

<div class="red-line"></div>
