<?php
include '../config.php';
include 'admin_auth.php';
if (isAdminLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Amboni Agrovet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #2c5f2d 0%, #558b2f 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .login-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); width: 100%; max-width: 400px; margin: 20px; }
        .logo { text-align: center; margin-bottom: 30px; }
        .logo h1 { color: #2c5f2d; font-size: 1.8rem; }
        h2 { text-align: center; color: #2c5f2d; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #333; font-weight: 600; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; }
        input:focus { outline: none; border-color: #2c5f2d; box-shadow: 0 0 5px rgba(44, 95, 45, 0.3); }
        button { width: 100%; padding: 12px; background: #2c5f2d; color: white; border: none; border-radius: 5px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        button:hover { background: #1e4620; }
        .error { background: #fee; color: #c33; padding: 12px; border-radius: 5px; margin-bottom: 20px; }
        .credentials { background: #f0f0f0; padding: 15px; border-radius: 5px; margin-top: 20px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo"><h1>🌾 Amboni Agrovet</h1><p>Admin Dashboard</p></div>
        <?php if (!empty($admin_login_error)): ?><div class="error"><?php echo htmlspecialchars($admin_login_error); ?></div><?php endif; ?>
        <h2>Admin Login</h2>
        <form method="POST">
            <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <button type="submit" name="admin_login">Login</button>
        </form>
        <div class="credentials"><strong>Demo Credentials:</strong><p>Username: <strong>admin</strong></p><p>Password: <strong>admin123</strong></p></div>
    </div>
</body>
</html>
