# internship-portalWE-ass2
# Secure Online Internship Registration Portal

A secure web application for university students to register for internships with multi-level validation and protection against common web attacks.

## 📋 Features
- **Student Registration Form** with fields: Student ID, Name, Email, Password, CNIC, Phone, CGPA, Department, Resume (PDF)
- **Client-side Validation** using JavaScript
- **Server-side Validation** using PHP
- **AJAX-based Email Availability Check**
- **Secure Database Design** with MySQL
- **Protection against:** SQL Injection, XSS, Duplicate Registration, File Upload Exploits

## 🛠️ Technologies Used
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Server:** WAMP (Windows, Apache, MySQL, PHP)


## 🚀 How to Run

### Requirements
- WAMP Server installed
- Web browser (Chrome, Firefox, etc.)

### Installation Steps

1. **Start WAMP Server** (icon turns green)

2. **Create Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin/`
   - Create database named `internship_portal`
   - Run the SQL from `database.sql`

3. **Place Project Files**
   - Copy all files to: `C:\wamp64\www\internship_portal\`

4. **Run the Application**
   - Open browser and go to: `http://localhost/internship_portal/index.html`

### Database SQL
### Run this code phpmyadmin withstart wampserver
```sql
CREATE DATABASE internship_portal;
USE internship_portal;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    cnic VARCHAR(15) NOT NULL UNIQUE,
    phone VARCHAR(11) NOT NULL,
    cgpa DECIMAL(3,2) NOT NULL,
    department VARCHAR(50) NOT NULL,
    resume_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
