<?php
/**
 * Home Controller
 * Handles public pages
 * Location: /controllers/HomeController.php
 */

class HomeController extends Controller {
    
    /**
     * Home page
     */
    public function index() {
        $data = [
            'title' => 'Welcome to Hospital Management System',
            'message' => 'Your health is our priority'
        ];
        
        $this->render('home/index', $data);
    }
    
    /**
     * About page
     */
    public function about() {
        $data = [
            'title' => 'About Us',
            'hospital_name' => 'Deltota Divisional Hospital',
            'established' => '1985',
            'description' => 'Serving the community for over 35 years with quality healthcare.',
            'mission' => 'To provide accessible, affordable, and quality healthcare to all members of our community with compassion and respect.',
            'vision' => 'A healthy community where everyone has access to comprehensive healthcare services.'
        ];
        
        $this->render('home/about', $data);
    }
    
    /**
     * Contact page
     */
    public function contact() {
        $data = [
            'title' => 'Contact Us',
            'email' => 'info@deltotahospital.lk',
            'phone' => '081-1234567',
            'address' => 'Main Street, Deltota',
            'working_hours' => [
                'Monday - Friday' => '8:00 AM - 8:00 PM',
                'Saturday' => '8:00 AM - 2:00 PM',
                'Sunday' => 'Emergency Only'
            ]
        ];
        
        $this->render('home/contact', $data);
    }
}