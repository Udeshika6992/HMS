-- =====================================================
-- HOSPITAL MANAGEMENT SYSTEM - COMPLETE SEED DATA
-- =====================================================
-- This file contains realistic sample data for testing
-- Run after migration.sql
-- =====================================================

USE hospital_management;

-- =====================================================
-- 1. GENERATE PASSWORD HASHES (password = 'password123')
-- =====================================================
-- Note: In real implementation, generate these with PHP's password_hash()
-- For now, we'll use a fixed hash that works with password_verify('password123', $hash)

SET @password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; -- 'password123'

-- =====================================================
-- 2. USERS TABLE - EXTENSIVE SAMPLE DATA
-- =====================================================
INSERT INTO users (username, email, password_hash, full_name, phone, address, role, is_active, created_at) VALUES
-- Admins (5 admins)
('admin_kumari', 'kumari.wijesinghe@hospital.gov.lk', @password_hash, 'Kumari Wijesinghe', '077-1234567', '23, Main Street, Kandy', 'admin', TRUE, NOW() - INTERVAL 180 DAY),
('admin_priyantha', 'priyantha.perera@hospital.gov.lk', @password_hash, 'Priyantha Perera', '077-2345678', '45, Temple Road, Delthota', 'admin', TRUE, NOW() - INTERVAL 175 DAY),
('admin_nadeeka', 'nadeeka.silva@hospital.gov.lk', @password_hash, 'Nadeeka Silva', '071-3456789', '78, Lake View, Gampola', 'admin', TRUE, NOW() - INTERVAL 170 DAY),
('admin_saman', 'saman.ratnayake@hospital.gov.lk', @password_hash, 'Saman Ratnayake', '072-4567890', '12, Station Road, Nawalapitiya', 'admin', TRUE, NOW() - INTERVAL 165 DAY),
('admin_chamari', 'chamari.fernando@hospital.gov.lk', @password_hash, 'Chamari Fernando', '075-5678901', '34, Hill Street, Hatton', 'admin', TRUE, NOW() - INTERVAL 160 DAY),

-- Doctors (15 doctors)
('dr_dayan', 'dr.dayan.perera@hospital.gov.lk', @password_hash, 'Dr. Dayan Perera', '077-6789012', '56, Flower Road, Kandy', 'doctor', TRUE, NOW() - INTERVAL 365 DAY),
('dr_nelum', 'dr.nelum.senarath@hospital.gov.lk', @password_hash, 'Dr. Nelum Senarath', '071-7890123', '89, Lake Drive, Kandy', 'doctor', TRUE, NOW() - INTERVAL 350 DAY),
('dr_ruwan', 'dr.ruwan.jayasinghe@hospital.gov.lk', @password_hash, 'Dr. Ruwan Jayasinghe', '072-8901234', '23, Hill Top, Delthota', 'doctor', TRUE, NOW() - INTERVAL 340 DAY),
('dr_malini', 'dr.malini.gunasekara@hospital.gov.lk', @password_hash, 'Dr. Malini Gunasekara', '075-9012345', '67, Temple Street, Gampola', 'doctor', TRUE, NOW() - INTERVAL 330 DAY),
('dr_kamal', 'dr.kamal.wickrama@hospital.gov.lk', @password_hash, 'Dr. Kamal Wickramasinghe', '077-0123456', '34, River Side, Nawalapitiya', 'doctor', TRUE, NOW() - INTERVAL 320 DAY),
('dr_sunethra', 'dr.sunethra.weerasinghe@hospital.gov.lk', @password_hash, 'Dr. Sunethra Weerasinghe', '071-1234500', '78, Lake Road, Hatton', 'doctor', TRUE, NOW() - INTERVAL 310 DAY),
('dr_nuwan', 'dr.nuwan.silva@hospital.gov.lk', @password_hash, 'Dr. Nuwan Silva', '072-2345600', '45, Main Street, Kandy', 'doctor', TRUE, NOW() - INTERVAL 300 DAY),
('dr_chaminda', 'dr.chaminda.fernando@hospital.gov.lk', @password_hash, 'Dr. Chaminda Fernando', '075-3456700', '12, Temple Road, Delthota', 'doctor', TRUE, NOW() - INTERVAL 290 DAY),
('dr_shyamali', 'dr.shyamali.perera@hospital.gov.lk', @password_hash, 'Dr. Shyamali Perera', '077-4567800', '89, Station Road, Gampola', 'doctor', TRUE, NOW() - INTERVAL 280 DAY),
('dr_nalin', 'dr.nalin.jayawardena@hospital.gov.lk', @password_hash, 'Dr. Nalin Jayawardena', '071-5678900', '23, Hill Street, Nawalapitiya', 'doctor', TRUE, NOW() - INTERVAL 270 DAY),
('dr_kusum', 'dr.kusum.ratnayake@hospital.gov.lk', @password_hash, 'Dr. Kusum Ratnayake', '072-6789011', '56, Lake View, Hatton', 'doctor', TRUE, NOW() - INTERVAL 260 DAY),
('dr_anura', 'dr.anura.senarath@hospital.gov.lk', @password_hash, 'Dr. Anura Senarath', '075-7890122', '34, Flower Road, Kandy', 'doctor', TRUE, NOW() - INTERVAL 250 DAY),
('dr_dilhani', 'dr.dilhani.wickrama@hospital.gov.lk', @password_hash, 'Dr. Dilhani Wickramasinghe', '077-8901233', '78, Lake Drive, Delthota', 'doctor', TRUE, NOW() - INTERVAL 240 DAY),
('dr_lalith', 'dr.lalith.gunasekara@hospital.gov.lk', @password_hash, 'Dr. Lalith Gunasekara', '071-9012344', '45, Hill Top, Gampola', 'doctor', TRUE, NOW() - INTERVAL 230 DAY),
('dr_champa', 'dr.champa.weerasinghe@hospital.gov.lk', @password_hash, 'Dr. Champa Weerasinghe', '072-0123455', '12, River Side, Nawalapitiya', 'doctor', TRUE, NOW() - INTERVAL 220 DAY),

-- Patients (50 patients)
('patient_nimal', 'nimal.silva@gmail.com', @password_hash, 'Nimal Silva', '077-1234501', '23, Main Street, Delthota', 'patient', TRUE, NOW() - INTERVAL 200 DAY),
('patient_sunima', 'sunima.perera@gmail.com', @password_hash, 'Sunima Perera', '071-2345602', '45, Temple Road, Delthota', 'patient', TRUE, NOW() - INTERVAL 195 DAY),
('patient_kamala', 'kamala.ratnayake@gmail.com', @password_hash, 'Kamala Ratnayake', '072-3456703', '78, Lake View, Delthota', 'patient', TRUE, NOW() - INTERVAL 190 DAY),
('patient_priya', 'priya.fernando@gmail.com', @password_hash, 'Priya Fernando', '075-4567804', '12, Station Road, Delthota', 'patient', TRUE, NOW() - INTERVAL 185 DAY),
('patient_upul', 'upul.jayasinghe@gmail.com', @password_hash, 'Upul Jayasinghe', '077-5678905', '34, Hill Street, Delthota', 'patient', TRUE, NOW() - INTERVAL 180 DAY),
('patient_champa', 'champa.senarath@gmail.com', @password_hash, 'Champa Senarath', '071-6789016', '56, Flower Road, Delthota', 'patient', TRUE, NOW() - INTERVAL 175 DAY),
('patient_asanka', 'asanka.gunasekara@gmail.com', @password_hash, 'Asanka Gunasekara', '072-7890127', '89, Lake Drive, Delthota', 'patient', TRUE, NOW() - INTERVAL 170 DAY),
('patient_malini', 'malini.wickrama@gmail.com', @password_hash, 'Malini Wickramasinghe', '075-8901238', '23, Hill Top, Delthota', 'patient', TRUE, NOW() - INTERVAL 165 DAY),
('patient_saman', 'saman.weerasinghe@gmail.com', @password_hash, 'Saman Weerasinghe', '077-9012349', '67, Temple Street, Delthota', 'patient', TRUE, NOW() - INTERVAL 160 DAY),
('patient_kusum', 'kusum.silva@gmail.com', @password_hash, 'Kusum Silva', '071-0123450', '34, River Side, Delthota', 'patient', TRUE, NOW() - INTERVAL 155 DAY),
('patient_nadeeka', 'nadeeka.perera@gmail.com', @password_hash, 'Nadeeka Perera', '072-1234561', '78, Lake Road, Delthota', 'patient', TRUE, NOW() - INTERVAL 150 DAY),
('patient_chandana', 'chandana.ratnayake@gmail.com', @password_hash, 'Chandana Ratnayake', '075-2345672', '45, Main Street, Delthota', 'patient', TRUE, NOW() - INTERVAL 145 DAY),
('patient_dilhan', 'dilhan.fernando@gmail.com', @password_hash, 'Dilhan Fernando', '077-3456783', '12, Temple Road, Delthota', 'patient', TRUE, NOW() - INTERVAL 140 DAY),
('patient_erangi', 'erangi.jayasinghe@gmail.com', @password_hash, 'Erangi Jayasinghe', '071-4567894', '89, Station Road, Delthota', 'patient', TRUE, NOW() - INTERVAL 135 DAY),
('patient_gayan', 'gayan.senarath@gmail.com', @password_hash, 'Gayan Senarath', '072-5678905', '23, Hill Street, Delthota', 'patient', TRUE, NOW() - INTERVAL 130 DAY),
('patient_harsha', 'harsha.gunasekara@gmail.com', @password_hash, 'Harsha Gunasekara', '075-6789016', '56, Flower Road, Delthota', 'patient', TRUE, NOW() - INTERVAL 125 DAY),
('patient_indika', 'indika.wickrama@gmail.com', @password_hash, 'Indika Wickramasinghe', '077-7890127', '34, Lake Drive, Delthota', 'patient', TRUE, NOW() - INTERVAL 120 DAY),
('patient_jagath', 'jagath.weerasinghe@gmail.com', @password_hash, 'Jagath Weerasinghe', '071-8901238', '78, Hill Top, Delthota', 'patient', TRUE, NOW() - INTERVAL 115 DAY),
('patient_kapila', 'kapila.silva@gmail.com', @password_hash, 'Kapila Silva', '072-9012349', '45, Temple Street, Delthota', 'patient', TRUE, NOW() - INTERVAL 110 DAY),
('patient_lakshmi', 'lakshmi.perera@gmail.com', @password_hash, 'Lakshmi Perera', '075-0123450', '12, River Side, Delthota', 'patient', TRUE, NOW() - INTERVAL 105 DAY),
('patient_madhava', 'madhava.ratnayake@gmail.com', @password_hash, 'Madhava Ratnayake', '077-1234562', '89, Lake Road, Delthota', 'patient', TRUE, NOW() - INTERVAL 100 DAY),
('patient_nayana', 'nayana.fernando@gmail.com', @password_hash, 'Nayana Fernando', '071-2345673', '23, Main Street, Gampola', 'patient', TRUE, NOW() - INTERVAL 95 DAY),
('patient_oshala', 'oshala.jayasinghe@gmail.com', @password_hash, 'Oshala Jayasinghe', '072-3456784', '45, Temple Road, Gampola', 'patient', TRUE, NOW() - INTERVAL 90 DAY),
('patient_prasanna', 'prasanna.senarath@gmail.com', @password_hash, 'Prasanna Senarath', '075-4567895', '78, Lake View, Gampola', 'patient', TRUE, NOW() - INTERVAL 85 DAY),
('patient_reshma', 'reshma.gunasekara@gmail.com', @password_hash, 'Reshma Gunasekara', '077-5678906', '12, Station Road, Gampola', 'patient', TRUE, NOW() - INTERVAL 80 DAY),
('patient_sagara', 'sagara.wickrama@gmail.com', @password_hash, 'Sagara Wickramasinghe', '071-6789017', '34, Hill Street, Gampola', 'patient', TRUE, NOW() - INTERVAL 75 DAY),
('patient_thilini', 'thilini.weerasinghe@gmail.com', @password_hash, 'Thilini Weerasinghe', '072-7890128', '56, Flower Road, Gampola', 'patient', TRUE, NOW() - INTERVAL 70 DAY),
('patient_upali', 'upali.silva@gmail.com', @password_hash, 'Upali Silva', '075-8901239', '89, Lake Drive, Kandy', 'patient', TRUE, NOW() - INTERVAL 65 DAY),
('patient_vajira', 'vajira.perera@gmail.com', @password_hash, 'Vajira Perera', '077-9012340', '23, Hill Top, Kandy', 'patient', TRUE, NOW() - INTERVAL 60 DAY),
('patient_wasantha', 'wasantha.ratnayake@gmail.com', @password_hash, 'Wasantha Ratnayake', '071-0123451', '67, Temple Street, Kandy', 'patient', TRUE, NOW() - INTERVAL 55 DAY),
('patient_xiara', 'xiara.fernando@gmail.com', @password_hash, 'Xiara Fernando', '072-1234562', '34, River Side, Kandy', 'patient', TRUE, NOW() - INTERVAL 50 DAY),
('patient_yalini', 'yalini.jayasinghe@gmail.com', @password_hash, 'Yalini Jayasinghe', '075-2345673', '78, Lake Road, Kandy', 'patient', TRUE, NOW() - INTERVAL 45 DAY),
('patient_zen', 'zen.senarath@gmail.com', @password_hash, 'Zen Senarath', '077-3456784', '45, Main Street, Nawalapitiya', 'patient', TRUE, NOW() - INTERVAL 40 DAY),
('patient_amara', 'amara.gunasekara@gmail.com', @password_hash, 'Amara Gunasekara', '071-4567895', '12, Temple Road, Nawalapitiya', 'patient', TRUE, NOW() - INTERVAL 35 DAY),
('patient_bandula', 'bandula.wickrama@gmail.com', @password_hash, 'Bandula Wickramasinghe', '072-5678906', '89, Station Road, Nawalapitiya', 'patient', TRUE, NOW() - INTERVAL 30 DAY),
('patient_chatura', 'chatura.weerasinghe@gmail.com', @password_hash, 'Chatura Weerasinghe', '075-6789017', '23, Hill Street, Nawalapitiya', 'patient', TRUE, NOW() - INTERVAL 25 DAY),
('patient_dammika', 'dammika.silva@gmail.com', @password_hash, 'Dammika Silva', '077-7890128', '56, Flower Road, Nawalapitiya', 'patient', TRUE, NOW() - INTERVAL 20 DAY),
('patient_eranda', 'eranda.perera@gmail.com', @password_hash, 'Eranda Perera', '071-8901239', '34, Lake Drive, Hatton', 'patient', TRUE, NOW() - INTERVAL 15 DAY),
('patient_farook', 'farook.ratnayake@gmail.com', @password_hash, 'Farook Ratnayake', '072-9012340', '78, Hill Top, Hatton', 'patient', TRUE, NOW() - INTERVAL 10 DAY),
('patient_gamini', 'gamini.fernando@gmail.com', @password_hash, 'Gamini Fernando', '075-0123451', '45, Temple Street, Hatton', 'patient', TRUE, NOW() - INTERVAL 5 DAY),
('patient_hemali', 'hemali.jayasinghe@gmail.com', @password_hash, 'Hemali Jayasinghe', '077-1234570', '12, River Side, Hatton', 'patient', TRUE, NOW()),
('patient_indrajith', 'indrajith.senarath@gmail.com', @password_hash, 'Indrajith Senarath', '071-2345681', '89, Lake Road, Hatton', 'patient', TRUE, NOW() - INTERVAL 2 DAY),
('patient_janaka', 'janaka.gunasekara@gmail.com', @password_hash, 'Janaka Gunasekara', '072-3456792', '23, Main Street, Kandy', 'patient', TRUE, NOW() - INTERVAL 3 DAY),
('patient_kavindi', 'kavindi.wickrama@gmail.com', @password_hash, 'Kavindi Wickramasinghe', '075-4567803', '45, Temple Road, Kandy', 'patient', TRUE, NOW() - INTERVAL 4 DAY),
('patient_lahiru', 'lahiru.weerasinghe@gmail.com', @password_hash, 'Lahiru Weerasinghe', '077-5678914', '78, Lake View, Kandy', 'patient', TRUE, NOW() - INTERVAL 6 DAY),
('patient_mangala', 'mangala.silva@gmail.com', @password_hash, 'Mangala Silva', '071-6789025', '12, Station Road, Kandy', 'patient', TRUE, NOW() - INTERVAL 7 DAY),
('patient_nirosha', 'nirosha.perera@gmail.com', @password_hash, 'Nirosha Perera', '072-7890136', '34, Hill Street, Kandy', 'patient', TRUE, NOW() - INTERVAL 8 DAY),
('patient_priyanthi', 'priyanthi.ratnayake@gmail.com', @password_hash, 'Priyanthi Ratnayake', '075-8901247', '56, Flower Road, Kandy', 'patient', TRUE, NOW() - INTERVAL 9 DAY);

-- =====================================================
-- 3. DEPARTMENTS TABLE
-- =====================================================
INSERT INTO departments (department_name, description, floor_number, extension_number, is_active) VALUES
('General Medicine', 'Primary care, internal medicine, and general consultations for all age groups', '1', '101', TRUE),
('Pediatrics', 'Specialized healthcare for infants, children, and adolescents', '1', '102', TRUE),
('Cardiology', 'Diagnosis and treatment of heart diseases and cardiovascular conditions', '2', '201', TRUE),
('Orthopedics', 'Treatment of musculoskeletal system including bones, joints, and muscles', '2', '202', TRUE),
('Dermatology', 'Skin, hair, and nail conditions including allergies and infections', '3', '301', TRUE),
('Obstetrics & Gynecology', 'Women\'s health, pregnancy care, and reproductive health', '3', '302', TRUE),
('ENT', 'Ear, Nose, and Throat specialist care', '4', '401', TRUE),
('Ophthalmology', 'Eye examinations, vision care, and eye disease treatment', '4', '402', TRUE),
('Psychiatry', 'Mental health assessment and counseling services', '5', '501', TRUE),
('Dentistry', 'Dental checkups, treatments, and oral hygiene', '5', '502', TRUE),
('Radiology', 'X-rays, ultrasounds, and diagnostic imaging services', '6', '601', TRUE),
('Laboratory', 'Blood tests, urine tests, and pathological investigations', '6', '602', TRUE),
('Physiotherapy', 'Physical therapy and rehabilitation services', '7', '701', TRUE),
('Pharmacy', 'Dispensing medications and pharmaceutical consultations', '7', '702', TRUE),
('Emergency', '24/7 emergency medical services and trauma care', 'Ground', '001', TRUE);

-- =====================================================
-- 4. DOCTORS TABLE - EXTENDED INFORMATION
-- =====================================================
-- Get doctor user_ids (from users table where role='doctor')
-- We'll use the IDs from the users insertion order (Admins 1-5, Doctors 6-20, Patients 21-70)
-- So doctors are IDs 6-20

-- Department mapping: Gen Med=1, Peds=2, Cardio=3, Ortho=4, Derma=5, OBGYN=6, ENT=7, Ophth=8

INSERT INTO doctors (user_id, department_id, specialization, qualification, experience_years, license_number, consultation_fee, available_days, available_time_start, available_time_end, max_patients_per_day, bio, is_available) VALUES
(6, 1, 'General Physician', 'MBBS, MD (Internal Medicine)', 12, 'SLMC/001/2020', 0.00, 'Mon,Tue,Wed,Thu,Fri', '08:00:00', '16:00:00', 30, 'Dr. Dayan Perera is a senior physician with 12 years experience in general medicine. Special interest in diabetes management and hypertension.', TRUE),
(7, 2, 'Pediatrician', 'MBBS, DCH, MD (Pediatrics)', 10, 'SLMC/002/2018', 0.00, 'Mon,Tue,Wed,Thu,Fri,Sat', '08:30:00', '14:30:00', 25, 'Dr. Nelum Senarath is a compassionate pediatrician who loves working with children. Specializes in childhood asthma and developmental issues.', TRUE),
(8, 3, 'Cardiologist', 'MBBS, MD, DM (Cardiology)', 15, 'SLMC/003/2015', 0.00, 'Mon,Wed,Fri', '09:00:00', '15:00:00', 20, 'Dr. Ruwan Jayasinghe is a renowned cardiologist specializing in preventive cardiology and heart failure management.', TRUE),
(9, 4, 'Orthopedic Surgeon', 'MBBS, MS (Ortho)', 11, 'SLMC/004/2017', 0.00, 'Tue,Thu,Sat', '09:00:00', '15:00:00', 22, 'Dr. Malini Gunasekara specializes in sports injuries, joint replacements, and fracture management.', TRUE),
(10, 5, 'Dermatologist', 'MBBS, DDV', 9, 'SLMC/005/2019', 0.00, 'Mon,Tue,Wed,Thu,Fri', '08:00:00', '14:00:00', 28, 'Dr. Kamal Wickramasinghe treats all skin conditions including eczema, psoriasis, and skin allergies.', TRUE),
(11, 6, 'Gynecologist', 'MBBS, DGO, MD (OBGYN)', 14, 'SLMC/006/2016', 0.00, 'Mon,Tue,Wed,Thu,Fri', '09:00:00', '15:00:00', 24, 'Dr. Sunethra Weerasinghe provides comprehensive women\'s healthcare including prenatal care and family planning.', TRUE),
(12, 7, 'ENT Specialist', 'MBBS, MS (ENT)', 8, 'SLMC/007/2020', 0.00, 'Mon,Wed,Fri,Sat', '08:30:00', '14:30:00', 26, 'Dr. Nuwan Silva treats ear infections, sinusitis, and throat disorders with both medical and surgical approaches.', TRUE),
(13, 8, 'Ophthalmologist', 'MBBS, MS (Ophthalmology)', 13, 'SLMC/008/2015', 0.00, 'Tue,Thu,Sat', '09:00:00', '15:00:00', 23, 'Dr. Chaminda Fernando specializes in cataract surgery and diabetic eye care.', TRUE),
(14, 1, 'General Physician', 'MBBS', 6, 'SLMC/009/2021', 0.00, 'Mon,Tue,Wed,Thu,Fri', '08:00:00', '16:00:00', 30, 'Dr. Shyamali Perera is a dedicated general physician with special interest in geriatric care.', TRUE),
(15, 3, 'Cardiologist', 'MBBS, MD, DM (Cardiology)', 16, 'SLMC/010/2014', 0.00, 'Mon,Wed,Fri', '10:00:00', '16:00:00', 18, 'Dr. Nalin Jayawardena specializes in interventional cardiology and heart rhythm disorders.', TRUE),
(16, 2, 'Pediatrician', 'MBBS, DCH', 7, 'SLMC/011/2020', 0.00, 'Mon,Tue,Wed,Thu,Fri,Sat', '08:00:00', '13:00:00', 25, 'Dr. Kusum Ratnayake has special interest in adolescent medicine and childhood nutrition.', TRUE),
(17, 4, 'Orthopedic Surgeon', 'MBBS, D.Ortho', 9, 'SLMC/012/2019', 0.00, 'Mon,Wed,Fri', '08:30:00', '14:30:00', 22, 'Dr. Anura Senarath specializes in pediatric orthopedics and spine conditions.', TRUE),
(18, 5, 'Dermatologist', 'MBBS, DDV', 8, 'SLMC/013/2020', 0.00, 'Tue,Thu,Sat', '09:00:00', '15:00:00', 26, 'Dr. Dilhani Wickramasinghe treats acne, skin allergies, and performs minor skin surgeries.', TRUE),
(19, 6, 'Gynecologist', 'MBBS, DGO', 10, 'SLMC/014/2018', 0.00, 'Mon,Tue,Wed,Thu,Fri', '08:00:00', '14:00:00', 24, 'Dr. Lalith Gunasekara specializes in infertility treatment and high-risk pregnancy care.', TRUE),
(20, 7, 'ENT Specialist', 'MBBS, MS (ENT)', 12, 'SLMC/015/2017', 0.00, 'Mon,Wed,Fri', '09:00:00', '15:00:00', 25, 'Dr. Champa Weerasinghe treats hearing loss, balance disorders, and performs sinus surgeries.', TRUE);

-- Update department heads
UPDATE departments SET head_doctor_id = 6 WHERE id = 1;  -- Dr. Dayan Perera
UPDATE departments SET head_doctor_id = 7 WHERE id = 2;  -- Dr. Nelum Senarath
UPDATE departments SET head_doctor_id = 8 WHERE id = 3;  -- Dr. Ruwan Jayasinghe
UPDATE departments SET head_doctor_id = 9 WHERE id = 4;  -- Dr. Malini Gunasekara
UPDATE departments SET head_doctor_id = 10 WHERE id = 5; -- Dr. Kamal Wickramasinghe
UPDATE departments SET head_doctor_id = 11 WHERE id = 6; -- Dr. Sunethra Weerasinghe
UPDATE departments SET head_doctor_id = 12 WHERE id = 7; -- Dr. Nuwan Silva
UPDATE departments SET head_doctor_id = 13 WHERE id = 8; -- Dr. Chaminda Fernando

-- =====================================================
-- 5. PATIENTS TABLE - EXTENDED INFORMATION
-- =====================================================
-- Patient user_ids start from 21 to 70
-- We'll add detailed medical information for first 30 patients (IDs 21-50)

INSERT INTO patients (user_id, date_of_birth, gender, blood_group, emergency_contact_name, emergency_contact_phone, emergency_contact_relation, allergies, chronic_conditions, current_medications, registration_date, registration_fee_paid) VALUES
(21, '1985-03-15', 'Male', 'O+', 'Sunitha Silva', '077-1234501', 'Wife', 'Penicillin', 'None', 'None', '2024-01-15', TRUE),
(22, '1978-07-22', 'Female', 'B+', 'Kamal Perera', '071-2345602', 'Husband', 'Dust, Pollen', 'Asthma', 'Ventolin inhaler as needed', '2024-01-20', TRUE),
(23, '1992-11-05', 'Female', 'A+', 'Nuwan Ratnayake', '072-3456703', 'Brother', 'None', 'None', 'None', '2024-01-22', TRUE),
(24, '1965-09-12', 'Male', 'AB+', 'Priya Fernando', '075-4567804', 'Daughter', 'Sulfa', 'Hypertension, Diabetes Type 2', 'Metformin 500mg, Amlodipine 5mg', '2024-01-25', TRUE),
(25, '1988-04-18', 'Male', 'O-', 'Malini Jayasinghe', '077-5678905', 'Wife', 'None', 'None', 'None', '2024-01-28', TRUE),
(26, '1972-12-30', 'Female', 'A-', 'Upul Senarath', '071-6789016', 'Husband', 'Seafood', 'Arthritis', 'Ibuprofen as needed', '2024-02-01', TRUE),
(27, '1995-08-25', 'Male', 'B-', 'Champa Gunasekara', '072-7890127', 'Mother', 'None', 'None', 'None', '2024-02-05', TRUE),
(28, '1982-02-14', 'Female', 'AB-', 'Asanka Wickrama', '075-8901238', 'Husband', 'Latex', 'Migraine', 'Sumatriptan as needed', '2024-02-08', TRUE),
(29, '1970-06-08', 'Male', 'O+', 'Malini Weerasinghe', '077-9012349', 'Wife', 'Codeine', 'Hypertension', 'Losartan 50mg', '2024-02-10', TRUE),
(30, '1990-10-19', 'Female', 'B+', 'Saman Silva', '071-0123450', 'Father', 'None', 'Anemia', 'Iron supplements', '2024-02-12', TRUE),
(31, '1968-05-03', 'Male', 'A+', 'Kusum Perera', '072-1234561', 'Wife', 'Penicillin', 'Diabetes Type 2', 'Metformin 1000mg', '2024-02-15', TRUE),
(32, '1983-09-27', 'Female', 'AB+', 'Chandana Ratnayake', '075-2345672', 'Husband', 'None', 'Hypothyroidism', 'Levothyroxine 50mcg', '2024-02-18', TRUE),
(33, '1975-01-11', 'Male', 'O-', 'Dilhan Fernando', '077-3456783', 'Son', 'Dust', 'COPD', 'Inhalers', '2024-02-20', TRUE),
(34, '1993-07-04', 'Female', 'B-', 'Erangi Jayasinghe', '071-4567894', 'Sister', 'None', 'None', 'None', '2024-02-22', TRUE),
(35, '1980-11-29', 'Male', 'A-', 'Gayan Senarath', '072-5678905', 'Brother', 'Sulfa', 'Gout', 'Allopurinol', '2024-02-25', TRUE),
(36, '1976-03-17', 'Female', 'AB-', 'Harsha Gunasekara', '075-6789016', 'Husband', 'None', 'Depression', 'Sertraline 50mg', '2024-02-28', TRUE),
(37, '1987-08-09', 'Male', 'O+', 'Indika Wickrama', '077-7890127', 'Wife', 'Peanuts', 'None', 'None', '2024-03-01', TRUE),
(38, '1991-12-23', 'Female', 'B+', 'Jagath Weerasinghe', '071-8901238', 'Husband', 'None', 'PCOS', 'Metformin', '2024-03-03', TRUE),
(39, '1969-04-05', 'Male', 'A+', 'Kapila Silva', '072-9012349', 'Son', 'Ibuprofen', 'Heart Disease', 'Aspirin, Atorvastatin', '2024-03-05', TRUE),
(40, '1984-06-21', 'Female', 'AB+', 'Lakshmi Perera', '075-0123450', 'Daughter', 'None', 'None', 'None', '2024-03-07', TRUE),
(41, '1973-10-14', 'Male', 'O-', 'Madhava Ratnayake', '077-1234562', 'Wife', 'Codeine', 'Back Pain', 'Painkillers', '2024-03-10', TRUE),
(42, '1989-02-28', 'Female', 'B-', 'Nayana Fernando', '071-2345673', 'Husband', 'None', 'Anxiety', 'None', '2024-03-12', TRUE),
(43, '1977-05-16', 'Male', 'A-', 'Oshala Jayasinghe', '072-3456784', 'Wife', 'Penicillin', 'Epilepsy', 'Sodium valproate', '2024-03-15', TRUE),
(44, '1986-09-30', 'Female', 'AB-', 'Prasanna Senarath', '075-4567895', 'Husband', 'None', 'Thyroid', 'Thyroxine', '2024-03-17', TRUE),
(45, '1967-01-08', 'Male', 'O+', 'Reshma Gunasekara', '077-5678906', 'Daughter', 'Dust', 'Asthma', 'Inhaler', '2024-03-19', TRUE),
(46, '1994-04-25', 'Female', 'B+', 'Sagara Wickrama', '071-6789017', 'Father', 'None', 'None', 'None', '2024-03-22', TRUE),
(47, '1981-07-13', 'Male', 'A+', 'Thilini Weerasinghe', '072-7890128', 'Wife', 'Sulfa', 'Diabetes', 'Metformin', '2024-03-25', TRUE),
(48, '1974-11-02', 'Female', 'AB+', 'Upali Silva', '075-8901239', 'Husband', 'None', 'High Cholesterol', 'Atorvastatin', '2024-03-27', TRUE),
(49, '1988-