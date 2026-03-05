<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $student_id = trim($_POST['student_id']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cnic = trim($_POST['cnic']);
    $phone = trim($_POST['phone']);
    $cgpa = $_POST['cgpa'];
    $department = $_POST['department'];
    
    // Server-side validation
    
    // 1. Student ID validation
    if (!preg_match('/^FA\d{2}-BCS-\d{3}$/', $student_id)) {
        die("Error: Invalid Student ID format");
    }
    
    // 2. Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid Email format");
    }
    
    // 3. Password validation
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        die("Error: Password does not meet requirements");
    }
    
    // 4. CNIC validation
    if (!preg_match('/^\d{5}-\d{7}-\d{1}$/', $cnic)) {
        die("Error: Invalid CNIC format");
    }
    
    // 5. Phone validation
    if (!preg_match('/^03\d{9}$/', $phone)) {
        die("Error: Invalid Phone format");
    }
    
    // 6. CGPA validation
    if ($cgpa < 0.0 || $cgpa > 4.0) {
        die("Error: CGPA must be between 0.00 and 4.00");
    }
    
    // 7. File upload validation
    $target_dir = "uploads/";
    
    // Create uploads directory if not exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $resume_name = time() . "_" . basename($_FILES["resume"]["name"]);
    $target_file = $target_dir . $resume_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if file is PDF
    if ($file_type != "pdf") {
        die("Error: Only PDF files are allowed");
    }
    
    // Check file size (2MB max)
    if ($_FILES["resume"]["size"] > 2 * 1024 * 1024) {
        die("Error: File size must be less than 2MB");
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES["resume"]["tmp_name"]);
    if ($mime != "application/pdf") {
        die("Error: File is not a valid PDF");
    }
    finfo_close($finfo);
    
    // Upload file
    if (!move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        die("Error: Failed to upload file");
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check for duplicate email using prepared statement
    $check_stmt = $conn->prepare("SELECT id FROM students WHERE email = ? OR student_id = ? OR cnic = ?");
    $check_stmt->bind_param("sss", $email, $student_id, $cnic);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows > 0) {
        unlink($target_file); // Delete uploaded file
        die("Error: Student ID, Email or CNIC already exists");
    }
    $check_stmt->close();
    
    // Insert data using prepared statement
    $stmt = $conn->prepare("INSERT INTO students (student_id, full_name, email, password, cnic, phone, cgpa, department, resume_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssdss", $student_id, $full_name, $email, $hashed_password, $cnic, $phone, $cgpa, $department, $target_file);
    
    if ($stmt->execute()) {
        echo "<h3 style='color: green;'>Registration Successful!</h3>";
        echo "<p>Student ID: " . htmlspecialchars($student_id) . "</p>";
        echo "<p>Name: " . htmlspecialchars($full_name) . "</p>";
        echo "<p>Email: " . htmlspecialchars($email) . "</p>";
        echo "<a href='index.html'>Register Another Student</a>";
    } else {
        unlink($target_file); // Delete uploaded file if insert fails
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.html");
    exit();
}
?>