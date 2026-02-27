<?php
/**
 * Validation Functions File
 * Location: /includes/validation.php
 */

// =====================================================
// Basic Validation Functions
// =====================================================

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate URL
 */
function validateUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Validate IP address
 */
function validateIp($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP) !== false;
}

/**
 * Validate integer
 */
function validateInt($value, $min = null, $max = null) {
    if (!filter_var($value, FILTER_VALIDATE_INT)) {
        return false;
    }
    if ($min !== null && $value < $min) return false;
    if ($max !== null && $value > $max) return false;
    return true;
}

/**
 * Validate float
 */
function validateFloat($value) {
    return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
}

/**
 * Validate boolean
 */
function validateBoolean($value) {
    return is_bool($value) || in_array(strtolower($value), ['true', 'false', '1', '0', 'yes', 'no']);
}

// =====================================================
// String Validation Functions
// =====================================================

/**
 * Validate string length
 */
function validateLength($value, $min = null, $max = null) {
    $length = strlen($value);
    if ($min !== null && $length < $min) return false;
    if ($max !== null && $length > $max) return false;
    return true;
}

/**
 * Validate alphanumeric
 */
function validateAlphaNumeric($value) {
    return ctype_alnum(str_replace(' ', '', $value));
}

/**
 * Validate alphabetic
 */
function validateAlpha($value) {
    return ctype_alpha(str_replace(' ', '', $value));
}

/**
 * Validate numeric
 */
function validateNumeric($value) {
    return is_numeric($value);
}

/**
 * Validate phone number (Sri Lankan format)
 */
function validatePhone($phone) {
    // Sri Lankan phone: 0771234567, 071-2345678, +94771234567
    $pattern = '/^(?:\+94|0)(?:[1-9][0-9])?[0-9]{7}$/';
    $phone = preg_replace('/[^0-9+]/', '', $phone);
    return preg_match($pattern, $phone);
}

/**
 * Validate Sri Lankan NIC
 */
function validateNic($nic) {
    $nic = strtoupper($nic);
    // Old format: 123456789V or 123456789X
    if (preg_match('/^[0-9]{9}[VX]$/', $nic)) {
        return true;
    }
    // New format: 199812345678
    if (preg_match('/^[0-9]{12}$/', $nic)) {
        return true;
    }
    return false;
}

// =====================================================
// Date Validation Functions
// =====================================================

/**
 * Validate date
 */
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Validate time
 */
function validateTime($time, $format = 'H:i:s') {
    $d = DateTime::createFromFormat($format, $time);
    return $d && $d->format($format) === $time;
}

/**
 * Validate datetime
 */
function validateDateTime($datetime, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $datetime);
    return $d && $d->format($format) === $datetime;
}

/**
 * Check if date is in future
 */
function isFutureDate($date) {
    return strtotime($date) > time();
}

/**
 * Check if date is in past
 */
function isPastDate($date) {
    return strtotime($date) < time();
}

// =====================================================
// File Validation Functions
// =====================================================

/**
 * Validate file type
 */
function validateFileType($file, $allowedTypes) {
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($extension, $allowedTypes);
}

/**
 * Validate file size
 */
function validateFileSize($file, $maxSize) {
    return $file['size'] <= $maxSize;
}

/**
 * Validate image dimensions
 */
function validateImageDimensions($file, $minWidth = null, $minHeight = null, $maxWidth = null, $maxHeight = null) {
    if (!file_exists($file['tmp_name'])) return false;
    
    $imageInfo = getimagesize($file['tmp_name']);
    if (!$imageInfo) return false;
    
    list($width, $height) = $imageInfo;
    
    if ($minWidth !== null && $width < $minWidth) return false;
    if ($minHeight !== null && $height < $minHeight) return false;
    if ($maxWidth !== null && $width > $maxWidth) return false;
    if ($maxHeight !== null && $height > $maxHeight) return false;
    
    return true;
}

// =====================================================
// Custom Validation Functions
// =====================================================

/**
 * Validate password strength
 */
function validatePassword($password) {
    if (strlen($password) < 6 || strlen($password) > 20) {
        return false;
    }
    
    // At least one uppercase, one lowercase, one number
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    
    return true;
}

/**
 * Validate username
 */
function validateUsername($username) {
    $len = strlen($username);
    if ($len < 3 || $len > 50) return false;
    return preg_match('/^[a-zA-Z0-9_]+$/', $username);
}

/**
 * Validate credit card number (Luhn algorithm)
 */
function validateCreditCard($number) {
    $number = preg_replace('/[^0-9]/', '', $number);
    $sum = 0;
    $alternate = false;
    
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $digit = (int)$number[$i];
        
        if ($alternate) {
            $digit *= 2;
            if ($digit > 9) {
                $digit = $digit - 9;
            }
        }
        
        $sum += $digit;
        $alternate = !$alternate;
    }
    
    return ($sum % 10 === 0);
}

// =====================================================
// Database Related Validation Functions
// =====================================================

/**
 * Check if email exists in database
 */
function emailExists($email, $table = 'users', $excludeId = null) {
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) as count FROM {$table} WHERE email = :email";
    $params = ['email' => $email];
    
    if ($excludeId) {
        $sql .= " AND id != :id";
        $params['id'] = $excludeId;
    }
    
    $result = $db->fetchOne($sql, $params);
    return $result['count'] > 0;
}

/**
 * Check if username exists in database
 */
function usernameExists($username, $table = 'users', $excludeId = null) {
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) as count FROM {$table} WHERE username = :username";
    $params = ['username' => $username];
    
    if ($excludeId) {
        $sql .= " AND id != :id";
        $params['id'] = $excludeId;
    }
    
    $result = $db->fetchOne($sql, $params);
    return $result['count'] > 0;
}

// =====================================================
// Form Validation
// =====================================================

/**
 * Validate form data against rules
 */
function validateForm($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? null;
        $rulesList = explode('|', $rule);
        
        foreach ($rulesList as $singleRule) {
            if (strpos($singleRule, ':') !== false) {
                list($ruleName, $parameter) = explode(':', $singleRule, 2);
            } else {
                $ruleName = $singleRule;
                $parameter = null;
            }
            
            switch ($ruleName) {
                case 'required':
                    if (empty($value) && $value !== '0') {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
                    }
                    break;
                    
                case 'email':
                    if (!empty($value) && !validateEmail($value)) {
                        $errors[$field][] = 'Invalid email format';
                    }
                    break;
                    
                case 'phone':
                    if (!empty($value) && !validatePhone($value)) {
                        $errors[$field][] = 'Invalid phone number';
                    }
                    break;
                    
                case 'nic':
                    if (!empty($value) && !validateNic($value)) {
                        $errors[$field][] = 'Invalid NIC number';
                    }
                    break;
                    
                case 'min':
                    if (strlen($value) < (int)$parameter) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$parameter} characters";
                    }
                    break;
                    
                case 'max':
                    if (strlen($value) > (int)$parameter) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must not exceed {$parameter} characters";
                    }
                    break;
                    
                case 'numeric':
                    if (!empty($value) && !is_numeric($value)) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must be a number';
                    }
                    break;
                    
                case 'integer':
                    if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must be an integer';
                    }
                    break;
                    
                case 'date':
                    if (!empty($value) && !strtotime($value)) {
                        $errors[$field][] = 'Invalid date format';
                    }
                    break;
                    
                case 'matches':
                    if ($value !== ($data[$parameter] ?? null)) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must match " . str_replace('_', ' ', $parameter);
                    }
                    break;
                    
                case 'unique':
                    if (!empty($value)) {
                        list($table, $column) = explode(',', $parameter);
                        if (emailExists($value, $table)) {
                            $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' already exists';
                        }
                    }
                    break;
            }
        }
    }
    
    return $errors;
}

/**
 * Sanitize input data
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}