<?php
require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'core/Model.php';
require_once 'models/UserModel.php';
require_once 'models/DoctorModel.php';

echo "<h1>👨‍⚕️ Add Sample Doctors</h1>";

try {
    $db = Database::getInstance()->getConnection();
    $userModel = new UserModel();
    $doctorModel = new DoctorModel();
    
    // Check if departments exist
    $depts = $db->query("SELECT id FROM departments LIMIT 1")->fetch();
    if (!$depts) {
        // Create default departments
        $db->exec("INSERT INTO departments (department_name, description, floor_number) VALUES
            ('General Medicine', 'Primary care', '1'),
            ('Pediatrics', 'Child healthcare', '1'),
            ('Cardiology', 'Heart care', '2'),
            ('Orthopedics', 'Bone and joint', '2'),
            ('Dermatology', 'Skin care', '3')");
        echo "<p>✅ Created default departments</p>";
    }
    
    // Sample doctors data
    $doctors = [
        [
            'username' => 'dr_smith',
            'email' => 'dr.smith@hospital.com',
            'password' => 'password123',
            'full_name' => 'John Smith',
            'specialization' => 'General Physician',
            'qualification' => 'MBBS, MD',
            'experience' => 10,
            'license' => 'LIC001',
            'dept' => 1
        ],
        [
            'username' => 'dr_jones',
            'email' => 'dr.jones@hospital.com',
            'password' => 'password123',
            'full_name' => 'Sarah Jones',
            'specialization' => 'Cardiologist',
            'qualification' => 'MBBS, DM',
            'experience' => 8,
            'license' => 'LIC002',
            'dept' => 3
        ],
        [
            'username' => 'dr_wilson',
            'email' => 'dr.wilson@hospital.com',
            'password' => 'password123',
            'full_name' => 'Michael Wilson',
            'specialization' => 'Pediatrician',
            'qualification' => 'MBBS, DCH',
            'experience' => 12,
            'license' => 'LIC003',
            'dept' => 2
        ],
        [
            'username' => 'dr_brown',
            'email' => 'dr.brown@hospital.com',
            'password' => 'password123',
            'full_name' => 'Emily Brown',
            'specialization' => 'Dermatologist',
            'qualification' => 'MBBS, DVD',
            'experience' => 6,
            'license' => 'LIC004',
            'dept' => 5
        ],
        [
            'username' => 'dr_davis',
            'email' => 'dr.davis@hospital.com',
            'password' => 'password123',
            'full_name' => 'David Davis',
            'specialization' => 'Orthopedic Surgeon',
            'qualification' => 'MBBS, MS',
            'experience' => 15,
            'license' => 'LIC005',
            'dept' => 4
        ]
    ];
    
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #007bff; color: white;'><th>Doctor</th><th>Email</th><th>Specialization</th><th>Status</th></tr>";
    
    foreach ($doctors as $doc) {
        // Check if user already exists
        $existingUser = $userModel->findByEmail($doc['email']);
        
        if ($existingUser) {
            // Check if doctor record exists
            $doctorExists = $db->prepare("SELECT id FROM doctors WHERE user_id = ?");
            $doctorExists->execute([$existingUser['id']]);
            
            if (!$doctorExists->fetch()) {
                // Create doctor record for existing user
                $doctorData = [
                    'user_id' => $existingUser['id'],
                    'department_id' => $doc['dept'],
                    'specialization' => $doc['specialization'],
                    'qualification' => $doc['qualification'],
                    'experience_years' => $doc['experience'],
                    'license_number' => $doc['license'],
                    'available_days' => 'Mon,Tue,Wed,Thu,Fri',
                    'available_time_start' => '09:00:00',
                    'available_time_end' => '17:00:00',
                    'max_patients_per_day' => 20,
                    'is_available' => 1
                ];
                $doctorModel->create($doctorData);
                
                echo "<tr>";
                echo "<td>Dr. " . $doc['full_name'] . "</td>";
                echo "<td>" . $doc['email'] . "</td>";
                echo "<td>" . $doc['specialization'] . "</td>";
                echo "<td style='color: orange;'>✅ Doctor record added</td>";
                echo "</tr>";
            } else {
                echo "<tr>";
                echo "<td>Dr. " . $doc['full_name'] . "</td>";
                echo "<td>" . $doc['email'] . "</td>";
                echo "<td>" . $doc['specialization'] . "</td>";
                echo "<td style='color: blue;'>ℹ️ Already exists</td>";
                echo "</tr>";
            }
            continue;
        }
        
        // Create new user
        $userData = [
            'username' => $doc['username'],
            'email' => $doc['email'],
            'password' => $doc['password'],
            'full_name' => $doc['full_name'],
            'role' => 'doctor'
        ];
        
        $userId = $userModel->createUser($userData);
        
        if ($userId) {
            // Create doctor record
            $doctorData = [
                'user_id' => $userId,
                'department_id' => $doc['dept'],
                'specialization' => $doc['specialization'],
                'qualification' => $doc['qualification'],
                'experience_years' => $doc['experience'],
                'license_number' => $doc['license'],
                'available_days' => 'Mon,Tue,Wed,Thu,Fri',
                'available_time_start' => '09:00:00',
                'available_time_end' => '17:00:00',
                'max_patients_per_day' => 20,
                'is_available' => 1
            ];
            
            $doctorModel->create($doctorData);
            
            echo "<tr>";
            echo "<td>Dr. " . $doc['full_name'] . "</td>";
            echo "<td>" . $doc['email'] . "</td>";
            echo "<td>" . $doc['specialization'] . "</td>";
            echo "<td style='color: green;'>✅ Added successfully</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    // Show current doctors
    echo "<h2 style='margin-top: 30px;'>Current Doctors in System:</h2>";
    
    $sql = "SELECT u.id, u.full_name, u.email, d.specialization, d.license_number, dep.department_name
            FROM users u
            JOIN doctors d ON u.id = d.user_id
            LEFT JOIN departments dep ON d.department_id = dep.id
            WHERE u.role = 'doctor'
            ORDER BY u.full_name";
    
    $currentDoctors = $db->query($sql)->fetchAll();
    
    if (count($currentDoctors) > 0) {
        echo "<table border='1' cellpadding='8' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #28a745; color: white;'><th>ID</th><th>Name</th><th>Email</th><th>Specialization</th><th>Department</th></tr>";
        
        foreach ($currentDoctors as $doctor) {
            echo "<tr>";
            echo "<td>" . $doctor['id'] . "</td>";
            echo "<td>Dr. " . htmlspecialchars($doctor['full_name']) . "</td>";
            echo "<td>" . $doctor['email'] . "</td>";
            echo "<td>" . ($doctor['specialization'] ?? 'General') . "</td>";
            echo "<td>" . ($doctor['department_name'] ?? 'Not Assigned') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p><strong>Total Doctors: " . count($currentDoctors) . "</strong></p>";
    } else {
        echo "<p style='color: red;'>❌ No doctors found in database</p>";
    }
    
    echo "<p style='margin-top: 20px;'><a href='" . BASE_URL . "admin/doctors' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Doctors Page</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}