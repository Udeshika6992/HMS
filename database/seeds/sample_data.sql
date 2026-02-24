USE hospital_management;

-- Insert departments
INSERT INTO departments (name, description) VALUES
('Cardiology', 'Heart and cardiovascular system treatment'),
('Neurology', 'Brain and nervous system disorders'),
('Pediatrics', 'Medical care for children'),
('Orthopedics', 'Bones, joints, and muscles treatment'),
('General Medicine', 'General health care and consultations'),
('Dermatology', 'Skin care and treatment'),
('Ophthalmology', 'Eye care and treatment');

-- Insert admin (password: Admin@123)
INSERT INTO users (name, email, password, role, is_active) VALUES
('System Administrator', 'admin@hospital.com', '$2y$10$YourHashedPasswordHere', 'admin', 1);

-- Insert doctors (password: Doctor@123)
INSERT INTO users (name, email, password, role, is_active) VALUES
('Dr. Kamal Perera', 'kamal@hospital.com', '$2y$10$YourHashedPasswordHere', 'doctor', 1),
('Dr. Nimali Silva', 'nimali@hospital.com', '$2y$10$YourHashedPasswordHere', 'doctor', 1),
('Dr. Sunil Weerasinghe', 'sunil@hospital.com', '$2y$10$YourHashedPasswordHere', 'doctor', 1),
('Dr. Kumari Jayawardena', 'kumari@hospital.com', '$2y$10$YourHashedPasswordHere', 'doctor', 1);

-- Insert doctor details
INSERT INTO doctors (user_id, specialization, qualification, experience, available_days, available_time_start, available_time_end, department_id) VALUES
(2, 'Cardiologist', 'MD, MRCP', 10, 'Monday,Tuesday,Wednesday,Thursday,Friday', '09:00:00', '17:00:00', 1),
(3, 'Neurologist', 'MD, FRCP', 8, 'Monday,Tuesday,Wednesday,Thursday,Friday', '09:00:00', '17:00:00', 2),
(4, 'Pediatrician', 'MD, DCH', 12, 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday', '08:00:00', '16:00:00', 3),
(5, 'General Physician', 'MBBS, MD', 15, 'Monday,Tuesday,Wednesday,Thursday,Friday', '10:00:00', '18:00:00', 5);

-- Insert patients (password: Patient@123)
INSERT INTO users (name, email, password, role, is_active) VALUES
('Saman Perera', 'saman@gmail.com', '$2y$10$YourHashedPasswordHere', 'patient', 1),
('Nayana Silva', 'nayana@gmail.com', '$2y$10$YourHashedPasswordHere', 'patient', 1),
('Kasun Rathnayake', 'kasun@gmail.com', '$2y$10$YourHashedPasswordHere', 'patient', 1);

-- Insert patient details
INSERT INTO patients (user_id, patient_code, phone, gender, date_of_birth, age, address, blood_group, emergency_contact, emergency_name) VALUES
(6, 'P20240001', '0771234567', 'Male', '1985-05-15', 39, '123 Main St, Kandy', 'O+', '0777654321', 'Mrs. Perera'),
(7, 'P20240002', '0712345678', 'Female', '1990-08-22', 34, '456 Park Ave, Colombo', 'A+', '0718765432', 'Mr. Silva'),
(8, 'P20240003', '0756789123', 'Male', '1978-03-10', 46, '789 Lake Rd, Galle', 'B+', '0751234567', 'Mrs. Rathnayake');

-- Insert symptom mappings for AI
INSERT INTO symptom_mapping (symptom, disease, specialization, severity) VALUES
('chest pain, shortness of breath', 'Heart Attack', 'Cardiology', 'severe'),
('chest pain, sweating', 'Angina', 'Cardiology', 'severe'),
('headache, nausea, light sensitivity', 'Migraine', 'Neurology', 'moderate'),
('fever, cough, fatigue', 'Viral Fever', 'General Medicine', 'mild'),
('fever, sore throat, body ache', 'Flu', 'General Medicine', 'moderate'),
('joint pain, swelling', 'Arthritis', 'Orthopedics', 'moderate'),
('skin rash, itching', 'Dermatitis', 'Dermatology', 'mild'),
('blurred vision, eye pain', 'Glaucoma', 'Ophthalmology', 'severe'),
('back pain, leg numbness', 'Herniated Disc', 'Orthopedics', 'moderate'),
('high fever, headache, vomiting', 'Dengue', 'General Medicine', 'severe');

-- Insert sample appointments
INSERT INTO appointments (appointment_no, patient_id, doctor_id, appointment_date, appointment_time, symptoms, status) VALUES
('APT20240101', 6, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00:00', 'Chest pain and shortness of breath', 'confirmed'),
('APT20240102', 7, 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:30:00', 'Severe headache with nausea', 'pending'),
('APT20240103', 8, 4, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '09:15:00', 'Child has fever and cough', 'confirmed');