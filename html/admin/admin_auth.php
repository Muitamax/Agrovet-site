<?php
// Admin authentication
$admin_users = [
    'admin' => password_hash('admin123', PASSWORD_BCRYPT),
    'amboni' => password_hash('amboni123', PASSWORD_BCRYPT)
];

$admin_login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    if (isset($admin_users[$username]) && password_verify($password, $admin_users[$username])) {
        $_SESSION['admin_id'] = 'admin_' . $username;
        $_SESSION['admin_name'] = ucfirst($username);
        header("Location: dashboard.php");
        exit;
    } else {
        $admin_login_error = "Invalid username or password";
    }
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Logout admin
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_name']);
    header("Location: login.php");
    exit;
}
?>
