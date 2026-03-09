# Hospital Management System (HMS)

A complete web-based Hospital Management System built with PHP (OOP), MySQL, and Bootstrap. This system manages patients, doctors, appointments, medical records, prescriptions, and more.

## Features

### User Roles
- **Admin**: Full system control, user management, reports
- **Doctor**: Patient management, appointments, prescriptions, progress tracking
- **Patient**: Book appointments, view medical history, track health progress

### Key Features
- User Authentication & Authorization
- Role-based Dashboard
- Appointment Booking & Management
- Medical Records Management
- Prescription Management
- Patient Progress Tracking
- Department Management
- Reports Generation
- Profile Management
- Email Notifications (optional)
- PDF Reports (optional)

## Technologies Used

- **Backend**: PHP 8.2 (Object-Oriented)
- **Database**: MySQL 8.0 / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Libraries**: jQuery, Chart.js, DataTables, Font Awesome
- **Architecture**: MVC Pattern
- **Design Patterns**: Singleton, Factory, Front Controller

## Project Structure
HMS/
├── assets/ # CSS, JS, images, vendor files
├── config/ # Configuration files
├── core/ # Core classes (Router, Controller, Model)
├── controllers/ # Application controllers
├── models/ # Database models
├── views/ # View templates
├── middleware/ # Authentication middleware
├── factories/ # Factory classes
├── includes/ # Helper functions
├── database/ # SQL migrations and seeds
├── uploads/ # User uploaded files
├── logs/ # Application logs
└── lib/ # Third-party libraries


## Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (optional)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/hms.git
   cd hms

   Configure Database

Create a MySQL database named hms_db

Import database/migration.sql

Import database/seed.sql for sample data

Configure Application

Copy config/config.example.php to config/config.php

Update database credentials in config/config.php

Set BASE_URL to your project URL

Set Permissions

bash
chmod 777 uploads/
chmod 777 logs/
Start Server

If using XAMPP: Start Apache and MySQL

Access the application at http://localhost/HMS/
