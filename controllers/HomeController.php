<?php
/**
 * Home Controller - Public pages
 */

class HomeController extends Controller {
    
    public function index() {
        $data = [
            'title' => 'Welcome to ' . APP_NAME
        ];
        
        // Use public layout (no sidebar)
        $this->render('home/index', $data, 'public');
    }
    
    public function about() {
        $data = [
            'title' => 'About Us'
        ];
        
        $this->render('home/about', $data, 'public');
    }
    
    public function contact() {
        $data = [
            'title' => 'Contact Us'
        ];
        
        $this->render('home/contact', $data, 'public');
    }
}