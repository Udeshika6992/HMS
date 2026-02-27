-- =====================================================
-- HOSPITAL MANAGEMENT SYSTEM - COMPLETE DATABASE
-- Ready to run in phpMyAdmin
-- =====================================================

-- Drop database if exists and create fresh
DROP DATABASE IF EXISTS `hospital_management`;
CREATE DATABASE `hospital_management`;
USE `hospital_management`;

-- =====================================================
-- 1. USERS TABLE
-- =====================================================
CREATE TABLE `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(15),
    `address` TEXT,
    `profile_image` VARCHAR(255) DEFAULT 'default-avatar.png',
    `role` ENUM('admin', 'doctor', 'patient') NOT NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `last_login` DATETIME,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. DEPARTMENTS TABLE
-- =====================================================
CREATE TABLE `departments` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `department_name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `head_doctor_id` INT NULL,
    `floor_number` VARCHAR(10),
    `extension_number` VARCHAR(20),
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`head_doctor_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. DOCTORS TABLE
-- =====================================================
CREATE TABLE `doctors` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT UNIQUE NOT NULL,
    `department_id` INT,
    `specialization` VARCHAR(100),
    `qualification` TEXT,
    `experience_years` INT,
    `license_number` VARCHAR(50) UNIQUE,
    `consultation_fee` DECIMAL(10,2) DEFAULT 0.00,
    `available_days` VARCHAR(100),
    `available_time_start` TIME,
    `available_time_end` TIME,
    `max_patients_per_day` INT DEFAULT 20,
    `bio` TEXT,
    `is_available` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. PATIENTS TABLE
-- =====================================================
CREATE TABLE `patients` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT UNIQUE NOT NULL,
    `date_of_birth` DATE,
    `gender` ENUM('Male', 'Female', 'Other'),
    `blood_group` ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'),
    `emergency_contact_name` VARCHAR(100),
    `emergency_contact_phone` VARCHAR(15),
    `emergency_contact_relation` VARCHAR(50),
    `allergies` TEXT,
    `chronic_conditions` TEXT,
    `current_medications` TEXT,
    `registration_date` DATE,
    `registration_fee_paid` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. APPOINTMENTS TABLE
-- =====================================================
CREATE TABLE `appointments` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `appointment_number` VARCHAR(20) UNIQUE NOT NULL,
    `patient_id` INT NOT NULL,
    `doctor_id` INT NOT NULL,
    `appointment_date` DATE NOT NULL,
    `appointment_time` TIME NOT NULL,
    `end_time` TIME,
    `status` ENUM('pending', 'confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'pending',
    `symptoms` TEXT,
    `diagnosis` TEXT,
    `notes` TEXT,
    `follow_up_date` DATE,
    `created_by` INT,
    `cancelled_by` INT,
    `cancellation_reason` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`cancelled_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 6. MEDICAL_RECORDS TABLE
-- =====================================================
CREATE TABLE `medical_records` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `patient_id` INT NOT NULL,
    `doctor_id` INT NOT NULL,
    `appointment_id` INT UNIQUE,
    `record_date` DATE NOT NULL,
    `visit_type` ENUM('regular', 'follow_up', 'emergency', 'checkup') DEFAULT 'regular',
    `chief_complaint` TEXT,
    `symptoms` TEXT,
    `diagnosis` TEXT,
    `treatment_plan` TEXT,
    `doctor_notes` TEXT,
    `follow_up_required` BOOLEAN DEFAULT FALSE,
    `follow_up_date` DATE,
    `prescriptions` JSON,
    `lab_tests` JSON,
    `attachments` JSON,
    `is_confidential` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 7. PRESCRIPTIONS TABLE
-- =====================================================
CREATE TABLE `prescriptions` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `medical_record_id` INT NOT NULL,
    `patient_id` INT NOT NULL,
    `doctor_id` INT NOT NULL,
    `medicine_name` VARCHAR(200) NOT NULL,
    `dosage` VARCHAR(100) NOT NULL,
    `frequency` VARCHAR(100) NOT NULL,
    `duration` VARCHAR(100),
    `instructions` TEXT,
    `quantity` INT,
    `refills` INT DEFAULT 0,
    `start_date` DATE,
    `end_date` DATE,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 8. PATIENT_VITALS TABLE
-- =====================================================
CREATE TABLE `patient_vitals` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `patient_id` INT NOT NULL,
    `doctor_id` INT NOT NULL,
    `appointment_id` INT,
    `record_date` DATE NOT NULL,
    `blood_pressure_systolic` INT,
    `blood_pressure_diastolic` INT,
    `heart_rate` INT,
    `temperature` DECIMAL(4,2),
    `weight` DECIMAL(5,2),
    `height` DECIMAL(5,2),
    `bmi` DECIMAL(4,2) GENERATED ALWAYS AS (ROUND(`weight` / (`height` * `height`), 2)) STORED,
    `blood_sugar_fasting` INT,
    `blood_sugar_random` INT,
    `oxygen_saturation` INT,
    `respiratory_rate` INT,
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 9. PROGRESS_TRACKING TABLE
-- =====================================================
CREATE TABLE `progress_tracking` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `patient_id` INT NOT NULL,
    `doctor_id` INT NOT NULL,
    `tracking_date` DATE NOT NULL,
    `metric_name` VARCHAR(100) NOT NULL,
    `metric_value` VARCHAR(50) NOT NULL,
    `metric_unit` VARCHAR(20),
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 10. NOTIFICATIONS TABLE
-- =====================================================
CREATE TABLE `notifications` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `type` ENUM('appointment', 'reminder', 'system', 'alert') DEFAULT 'system',
    `is_read` BOOLEAN DEFAULT FALSE,
    `link` VARCHAR(255),
    `sent_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `read_at` DATETIME,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 11. ACTIVITY_LOGS TABLE
-- =====================================================
CREATE TABLE `activity_logs` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT,
    `action` VARCHAR(100) NOT NULL,
    `table_name` VARCHAR(50),
    `record_id` INT,
    `old_data` JSON,
    `new_data` JSON,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 12. HOLIDAYS TABLE
-- =====================================================
CREATE TABLE `holidays` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `doctor_id` INT,
    `holiday_date` DATE NOT NULL,
    `reason` VARCHAR(255),
    `is_annual` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 13. FEEDBACK TABLE
-- =====================================================
CREATE TABLE `feedback` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `patient_id` INT NOT NULL,
    `doctor_id` INT,
    `appointment_id` INT,
    `rating` INT CHECK (`rating` >= 1 AND `rating` <= 5),
    `feedback_text` TEXT,
    `is_anonymous` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- CREATE TRIGGERS
-- =====================================================

DELIMITER $$

-- Trigger to generate appointment number
CREATE TRIGGER `before_appointment_insert` 
BEFORE INSERT ON `appointments` 
FOR EACH ROW 
BEGIN
    DECLARE next_id INT;
    SET next_id = (SELECT AUTO_INCREMENT FROM information_schema.TABLES 
                   WHERE TABLE_SCHEMA = DATABASE() 
                   AND TABLE_NAME = 'appointments');
    SET NEW.`appointment_number` = CONCAT('APT-', DATE_FORMAT(NEW.`appointment_date`, '%Y%m%d'), '-', LPAD(next_id, 5, '0'));
END$$

-- Trigger to log user login
CREATE TRIGGER `after_user_login_update` 
AFTER UPDATE ON `users` 
FOR EACH ROW 
BEGIN
    IF NEW.`last_login` != OLD.`last_login` OR (OLD.`last_login` IS NULL AND NEW.`last_login` IS NOT NULL) THEN
        INSERT INTO `activity_logs` (`user_id`, `action`, `table_name`, `record_id`)
        VALUES (NEW.`id`, 'login', 'users', NEW.`id`);
    END IF;
END$$

DELIMITER ;

-- =====================================================
-- CREATE STORED PROCEDURES
-- =====================================================

DELIMITER $$

-- Procedure to book appointment
CREATE PROCEDURE `book_appointment`(
    IN p_patient_id INT,
    IN p_doctor_id INT,
    IN p_appointment_date DATE,
    IN p_appointment_time TIME,
    IN p_symptoms TEXT,
    IN p_created_by INT
)
BEGIN
    DECLARE doctor_max INT;
    DECLARE current_count INT;
    DECLARE appointment_id INT;
    DECLARE doctor_user_id INT;
    DECLARE patient_user_id INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Error: Appointment booking failed' as message;
    END;
    
    START TRANSACTION;
    
    SELECT `max_patients_per_day` INTO doctor_max FROM `doctors` WHERE `id` = p_doctor_id;
    
    SELECT COUNT(*) INTO current_count FROM `appointments` 
    WHERE `doctor_id` = p_doctor_id AND `appointment_date` = p_appointment_date 
    AND `status` NOT IN ('cancelled', 'no_show');
    
    IF current_count < doctor_max THEN
        INSERT INTO `appointments` (`patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `symptoms`, `created_by`)
        VALUES (p_patient_id, p_doctor_id, p_appointment_date, p_appointment_time, p_symptoms, p_created_by);
        
        SET appointment_id = LAST_INSERT_ID();
        
        SELECT `user_id` INTO doctor_user_id FROM `doctors` WHERE `id` = p_doctor_id;
        SELECT `user_id` INTO patient_user_id FROM `patients` WHERE `id` = p_patient_id;
        
        INSERT INTO `notifications` (`user_id`, `title`, `message`, `type`, `link`)
        VALUES (
            doctor_user_id, 
            'New Appointment Booked', 
            CONCAT('New appointment booked for ', DATE_FORMAT(p_appointment_date, '%Y-%m-%d'), ' at ', p_appointment_time),
            'appointment',
            CONCAT('/doctor/appointments/view/', appointment_id)
        );
        
        INSERT INTO `notifications` (`user_id`, `title`, `message`, `type`, `link`)
        VALUES (
            patient_user_id,
            'Appointment Confirmed',
            CONCAT('Your appointment on ', DATE_FORMAT(p_appointment_date, '%Y-%m-%d'), ' at ', p_appointment_time, ' is confirmed'),
            'appointment',
            '/patient/my-appointments'
        );
        
        COMMIT;
        
        SELECT appointment_id as appointment_id, 'Appointment booked successfully' as message;
    ELSE
        ROLLBACK;
        SELECT 'Doctor is fully booked for this day' as message;
    END IF;
END$$

-- Procedure to cancel appointment
CREATE PROCEDURE `cancel_appointment`(
    IN p_appointment_id INT,
    IN p_cancelled_by INT,
    IN p_reason TEXT
)
BEGIN
    DECLARE patient_user_id INT;
    DECLARE doctor_user_id INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Error: Cancellation failed' as message;
    END;
    
    START TRANSACTION;
    
    SELECT p.`user_id`, d.`user_id` 
    INTO patient_user_id, doctor_user_id
    FROM `appointments` a
    JOIN `patients` p ON a.`patient_id` = p.`id`
    JOIN `doctors` d ON a.`doctor_id` = d.`id`
    WHERE a.`id` = p_appointment_id;
    
    UPDATE `appointments` 
    SET `status` = 'cancelled', 
        `cancelled_by` = p_cancelled_by, 
        `cancellation_reason` = p_reason 
    WHERE `id` = p_appointment_id;
    
    INSERT INTO `notifications` (`user_id`, `title`, `message`, `type`, `link`)
    VALUES (
        patient_user_id,
        'Appointment Cancelled',
        CONCAT('Your appointment has been cancelled. Reason: ', p_reason),
        'alert',
        '/patient/my-appointments'
    );
    
    INSERT INTO `notifications` (`user_id`, `title`, `message`, `type`, `link`)
    VALUES (
        doctor_user_id,
        'Appointment Cancelled',
        CONCAT('An appointment has been cancelled. Reason: ', p_reason),
        'alert',
        '/doctor/appointments'
    );
    
    COMMIT;
    
    SELECT 'Appointment cancelled successfully' as message;
END$$

DELIMITER ;

-- =====================================================
-- CREATE VIEWS
-- =====================================================

-- View for Today's Appointments
CREATE VIEW `view_today_appointments` AS
SELECT 
    a.`appointment_number`,
    a.`appointment_date`,
    a.`appointment_time`,
    a.`status`,
    a.`symptoms`,
    p.`id` as patient_id,
    pu.`full_name` as patient_name,
    pu.`phone` as patient_phone,
    d.`id` as doctor_id,
    du.`full_name` as doctor_name,
    d.`specialization`,
    dep.`department_name`
FROM `appointments` a
JOIN `patients` p ON a.`patient_id` = p.`id`
JOIN `users` pu ON p.`user_id` = pu.`id`
JOIN `doctors` d ON a.`doctor_id` = d.`id`
JOIN `users` du ON d.`user_id` = du.`id`
LEFT JOIN `departments` dep ON d.`department_id` = dep.`id`
WHERE a.`appointment_date` = CURDATE();

-- View for Patient Progress Summary
CREATE VIEW `view_patient_progress` AS
SELECT 
    p.`id` as patient_id,
    u.`full_name`,
    u.`phone`,
    p.`blood_group`,
    COUNT(DISTINCT a.`id`) as total_visits,
    COUNT(DISTINCT CASE WHEN a.`status` = 'completed' THEN a.`id` END) as completed_visits,
    MAX(a.`appointment_date`) as last_visit_date,
    MAX(v.`blood_pressure_systolic`) as latest_bp_systolic,
    MAX(v.`blood_pressure_diastolic`) as latest_bp_diastolic,
    MAX(v.`weight`) as latest_weight,
    MAX(v.`record_date`) as last_vitals_date
FROM `patients` p
JOIN `users` u ON p.`user_id` = u.`id`
LEFT JOIN `appointments` a ON p.`id` = a.`patient_id`
LEFT JOIN `patient_vitals` v ON p.`id` = v.`patient_id`
GROUP BY p.`id`, u.`full_name`, u.`phone`, p.`blood_group`;

-- View for Doctor Workload
CREATE VIEW `view_doctor_workload` AS
SELECT 
    d.`id` as doctor_id,
    u.`full_name` as doctor_name,
    dep.`department_name`,
    d.`specialization`,
    COUNT(a.`id`) as total_appointments,
    COUNT(CASE WHEN a.`appointment_date` = CURDATE() THEN 1 END) as today_appointments,
    COUNT(CASE WHEN a.`status` = 'pending' THEN 1 END) as pending_appointments,
    COUNT(CASE WHEN a.`status` = 'completed' THEN 1 END) as completed_appointments,
    COUNT(CASE WHEN a.`appointment_date` > CURDATE() THEN 1 END) as upcoming_appointments
FROM `doctors` d
JOIN `users` u ON d.`user_id` = u.`id`
LEFT JOIN `departments` dep ON d.`department_id` = dep.`id`
LEFT JOIN `appointments` a ON d.`id` = a.`doctor_id`
GROUP BY d.`id`, u.`full_name`, dep.`department_name`, d.`specialization`;

-- =====================================================
-- CREATE INDEXES
-- =====================================================
CREATE INDEX `idx_appointments_doctor_status` ON `appointments`(`doctor_id`, `status`);
CREATE INDEX `idx_appointments_patient_status` ON `appointments`(`patient_id`, `status`);
CREATE INDEX `idx_appointments_date_status` ON `appointments`(`appointment_date`, `status`);
CREATE INDEX `idx_medical_records_patient` ON `medical_records`(`patient_id`);
CREATE INDEX `idx_prescriptions_patient` ON `prescriptions`(`patient_id`, `is_active`);
CREATE INDEX `idx_vitals_patient_date` ON `patient_vitals`(`patient_id`, `record_date`);
CREATE INDEX `idx_notifications_user_read` ON `notifications`(`user_id`, `is_read`);

-- =====================================================
-- CREATE FULLTEXT INDEXES FOR SEARCH
-- =====================================================
ALTER TABLE `users` ADD FULLTEXT INDEX `ft_users_search` (`full_name`, `email`, `username`);
ALTER TABLE `medical_records` ADD FULLTEXT INDEX `ft_medical_search` (`diagnosis`, `symptoms`, `doctor_notes`);

-- =====================================================
-- VERIFY TABLES CREATED
-- =====================================================
SELECT '✅ DATABASE CREATED SUCCESSFULLY!' as 'STATUS';
SELECT CONCAT('📊 Total Tables: ', COUNT(*)) as 'SUMMARY' FROM information_schema.tables WHERE table_schema = 'hospital_management';

-- Show all tables
SELECT table_name as '📋 TABLES CREATED' 
FROM information_schema.tables 
WHERE table_schema = 'hospital_management'
ORDER BY table_name;

-- =====================================================
-- END OF SCRIPT
-- =====================================================