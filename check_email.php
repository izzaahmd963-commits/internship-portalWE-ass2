<?php
include 'config.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "taken";
    } else {
        echo "available";
    }
    
    $stmt->close();
}
$conn->close();
?>