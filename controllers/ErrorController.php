<?php
/**
 * Error Controller
 * Handles error pages
 * Location: /controllers/ErrorController.php
 */

class ErrorController extends Controller {
    
    /**
     * 404 Not Found page
     */
    public function notFound() {
        http_response_code(404);
        
        $data = [
            'title' => '404 - Page Not Found',
            'message' => 'The page you are looking for could not be found.',
            'code' => 404
        ];
        
        $this->render('errors/404', $data, 'error');
    }
    
    /**
     * 403 Forbidden page
     */
    public function forbidden() {
        http_response_code(403);
        
        $data = [
            'title' => '403 - Access Denied',
            'message' => 'You do not have permission to access this page.',
            'code' => 403
        ];
        
        $this->render('errors/403', $data, 'error');
    }
    
    /**
     * 500 Server Error page
     */
    public function serverError() {
        http_response_code(500);
        
        $data = [
            'title' => '500 - Server Error',
            'message' => 'An internal server error occurred. Please try again later.',
            'code' => 500
        ];
        
        $this->render('errors/500', $data, 'error');
    }
}