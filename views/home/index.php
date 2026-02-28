<?php
/**
 * Hospital Management System - Public Homepage
 * This page is visible to everyone before login
 */

// Load required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/functions.php';

// Start session if not started (but don't require login)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Quality Healthcare for Everyone</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50 !important;
        }

        .navbar-brand i {
            color: #3498db;
            margin-right: 10px;
            font-size: 2rem;
        }

        .nav-link {
            font-weight: 500;
            color: #2c3e50 !important;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #3498db !important;
        }

        .btn-login {
            background: transparent;
            border: 2px solid #3498db;
            color: #3498db !important;
            border-radius: 50px;
            padding: 8px 25px !important;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #3498db;
            color: white !important;
        }

        .btn-register {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white !important;
            border-radius: 50px;
            padding: 8px 25px !important;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: 100px 0;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,170.7C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.1;
        }

        .hero-content {
            color: white;
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
            animation: fadeInUp 1s ease 0.2s;
            animation-fill-mode: both;
        }

        .hero-buttons {
            animation: fadeInUp 1s ease 0.4s;
            animation-fill-mode: both;
        }

        .hero-image {
            position: relative;
            z-index: 2;
            animation: float 6s ease-in-out infinite;
        }

        .hero-image img {
            max-width: 100%;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .section-title p {
            font-size: 1.2rem;
            color: #7f8c8d;
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 100px;
            height: 100px;
            line-height: 100px;
            text-align: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-size: 2.5rem;
            border-radius: 50%;
            margin: 0 auto 30px;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #7f8c8d;
            margin-bottom: 0;
        }

        /* Services Section */
        .services {
            padding: 100px 0;
            background: white;
        }

        .service-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e0e0e0;
        }

        .service-card:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
            border-color: transparent;
        }

        .service-card:hover h3,
        .service-card:hover p {
            color: white;
        }

        .service-card i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
            transition: color 0.3s ease;
        }

        .service-card:hover i {
            color: white;
        }

        .service-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        .service-card p {
            color: #7f8c8d;
            margin-bottom: 0;
            transition: color 0.3s ease;
        }

        /* About Section */
        .about {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .about-image {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
        }

        .about-image img {
            width: 100%;
            height: auto;
            transition: transform 0.5s ease;
        }

        .about-image:hover img {
            transform: scale(1.05);
        }

        .about-content h2 {
            font-size: 3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .about-content p {
            font-size: 1.1rem;
            color: #7f8c8d;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .about-list {
            list-style: none;
            padding: 0;
        }

        .about-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .about-list i {
            color: #27ae60;
            font-size: 1.2rem;
        }

        /* Testimonials */
        .testimonials {
            padding: 100px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .testimonial-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.2);
            margin: 20px 0;
        }

        .testimonial-content {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 20px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .testimonial-author img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }

        .author-info h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .author-info p {
            font-size: 0.9rem;
            opacity: 0.8;
            margin: 0;
        }

        .rating {
            color: #ffd700;
            margin-top: 10px;
        }

        /* CTA Section */
        .cta {
            padding: 100px 0;
            background: white;
            text-align: center;
        }

        .cta h2 {
            font-size: 3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .cta p {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-cta {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-cta-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .btn-cta-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        .btn-cta-secondary {
            background: transparent;
            color: #2c3e50;
            border: 2px solid #667eea;
        }

        .btn-cta-secondary:hover {
            background: #667eea;
            color: white;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 60px 0 20px;
        }

        .footer h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
        }

        .footer h4::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background: #667eea;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .footer-contact {
            list-style: none;
            padding: 0;
        }

        .footer-contact li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.7);
        }

        .footer-contact i {
            color: #667eea;
            width: 20px;
        }

        .social-links {
            margin-top: 20px;
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background: rgba(255,255,255,0.1);
            color: white;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: #667eea;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 40px;
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.5);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero-image {
                margin-top: 50px;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .about-content h2 {
                font-size: 2rem;
            }
            
            .cta h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <i class="fas fa-hospital"></i>
                <?php echo APP_NAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-login" href="<?php echo BASE_URL; ?>login">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-register" href="<?php echo BASE_URL; ?>register">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 data-aos="fade-up">Your Health Is Our Priority</h1>
                    <p data-aos="fade-up" data-aos-delay="100">Welcome to Deltota Divisional Hospital. We provide quality healthcare services with compassion and excellence. Your well-being is our mission.</p>
                    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="200">
                        <a href="<?php echo BASE_URL; ?>register" class="btn btn-light btn-lg me-3">
                            <i class="fas fa-calendar-plus me-2"></i>Get Started
                        </a>
                        <a href="#services" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-play me-2"></i>Learn More
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image" data-aos="fade-left">
                        <img src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Hospital" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Why Choose Us</h2>
                <p>We are committed to providing the best healthcare services to our community</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h3>Expert Doctors</h3>
                        <p>Our team consists of highly qualified and experienced medical professionals dedicated to your care.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>24/7 Emergency</h3>
                        <p>Round-the-clock emergency services to ensure you receive immediate care when you need it most.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h3>Modern Equipment</h3>
                        <p>State-of-the-art medical technology and equipment for accurate diagnosis and effective treatment.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Medical Services</h2>
                <p>We offer a comprehensive range of healthcare services to meet your needs</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="service-card">
                        <i class="fas fa-stethoscope"></i>
                        <h3>General Medicine</h3>
                        <p>Comprehensive primary care for patients of all ages.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <i class="fas fa-heartbeat"></i>
                        <h3>Cardiology</h3>
                        <p>Expert heart care with modern diagnostic equipment.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <i class="fas fa-brain"></i>
                        <h3>Neurology</h3>
                        <p>Specialized care for brain and nervous system disorders.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <i class="fas fa-child"></i>
                        <h3>Pediatrics</h3>
                        <p>Gentle and compassionate care for children.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-card">
                        <i class="fas fa-bone"></i>
                        <h3>Orthopedics</h3>
                        <p>Treatment for bone, joint, and muscle conditions.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-card">
                        <i class="fas fa-eye"></i>
                        <h3>Ophthalmology</h3>
                        <p>Complete eye care services and vision correction.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-image">
                        <img src="https://images.unsplash.com/photo-1587351021759-3772687fe598?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="About Us">
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="about-content">
                        <h2>About Deltota Divisional Hospital</h2>
                        <p>For over 35 years, we have been serving our community with dedication and compassion. Our hospital is committed to providing accessible, affordable, and quality healthcare to all.</p>
                        <ul class="about-list">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>24/7 Emergency Services</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Experienced Specialist Doctors</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Modern Medical Equipment</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Affordable Healthcare</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Digital Medical Records</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <div class="container">
            <div class="section-title text-white" data-aos="fade-up">
                <h2 class="text-white">What Our Patients Say</h2>
                <p class="text-white-50">Real stories from people we've had the privilege to care for</p>
            </div>
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <i class="fas fa-quote-left me-2"></i>
                            Excellent service and caring doctors. The digital system makes booking appointments so easy!
                        </div>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Patient">
                            <div class="author-info">
                                <h5>Amara Silva</h5>
                                <p>Patient</p>
                            </div>
                        </div>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <i class="fas fa-quote-left me-2"></i>
                            Very professional staff and clean environment. Highly recommended!
                        </div>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Patient">
                            <div class="author-info">
                                <h5>Sunil Perera</h5>
                                <p>Patient</p>
                            </div>
                        </div>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <i class="fas fa-quote-left me-2"></i>
                            The online portal is great! I can check my medical history anytime.
                        </div>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Patient">
                            <div class="author-info">
                                <h5>Kumari Ratnayake</h5>
                                <p>Patient</p>
                            </div>
                        </div>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta">
        <div class="container">
            <h2 data-aos="fade-up">Ready to Get Started?</h2>
            <p data-aos="fade-up" data-aos-delay="100">Join our healthcare community today and experience quality medical care.</p>
            <div class="cta-buttons" data-aos="fade-up" data-aos-delay="200">
                <a href="<?php echo BASE_URL; ?>register" class="btn btn-cta btn-cta-primary">
                    <i class="fas fa-user-plus me-2"></i>Register Now
                </a>
                <a href="<?php echo BASE_URL; ?>login" class="btn btn-cta btn-cta-secondary">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to Account
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4>About Us</h4>
                    <p><?php echo APP_NAME; ?> has been serving the community for over 35 years, providing quality healthcare services with compassion and excellence.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h4>Our Services</h4>
                    <ul class="footer-links">
                        <li><a href="#">General Medicine</a></li>
                        <li><a href="#">Cardiology</a></li>
                        <li><a href="#">Pediatrics</a></li>
                        <li><a href="#">Orthopedics</a></li>
                        <li><a href="#">Emergency Care</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h4>Contact Info</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Main Street, Deltota</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>081-1234567</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@deltotahospital.lk</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Mon-Fri: 8:00 AM - 8:00 PM<br>Sat: 8:00 AM - 2:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('scrolled');
            } else {
                document.querySelector('.navbar').classList.remove('scrolled');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>