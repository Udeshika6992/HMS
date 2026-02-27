<?php
/**
 * Constants File
 * Location: /config/constants.php
 */

// =====================================================
// User Roles
// =====================================================
define('ROLE_ADMIN', 'admin');
define('ROLE_DOCTOR', 'doctor');
define('ROLE_PATIENT', 'patient');

// =====================================================
// Appointment Status
// =====================================================
define('APPOINTMENT_PENDING', 'pending');
define('APPOINTMENT_CONFIRMED', 'confirmed');
define('APPOINTMENT_COMPLETED', 'completed');
define('APPOINTMENT_CANCELLED', 'cancelled');
define('APPOINTMENT_NO_SHOW', 'no_show');

// =====================================================
// Blood Groups
// =====================================================
define('BLOOD_GROUPS', [
    'A+', 'A-', 'B+', 'B-', 
    'AB+', 'AB-', 'O+', 'O-'
]);

// =====================================================
// Genders
// =====================================================
define('GENDERS', ['Male', 'Female', 'Other']);

// =====================================================
// Visit Types
// =====================================================
define('VISIT_TYPES', [
    'regular' => 'Regular Visit',
    'follow_up' => 'Follow-up Visit',
    'emergency' => 'Emergency Visit',
    'checkup' => 'Regular Checkup'
]);

// =====================================================
// Notification Types
// =====================================================
define('NOTIFICATION_TYPES', [
    'appointment' => 'Appointment Notification',
    'reminder' => 'Reminder',
    'system' => 'System Notification',
    'alert' => 'Alert'
]);

// =====================================================
// Days of Week
// =====================================================
define('DAYS_OF_WEEK', [
    'Mon' => 'Monday',
    'Tue' => 'Tuesday',
    'Wed' => 'Wednesday',
    'Thu' => 'Thursday',
    'Fri' => 'Friday',
    'Sat' => 'Saturday',
    'Sun' => 'Sunday'
]);

// =====================================================
// Default Values
// =====================================================
define('DEFAULT_PROFILE_IMAGE', 'default-avatar.png');
define('DEFAULT_PASSWORD', 'Welcome@123');

// =====================================================
// Validation Rules
// =====================================================
define('PASSWORD_MIN_LENGTH', 6);
define('PASSWORD_MAX_LENGTH', 20);
define('USERNAME_MIN_LENGTH', 3);
define('USERNAME_MAX_LENGTH', 50);
define('PHONE_LENGTH', 10);

// =====================================================
// Messages
// =====================================================
define('MSG_LOGIN_SUCCESS', 'Login successful!');
define('MSG_LOGOUT_SUCCESS', 'Logout successful!');
define('MSG_REGISTER_SUCCESS', 'Registration successful! Please login.');
define('MSG_APPOINTMENT_BOOKED', 'Appointment booked successfully!');
define('MSG_APPOINTMENT_CANCELLED', 'Appointment cancelled successfully!');
define('MSG_UPDATE_SUCCESS', 'Record updated successfully!');
define('MSG_DELETE_SUCCESS', 'Record deleted successfully!');
define('MSG_ADD_SUCCESS', 'Record added successfully!');

define('ERR_LOGIN_FAILED', 'Invalid username or password!');
define('ERR_ACCESS_DENIED', 'Access denied!');
define('ERR_SESSION_EXPIRED', 'Session expired! Please login again.');
define('ERR_INVALID_REQUEST', 'Invalid request!');
define('ERR_NOT_FOUND', 'Record not found!');
define('ERR_DUPLICATE', 'Record already exists!');
define('ERR_VALIDATION', 'Please check your input!');

// =====================================================
// HTTP Status Codes
// =====================================================
define('HTTP_OK', 200);
define('HTTP_CREATED', 201);
define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED', 401);
define('HTTP_FORBIDDEN', 403);
define('HTTP_NOT_FOUND', 404);
define('HTTP_SERVER_ERROR', 500);