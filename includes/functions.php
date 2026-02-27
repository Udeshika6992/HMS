<?php
/**
 * Helper Functions File
 * Location: /includes/functions.php
 */

// =====================================================
// URL Functions - ALL with function_exists checks
// =====================================================

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return BASE_URL . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header('Location: ' . base_url($url));
        exit();
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return base_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('upload_url')) {
    function upload_url($path) {
        return base_url('uploads/' . ltrim($path, '/'));
    }
}

if (!function_exists('current_url')) {
    function current_url() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}

// =====================================================
// Session Functions
// =====================================================

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('getCurrentUserId')) {
    function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
}

if (!function_exists('getCurrentUserRole')) {
    function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
}

if (!function_exists('hasRole')) {
    function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }
}

if (!function_exists('hasAnyRole')) {
    function hasAnyRole($roles) {
        return isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], $roles);
    }
}

if (!function_exists('setFlash')) {
    function setFlash($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
}

if (!function_exists('getFlash')) {
    function getFlash() {
        if (isset($_SESSION['flash_message'])) {
            $flash = [
                'message' => $_SESSION['flash_message'],
                'type' => $_SESSION['flash_type'] ?? 'info'
            ];
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            return $flash;
        }
        return null;
    }
}

if (!function_exists('showFlash')) {
    function showFlash() {
        $flash = getFlash();
        if ($flash) {
            $alertClass = 'alert-info';
            if ($flash['type'] === 'success') $alertClass = 'alert-success';
            if ($flash['type'] === 'error') $alertClass = 'alert-danger';
            if ($flash['type'] === 'warning') $alertClass = 'alert-warning';
            
            echo "<div class='alert {$alertClass} alert-dismissible fade show'>";
            echo htmlspecialchars($flash['message']);
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
            echo "</div>";
        }
    }
}

// =====================================================
// String Functions
// =====================================================

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('generateToken')) {
    function generateToken() {
        return bin2hex(random_bytes(32));
    }
}

if (!function_exists('slugify')) {
    function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }
}

if (!function_exists('truncate')) {
    function truncate($text, $length = 100, $append = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $append;
    }
}

// =====================================================
// Date Functions
// =====================================================

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'Y-m-d') {
        if (empty($date)) return '';
        return date($format, strtotime($date));
    }
}

if (!function_exists('formatTime')) {
    function formatTime($time, $format = 'h:i A') {
        if (empty($time)) return '';
        return date($format, strtotime($time));
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime($datetime, $format = 'Y-m-d h:i A') {
        if (empty($datetime)) return '';
        return date($format, strtotime($datetime));
    }
}

if (!function_exists('calculateAge')) {
    function calculateAge($birthDate) {
        return date_diff(date_create($birthDate), date_create('today'))->y;
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($datetime) {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;
        
        if ($diff < 60) {
            return $diff . ' seconds ago';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M j, Y', $time);
        }
    }
}

// =====================================================
// Array Functions
// =====================================================

if (!function_exists('array_get')) {
    function array_get($array, $key, $default = null) {
        if (is_null($key)) return $array;
        if (isset($array[$key])) return $array[$key];
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }
        return $array;
    }
}

if (!function_exists('array_has')) {
    function array_has($array, $key) {
        if (empty($array) || is_null($key)) return false;
        if (array_key_exists($key, $array)) return true;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return false;
            }
            $array = $array[$segment];
        }
        return true;
    }
}

// =====================================================
// File Functions
// =====================================================

if (!function_exists('humanFileSize')) {
    function humanFileSize($bytes, $decimals = 2) {
        $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $size[$factor];
    }
}

if (!function_exists('deleteFile')) {
    function deleteFile($path) {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}

// =====================================================
// Debug Functions
// =====================================================

if (!function_exists('dd')) {
    function dd($data) {
        echo '<pre style="background: #f4f4f4; padding: 10px; border: 1px solid #ccc; margin: 10px; border-radius: 5px;">';
        print_r($data);
        echo '</pre>';
        die();
    }
}

if (!function_exists('dump')) {
    function dump($data) {
        echo '<pre style="background: #f4f4f4; padding: 10px; border: 1px solid #ccc; margin: 10px; border-radius: 5px;">';
        print_r($data);
        echo '</pre>';
    }
}

// =====================================================
// Database Helper
// =====================================================

if (!function_exists('db')) {
    function db() {
        return Database::getInstance();
    }
}