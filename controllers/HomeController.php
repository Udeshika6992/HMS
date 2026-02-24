<?php
/**
 * ========================================
 * HOSPITAL MANAGEMENT SYSTEM - HOME CONTROLLER
 * Author: M.G. Udeshika Saman Kumari
 * Project: Delthota Divisional Hospital HMS
 * Description: Manages public pages, landing page, and general site information
 * ========================================
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Testimonial.php';
require_once __DIR__ . '/../models/News.php';
require_once __DIR__ . '/../models/Contact.php';

class HomeController extends Controller
{
    /**
     * @var Doctor $doctorModel
     */
    private $doctorModel;
    
    /**
     * @var Department $departmentModel
     */
    private $departmentModel;
    
    /**
     * @var Appointment $appointmentModel
     */
    private $appointmentModel;
    
    /**
     * @var User $userModel
     */
    private $userModel;
    
    /**
     * @var Testimonial $testimonialModel
     */
    private $testimonialModel;
    
    /**
     * @var News $newsModel
     */
    private $newsModel;
    
    /**
     * @var Contact $contactModel
     */
    private $contactModel;
    
    /**
     * Constructor - Initialize models
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->doctorModel = new Doctor();
        $this->departmentModel = new Department();
        $this->appointmentModel = new Appointment();
        $this->userModel = new User();
        
        // Optional models - create if they exist
        $this->initializeOptionalModels();
    }
    
    /**
     * Initialize optional models if table exists
     */
    private function initializeOptionalModels()
    {
        try {
            // Check if testimonials table exists
            $testimonialCheck = $this->doctorModel->getConnection()->query("SHOW TABLES LIKE 'testimonials'");
            if ($testimonialCheck->rowCount() > 0) {
                require_once __DIR__ . '/../models/Testimonial.php';
                $this->testimonialModel = new Testimonial();
            }
        } catch (Exception $e) {
            // Table doesn't exist, ignore
        }
        
        try {
            // Check if news table exists
            $newsCheck = $this->doctorModel->getConnection()->query("SHOW TABLES LIKE 'news'");
            if ($newsCheck->rowCount() > 0) {
                require_once __DIR__ . '/../models/News.php';
                $this->newsModel = new News();
            }
        } catch (Exception $e) {
            // Table doesn't exist, ignore
        }
        
        try {
            // Check if contacts table exists
            $contactCheck = $this->doctorModel->getConnection()->query("SHOW TABLES LIKE 'contacts'");
            if ($contactCheck->rowCount() > 0) {
                require_once __DIR__ . '/../models/Contact.php';
                $this->contactModel = new Contact();
            }
        } catch (Exception $e) {
            // Table doesn't exist, ignore
        }
    }
    
    /**
     * ========================================
     * MAIN PUBLIC PAGES
     * ========================================
     */
    
    /**
     * Homepage / Landing Page
     */
    public function index()
    {
        try {
            // Get statistics for homepage
            $stats = [
                'doctors' => $this->doctorModel->count(),
                'patients' => $this->userModel->countByRole('patient'),
                'appointments' => $this->appointmentModel->count(),
                'departments' => $this->departmentModel->count(),
                'years_of_service' => 15, // Static value - can be dynamic
                'happy_patients' => $this->userModel->countByRole('patient') * 0.8 // Approximate
            ];
            
            // Get featured doctors (limit 4)
            $featuredDoctors = $this->doctorModel->getFeaturedDoctors(4);
            
            // Get departments (limit 6)
            $departments = $this->departmentModel->getDepartmentsWithDoctorCount(6);
            
            // Get testimonials if model exists
            $testimonials = [];
            if ($this->testimonialModel) {
                $testimonials = $this->testimonialModel->getActiveTestimonials(3);
            }
            
            // Get latest news if model exists
            $latestNews = [];
            if ($this->newsModel) {
                $latestNews = $this->newsModel->getLatestNews(3);
            }
            
            $data = [
                'stats' => $stats,
                'featuredDoctors' => $featuredDoctors,
                'departments' => $departments,
                'testimonials' => $testimonials,
                'latestNews' => $latestNews,
                'pageTitle' => 'Welcome to ' . APP_NAME,
                'metaDescription' => 'Delthota Divisional Hospital - Providing quality healthcare services to the community.',
                'currentPage' => 'home'
            ];
            
            $this->view('home/index', $data);
            
        } catch (Exception $e) {
            error_log("Homepage Error: " . $e->getMessage());
            
            // Still show homepage with minimal data
            $data = [
                'pageTitle' => 'Welcome to ' . APP_NAME,
                'currentPage' => 'home',
                'error' => APP_DEBUG ? $e->getMessage() : null
            ];
            
            $this->view('home/index', $data);
        }
    }
    
    /**
     * About Us Page
     */
    public function about()
    {
        try {
            // Get hospital statistics
            $stats = [
                'doctors' => $this->doctorModel->count(),
                'patients' => $this->userModel->countByRole('patient'),
                'appointments' => $this->appointmentModel->count(),
                'departments' => $this->departmentModel->count(),
                'established' => 2010,
                'beds' => 250,
                'staff' => $this->doctorModel->count() + $this->userModel->countByRole('admin') + 50 // Approximate
            ];
            
            // Get department list
            $departments = $this->departmentModel->all();
            
            $data = [
                'stats' => $stats,
                'departments' => $departments,
                'pageTitle' => 'About Us',
                'currentPage' => 'about',
                'metaDescription' => 'Learn about Delthota Divisional Hospital - our mission, vision, and commitment to healthcare.'
            ];
            
            $this->view('home/about', $data);
            
        } catch (Exception $e) {
            error_log("About Page Error: " . $e->getMessage());
            $this->view('home/about', ['pageTitle' => 'About Us']);
        }
    }
    
    /**
     * Contact Us Page
     */
    public function contact()
    {
        $data = [
            'pageTitle' => 'Contact Us',
            'currentPage' => 'contact',
            'metaDescription' => 'Get in touch with Delthota Divisional Hospital. Find our location, phone numbers, and email.',
            'hospital' => [
                'name' => 'Delthota Divisional Hospital',
                'address' => 'Main Street, Delthota, Sri Lanka',
                'phone' => '+94 81 123 4567',
                'emergency' => '+94 81 123 4568',
                'email' => 'info@delhotahospital.lk',
                'emergency_email' => 'emergency@delhotahospital.lk',
                'latitude' => 7.1833,
                'longitude' => 80.5833
            ],
            'workingHours' => [
                'monday_friday' => '8:00 AM - 8:00 PM',
                'saturday' => '8:00 AM - 2:00 PM',
                'sunday' => 'Emergency Only',
                'emergency' => '24/7'
            ]
        ];
        
        $this->view('home/contact', $data);
    }
    
    /**
     * Process contact form submission
     */
    public function sendContact()
    {
        if (!$this->isPost()) {
            $this->redirect('/contact');
            return;
        }
        
        try {
            $data = $this->getPostData();
            
            // Validate required fields
            $errors = $this->validateRequired($data, ['name', 'email', 'subject', 'message']);
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                $this->redirect('/contact');
                return;
            }
            
            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->setFlash('error', 'Please enter a valid email address');
                $this->redirect('/contact');
                return;
            }
            
            // Save to database if contact model exists
            if ($this->contactModel) {
                $this->contactModel->create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? '',
                    'subject' => $data['subject'],
                    'message' => $data['message'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? ''
                ]);
            }
            
            // Send email notification (implement with PHPMailer)
            $this->sendContactEmail($data);
            
            $this->setFlash('success', 'Thank you for contacting us. We will get back to you soon!');
            
        } catch (Exception $e) {
            error_log("Contact Form Error: " . $e->getMessage());
            $this->setFlash('error', 'Sorry, there was an error sending your message. Please try again.');
        }
        
        $this->redirect('/contact');
    }
    
    /**
     * Send contact form email
     * @param array $data Form data
     */
    private function sendContactEmail($data)
    {
        // This is a placeholder for email functionality
        // In production, use PHPMailer or similar
        $to = 'info@delhotahospital.lk';
        $subject = 'Contact Form: ' . $data['subject'];
        $message = "Name: " . $data['name'] . "\n";
        $message .= "Email: " . $data['email'] . "\n";
        $message .= "Phone: " . ($data['phone'] ?? 'Not provided') . "\n\n";
        $message .= "Message:\n" . $data['message'];
        
        // Log instead of sending in development
        if (APP_DEBUG) {
            error_log("Contact Email would be sent:\n" . $message);
        } else {
            // mail($to, $subject, $message);
        }
    }
    
    /**
     * ========================================
     * DOCTOR RELATED PAGES
     * ========================================
     */
    
    /**
     * Doctors Listing Page
     */
    public function doctors()
    {
        try {
            // Get filter parameters
            $department = $_GET['department'] ?? '';
            $specialization = $_GET['specialization'] ?? '';
            $search = $_GET['search'] ?? '';
            
            // Get all doctors with filters
            $doctors = $this->doctorModel->getAllDoctors($department, $specialization, $search);
            
            // Get departments for filter dropdown
            $departments = $this->departmentModel->all();
            
            // Get unique specializations
            $specializations = $this->doctorModel->getAllSpecializations();
            
            $data = [
                'doctors' => $doctors,
                'departments' => $departments,
                'specializations' => $specializations,
                'filters' => [
                    'department' => $department,
                    'specialization' => $specialization,
                    'search' => $search
                ],
                'pageTitle' => 'Our Doctors',
                'currentPage' => 'doctors',
                'metaDescription' => 'Meet our team of qualified and experienced doctors at Delthota Divisional Hospital.'
            ];
            
            $this->view('home/doctors', $data);
            
        } catch (Exception $e) {
            error_log("Doctors Page Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading doctors');
            $this->redirect('/');
        }
    }
    
    /**
     * Single Doctor Profile Page
     * @param int $id Doctor ID
     */
    public function doctor($id)
    {
        try {
            // Get doctor details
            $doctor = $this->doctorModel->getDoctorWithUser($id);
            
            if (!$doctor) {
                $this->setFlash('error', 'Doctor not found');
                $this->redirect('/doctors');
                return;
            }
            
            // Get available time slots for next 7 days
            $availability = [];
            for ($i = 0; $i < 7; $i++) {
                $date = date('Y-m-d', strtotime("+{$i} days"));
                $availability[$date] = $this->doctorModel->getAvailableSlots($id, $date);
            }
            
            $data = [
                'doctor' => $doctor,
                'availability' => $availability,
                'pageTitle' => 'Dr. ' . $doctor['name'],
                'currentPage' => 'doctors',
                'metaDescription' => 'Profile of Dr. ' . $doctor['name'] . ' - ' . $doctor['specialization'] . ' at Delthota Divisional Hospital.'
            ];
            
            $this->view('home/doctor_profile', $data);
            
        } catch (Exception $e) {
            error_log("Doctor Profile Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading doctor profile');
            $this->redirect('/doctors');
        }
    }
    
    /**
     * ========================================
     * DEPARTMENT RELATED PAGES
     * ========================================
     */
    
    /**
     * Departments Listing Page
     */
    public function departments()
    {
        try {
            // Get all departments with doctor counts
            $departments = $this->departmentModel->getDepartmentsWithDoctorCount();
            
            $data = [
                'departments' => $departments,
                'pageTitle' => 'Our Departments',
                'currentPage' => 'departments',
                'metaDescription' => 'Explore the various medical departments at Delthota Divisional Hospital.'
            ];
            
            $this->view('home/departments', $data);
            
        } catch (Exception $e) {
            error_log("Departments Page Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading departments');
            $this->redirect('/');
        }
    }
    
    /**
     * Single Department Page
     * @param int $id Department ID
     */
    public function department($id)
    {
        try {
            // Get department details
            $department = $this->departmentModel->find($id);
            
            if (!$department) {
                $this->setFlash('error', 'Department not found');
                $this->redirect('/departments');
                return;
            }
            
            // Get doctors in this department
            $doctors = $this->doctorModel->getDoctorsByDepartment($id);
            
            $data = [
                'department' => $department,
                'doctors' => $doctors,
                'pageTitle' => $department['name'] . ' Department',
                'currentPage' => 'departments',
                'metaDescription' => 'Learn about our ' . $department['name'] . ' department at Delthota Divisional Hospital.'
            ];
            
            $this->view('home/department', $data);
            
        } catch (Exception $e) {
            error_log("Department Page Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading department');
            $this->redirect('/departments');
        }
    }
    
    /**
     * ========================================
     * SERVICES PAGES
     * ========================================
     */
    
    /**
     * Services Page
     */
    public function services()
    {
        $services = [
            [
                'icon' => 'fa-stethoscope',
                'title' => 'General Medicine',
                'description' => 'Comprehensive primary healthcare services for all age groups.',
                'features' => ['Health Checkups', 'Disease Prevention', 'Chronic Disease Management']
            ],
            [
                'icon' => 'fa-heartbeat',
                'title' => 'Cardiology',
                'description' => 'Expert care for heart-related conditions and diseases.',
                'features' => ['ECG', 'Echocardiography', 'Cardiac Consultation']
            ],
            [
                'icon' => 'fa-brain',
                'title' => 'Neurology',
                'description' => 'Specialized care for brain and nervous system disorders.',
                'features' => ['Neurological Exams', 'Headache Management', 'Stroke Care']
            ],
            [
                'icon' => 'fa-child',
                'title' => 'Pediatrics',
                'description' => 'Complete healthcare for infants, children, and adolescents.',
                'features' => ['Well-child Visits', 'Vaccinations', 'Developmental Screening']
            ],
            [
                'icon' => 'fa-bone',
                'title' => 'Orthopedics',
                'description' => 'Treatment for bone, joint, and muscle conditions.',
                'features' => ['Fracture Care', 'Joint Replacement', 'Sports Medicine']
            ],
            [
                'icon' => 'fa-eye',
                'title' => 'Ophthalmology',
                'description' => 'Comprehensive eye care and vision services.',
                'features' => ['Eye Exams', 'Cataract Surgery', 'Glaucoma Treatment']
            ],
            [
                'icon' => 'fa-tooth',
                'title' => 'Dentistry',
                'description' => 'Complete dental care for all ages.',
                'features' => ['Cleanings', 'Fillings', 'Root Canals']
            ],
            [
                'icon' => 'fa-ambulance',
                'title' => 'Emergency Care',
                'description' => '24/7 emergency medical services.',
                'features' => ['Trauma Care', 'Acute Illness', 'Emergency Surgery']
            ],
            [
                'icon' => 'fa-flask',
                'title' => 'Laboratory Services',
                'description' => 'Advanced diagnostic testing and analysis.',
                'features' => ['Blood Tests', 'Urinalysis', 'Pathology']
            ]
        ];
        
        $data = [
            'services' => $services,
            'pageTitle' => 'Our Services',
            'currentPage' => 'services',
            'metaDescription' => 'Discover the comprehensive medical services offered at Delthota Divisional Hospital.'
        ];
        
        $this->view('home/services', $data);
    }
    
    /**
     * ========================================
     * APPOINTMENT PAGES
     * ========================================
     */
    
    /**
     * Online Appointment Booking Page (Public)
     */
    public function bookAppointment()
    {
        try {
            // Get departments for dropdown
            $departments = $this->departmentModel->all();
            
            // Get doctors list
            $doctors = $this->doctorModel->getAllDoctors();
            
            $data = [
                'departments' => $departments,
                'doctors' => $doctors,
                'pageTitle' => 'Book Appointment',
                'currentPage' => 'book-appointment',
                'metaDescription' => 'Book your appointment online at Delthota Divisional Hospital.'
            ];
            
            $this->view('home/book_appointment', $data);
            
        } catch (Exception $e) {
            error_log("Book Appointment Page Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading booking page');
            $this->redirect('/');
        }
    }
    
    /**
     * Process public appointment booking
     */
    public function submitAppointment()
    {
        if (!$this->isPost()) {
            $this->redirect('/book-appointment');
            return;
        }
        
        try {
            $data = $this->getPostData();
            
            // Validate required fields
            $errors = $this->validateRequired($data, ['name', 'email', 'phone', 'doctor_id', 'appointment_date', 'appointment_time']);
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                $this->redirect('/book-appointment');
                return;
            }
            
            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->setFlash('error', 'Please enter a valid email address');
                $this->redirect('/book-appointment');
                return;
            }
            
            // Check if slot is available
            $isAvailable = $this->appointmentModel->checkAvailability(
                $data['doctor_id'],
                $data['appointment_date'],
                $data['appointment_time']
            );
            
            if (!$isAvailable) {
                $this->setFlash('error', 'This time slot is not available. Please choose another time.');
                $this->redirect('/book-appointment');
                return;
            }
            
            // Check if user exists, if not create temporary patient record
            $user = $this->userModel->findByEmail($data['email']);
            $patientId = null;
            
            if ($user) {
                $patientId = $user['id'];
            } else {
                // Create temporary patient record (you might want to send them a registration link)
                $patientId = $this->createTemporaryPatient($data);
            }
            
            if (!$patientId) {
                $this->setFlash('error', 'Error creating patient record');
                $this->redirect('/book-appointment');
                return;
            }
            
            // Create appointment
            $appointmentData = [
                'patient_id' => $patientId,
                'doctor_id' => $data['doctor_id'],
                'appointment_date' => $data['appointment_date'],
                'appointment_time' => $data['appointment_time'],
                'symptoms' => $data['symptoms'] ?? '',
                'status' => 'pending',
                'is_public_booking' => 1
            ];
            
            $appointmentId = $this->appointmentModel->create($appointmentData);
            
            if ($appointmentId) {
                // Send confirmation email
                $this->sendAppointmentConfirmation($data);
                
                $this->setFlash('success', 'Appointment booked successfully! A confirmation has been sent to your email.');
            } else {
                $this->setFlash('error', 'Failed to book appointment. Please try again.');
            }
            
        } catch (Exception $e) {
            error_log("Submit Appointment Error: " . $e->getMessage());
            $this->setFlash('error', 'Error booking appointment: ' . (APP_DEBUG ? $e->getMessage() : 'Please try again'));
        }
        
        $this->redirect('/book-appointment');
    }
    
    /**
     * Create temporary patient record for public booking
     * @param array $data Patient data
     * @return int|false Patient ID
     */
    private function createTemporaryPatient($data)
    {
        try {
            // Generate a temporary password
            $tempPassword = bin2hex(random_bytes(4));
            
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($tempPassword, PASSWORD_DEFAULT),
                'role' => 'patient',
                'is_active' => 0 // Inactive until they verify/complete registration
            ];
            
            $userId = $this->userModel->create($userData);
            
            if ($userId) {
                // Send registration link email
                $this->sendRegistrationLink($data['email'], $tempPassword);
            }
            
            return $userId;
            
        } catch (Exception $e) {
            error_log("Create Temporary Patient Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send appointment confirmation email
     * @param array $data Appointment data
     */
    private function sendAppointmentConfirmation($data)
    {
        // Placeholder for email functionality
        if (APP_DEBUG) {
            error_log("Appointment confirmation email would be sent to: " . $data['email']);
        }
    }
    
    /**
     * Send registration link email
     * @param string $email User email
     * @param string $tempPassword Temporary password
     */
    private function sendRegistrationLink($email, $tempPassword)
    {
        // Placeholder for email functionality
        if (APP_DEBUG) {
            error_log("Registration link would be sent to: " . $email . " with temp password: " . $tempPassword);
        }
    }
    
    /**
     * ========================================
     * NEWS & ANNOUNCEMENTS
     * ========================================
     */
    
    /**
     * News & Announcements Page
     */
    public function news()
    {
        try {
            if (!$this->newsModel) {
                $this->redirect('/');
                return;
            }
            
            $page = $_GET['page'] ?? 1;
            $limit = PAGINATION_LIMIT ?? 10;
            
            $news = $this->newsModel->getPaginatedNews($page, $limit);
            $totalNews = $this->newsModel->count();
            
            $data = [
                'news' => $news,
                'pagination' => [
                    'current' => $page,
                    'total' => ceil($totalNews / $limit),
                    'limit' => $limit
                ],
                'pageTitle' => 'News & Announcements',
                'currentPage' => 'news',
                'metaDescription' => 'Stay updated with the latest news and announcements from Delthota Divisional Hospital.'
            ];
            
            $this->view('home/news', $data);
            
        } catch (Exception $e) {
            error_log("News Page Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading news');
            $this->redirect('/');
        }
    }
    
    /**
     * Single News Article
     * @param int $id News ID
     */
    public function newsArticle($id)
    {
        try {
            if (!$this->newsModel) {
                $this->redirect('/');
                return;
            }
            
            $article = $this->newsModel->find($id);
            
            if (!$article) {
                $this->setFlash('error', 'Article not found');
                $this->redirect('/news');
                return;
            }
            
            $data = [
                'article' => $article,
                'pageTitle' => $article['title'],
                'currentPage' => 'news',
                'metaDescription' => substr(strip_tags($article['content']), 0, 160)
            ];
            
            $this->view('home/news_article', $data);
            
        } catch (Exception $e) {
            error_log("News Article Error: " . $e->getMessage());
            $this->setFlash('error', 'Error loading article');
            $this->redirect('/news');
        }
    }
    
    /**
     * ========================================
     * SEARCH FUNCTIONALITY
     * ========================================
     */
    
    /**
     * Global Search
     */
    public function search()
    {
        try {
            $query = $_GET['q'] ?? '';
            
            if (empty($query) || strlen($query) < 3) {
                $this->setFlash('warning', 'Please enter at least 3 characters to search');
                $this->redirect('/');
                return;
            }
            
            $results = [
                'doctors' => $this->doctorModel->searchDoctors($query),
                'departments' => $this->departmentModel->search($query)
            ];
            
            // Search news if model exists
            if ($this->newsModel) {
                $results['news'] = $this->newsModel->search($query, 5);
            }
            
            $data = [
                'query' => $query,
                'results' => $results,
                'pageTitle' => 'Search Results for "' . htmlspecialchars($query) . '"',
                'currentPage' => 'search',
                'metaDescription' => 'Search results for ' . htmlspecialchars($query) . ' at Delthota Divisional Hospital.'
            ];
            
            $this->view('home/search', $data);
            
        } catch (Exception $e) {
            error_log("Search Error: " . $e->getMessage());
            $this->setFlash('error', 'Error performing search');
            $this->redirect('/');
        }
    }
    
    /**
     * ========================================
     * SITEMAP & LEGAL PAGES
     * ========================================
     */
    
    /**
     * Sitemap Page
     */
    public function sitemap()
    {
        $data = [
            'pageTitle' => 'Sitemap',
            'currentPage' => 'sitemap'
        ];
        
        $this->view('home/sitemap', $data);
    }
    
    /**
     * Terms of Service Page
     */
    public function terms()
    {
        $data = [
            'pageTitle' => 'Terms of Service',
            'currentPage' => 'terms',
            'lastUpdated' => '2025-01-01'
        ];
        
        $this->view('home/terms', $data);
    }
    
    /**
     * Privacy Policy Page
     */
    public function privacy()
    {
        $data = [
            'pageTitle' => 'Privacy Policy',
            'currentPage' => 'privacy',
            'lastUpdated' => '2025-01-01'
        ];
        
        $this->view('home/privacy', $data);
    }
    
    /**
     * FAQs Page
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'How do I book an appointment?',
                'answer' => 'You can book an appointment online through our website, by phone, or by visiting the hospital in person.'
            ],
            [
                'question' => 'What are the hospital visiting hours?',
                'answer' => 'Visiting hours are from 10:00 AM to 12:00 PM and 4:00 PM to 6:00 PM daily.'
            ],
            [
                'question' => 'Do I need insurance to be treated?',
                'answer' => 'No, we provide healthcare services to all patients regardless of insurance status. We accept various insurance plans.'
            ],
            [
                'question' => 'What should I bring to my appointment?',
                'answer' => 'Please bring your ID, insurance card (if applicable), list of current medications, and any relevant medical records.'
            ],
            [
                'question' => 'Is parking available at the hospital?',
                'answer' => 'Yes, we have free parking available for patients and visitors.'
            ],
            [
                'question' => 'How do I get my medical reports?',
                'answer' => 'You can access your medical reports through our patient portal or request them at the medical records department.'
            ]
        ];
        
        $data = [
            'faqs' => $faqs,
            'pageTitle' => 'Frequently Asked Questions',
            'currentPage' => 'faq',
            'metaDescription' => 'Find answers to commonly asked questions about Delthota Divisional Hospital.'
        ];
        
        $this->view('home/faq', $data);
    }
    
    /**
     * ========================================
     * ERROR PAGES
     * ========================================
     */
    
    /**
     * 404 Not Found Page
     */
    public function notFound()
    {
        http_response_code(404);
        
        $data = [
            'pageTitle' => 'Page Not Found',
            'message' => 'The page you are looking for does not exist or has been moved.'
        ];
        
        $this->view('errors/404', $data);
    }
    
    /**
     * 403 Forbidden Page
     */
    public function forbidden()
    {
        http_response_code(403);
        
        $data = [
            'pageTitle' => 'Access Denied',
            'message' => 'You do not have permission to access this page.'
        ];
        
        $this->view('errors/403', $data);
    }
    
    /**
     * 500 Server Error Page
     */
    public function serverError()
    {
        http_response_code(500);
        
        $data = [
            'pageTitle' => 'Server Error',
            'message' => 'An internal server error occurred. Please try again later.'
        ];
        
        $this->view('errors/500', $data);
    }
    
    /**
     * ========================================
     * UTILITY METHODS
     * ========================================
     */
    
    /**
     * Change language
     */
    public function language($lang)
    {
        $allowedLangs = ['en', 'si', 'ta']; // English, Sinhala, Tamil
        if (in_array($lang, $allowedLangs)) {
            $_SESSION['language'] = $lang;
            setcookie('language', $lang, time() + (86400 * 30), '/');
        }
        
        // Redirect back
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $referer);
        exit;
    }
    
    /**
     * Toggle theme (light/dark)
     */
    public function theme($mode)
    {
        if (in_array($mode, ['light', 'dark'])) {
            $_SESSION['theme'] = $mode;
            setcookie('theme', $mode, time() + (86400 * 30), '/');
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $referer);
        exit;
    }
    
    /**
     * Site health check (for monitoring)
     */
    public function health()
    {
        header('Content-Type: application/json');
        
        $status = 'healthy';
        $checks = [];
        
        // Check database connection
        try {
            $this->doctorModel->getConnection()->query('SELECT 1');
            $checks['database'] = 'ok';
        } catch (Exception $e) {
            $status = 'unhealthy';
            $checks['database'] = 'error: ' . $e->getMessage();
        }
        
        // Check required directories
        $requiredDirs = [
            UPLOAD_PATH,
            STORAGE_PATH ?? __DIR__ . '/../storage',
            LOG_DIR ?? __DIR__ . '/../logs'
        ];
        
        foreach ($requiredDirs as $dir) {
            if (defined($dir)) {
                $checks['dir_' . basename($dir)] = is_writable($dir) ? 'writable' : 'not writable';
            }
        }
        
        echo json_encode([
            'status' => $status,
            'timestamp' => date('Y-m-d H:i:s'),
            'environment' => APP_ENV ?? 'development',
            'debug' => APP_DEBUG ?? false,
            'checks' => $checks
        ], JSON_PRETTY_PRINT);
    }
}