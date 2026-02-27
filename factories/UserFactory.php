<?php
/**
 * User Factory
 * Creates different types of user objects
 * Location: /factories/UserFactory.php
 */

class UserFactory {
    
    /**
     * Create a user based on role
     * @param string $role User role (admin, doctor, patient)
     * @param array $data User data
     * @return object User object
     */
    public static function create($role, $data = []) {
        switch ($role) {
            case 'admin':
                return self::createAdmin($data);
            case 'doctor':
                return self::createDoctor($data);
            case 'patient':
                return self::createPatient($data);
            default:
                throw new Exception("Invalid user role: {$role}");
        }
    }
    
    /**
     * Create admin user
     */
    private static function createAdmin($data) {
        $user = new stdClass();
        $user->username = $data['username'] ?? '';
        $user->email = $data['email'] ?? '';
        $user->password = self::hashPassword($data['password'] ?? '');
        $user->full_name = $data['full_name'] ?? '';
        $user->phone = $data['phone'] ?? '';
        $user->address = $data['address'] ?? '';
        $user->role = 'admin';
        $user->is_active = $data['is_active'] ?? true;
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        
        return $user;
    }
    
    /**
     * Create doctor user
     */
    private static function createDoctor($data) {
        $user = new stdClass();
        $user->username = $data['username'] ?? '';
        $user->email = $data['email'] ?? '';
        $user->password = self::hashPassword($data['password'] ?? '');
        $user->full_name = $data['full_name'] ?? '';
        $user->phone = $data['phone'] ?? '';
        $user->address = $data['address'] ?? '';
        $user->role = 'doctor';
        $user->is_active = $data['is_active'] ?? true;
        
        // Doctor specific fields
        $user->specialization = $data['specialization'] ?? '';
        $user->qualification = $data['qualification'] ?? '';
        $user->experience_years = $data['experience_years'] ?? 0;
        $user->license_number = $data['license_number'] ?? '';
        $user->department_id = $data['department_id'] ?? null;
        $user->consultation_fee = $data['consultation_fee'] ?? 0.00;
        $user->available_days = $data['available_days'] ?? 'Mon,Tue,Wed,Thu,Fri';
        $user->available_time_start = $data['available_time_start'] ?? '09:00:00';
        $user->available_time_end = $data['available_time_end'] ?? '17:00:00';
        $user->max_patients_per_day = $data['max_patients_per_day'] ?? 20;
        $user->bio = $data['bio'] ?? '';
        $user->is_available = $data['is_available'] ?? true;
        
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        
        return $user;
    }
    
    /**
     * Create patient user
     */
    private static function createPatient($data) {
        $user = new stdClass();
        $user->username = $data['username'] ?? '';
        $user->email = $data['email'] ?? '';
        $user->password = self::hashPassword($data['password'] ?? '');
        $user->full_name = $data['full_name'] ?? '';
        $user->phone = $data['phone'] ?? '';
        $user->address = $data['address'] ?? '';
        $user->role = 'patient';
        $user->is_active = $data['is_active'] ?? true;
        
        // Patient specific fields
        $user->date_of_birth = $data['date_of_birth'] ?? null;
        $user->gender = $data['gender'] ?? '';
        $user->blood_group = $data['blood_group'] ?? '';
        $user->emergency_contact_name = $data['emergency_contact_name'] ?? '';
        $user->emergency_contact_phone = $data['emergency_contact_phone'] ?? '';
        $user->emergency_contact_relation = $data['emergency_contact_relation'] ?? '';
        $user->allergies = $data['allergies'] ?? '';
        $user->chronic_conditions = $data['chronic_conditions'] ?? '';
        $user->current_medications = $data['current_medications'] ?? '';
        $user->registration_date = $data['registration_date'] ?? date('Y-m-d');
        $user->registration_fee_paid = $data['registration_fee_paid'] ?? false;
        
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        
        return $user;
    }
    
    /**
     * Create user from array (for database results)
     */
    public static function createFromArray($data) {
        if (!isset($data['role'])) {
            throw new Exception("User role not specified");
        }
        
        return self::create($data['role'], $data);
    }
    
    /**
     * Hash password
     */
    private static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Validate user data
     */
    public static function validate($data) {
        $errors = [];
        
        // Required fields
        $required = ['username', 'email', 'password', 'full_name', 'role'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }
        
        // Email validation
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        
        // Password validation
        if (!empty($data['password']) && strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }
        
        // Username validation
        if (!empty($data['username']) && !preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
            $errors['username'] = 'Username can only contain letters, numbers, and underscore';
        }
        
        // Role validation
        $validRoles = ['admin', 'doctor', 'patient'];
        if (!empty($data['role']) && !in_array($data['role'], $validRoles)) {
            $errors['role'] = 'Invalid role';
        }
        
        return $errors;
    }
    
    /**
     * Create user data array for database
     */
    public static function toArray($user) {
        return (array) $user;
    }
}