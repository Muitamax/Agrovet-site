<?php
include '../config.php';
include '../functions.php';

// Register customer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    $errors = [];
    
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($phone)) $errors[] = "Phone is required";
    if (empty($password) || strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match";
    
    // Check if email exists
    $check = $conn->query("SELECT id FROM customers WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $errors[] = "Email already registered";
    }
    
    if (empty($errors)) {
        $password_hash = hashPassword($password);
        
        $query = "INSERT INTO customers (name, email, phone, password, created_at) 
                  VALUES ('$name', '$email', '$phone', '$password_hash', NOW())";
        
        if ($conn->query($query)) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: login.php");
            exit;
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}

// Login customer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    $result = $conn->query("SELECT * FROM customers WHERE email = '$email'");
    
    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        
        if (verifyPassword($password, $customer['password'])) {
            $_SESSION['customer_id'] = $customer['id'];
            $_SESSION['customer_name'] = $customer['name'];
            $_SESSION['customer_email'] = $customer['email'];
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
}
?>
