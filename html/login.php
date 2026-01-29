<?php include 'auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - Amboni Agrovet</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2c5f2d 0%, #558b2f 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h1 {
            color: #2c5f2d;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .logo p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        
        input[type="email"],
        input[type="password"],
        input[type="text"],
        input[type="tel"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #2c5f2d;
            box-shadow: 0 0 5px rgba(44, 95, 45, 0.3);
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: #2c5f2d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #1e4620;
        }
        
        .toggle-form {
            text-align: center;
            margin-top: 20px;
        }
        
        .toggle-form a {
            color: #2c5f2d;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }
        
        .toggle-form a:hover {
            text-decoration: underline;
        }
        
        .error {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }
        
        .success {
            background: #efe;
            color: #3c3;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #3c3;
        }
        
        .form-section {
            display: none;
        }
        
        .form-section.active {
            display: block;
        }
        
        .back-link {
            margin-bottom: 20px;
        }
        
        .back-link a {
            color: #2c5f2d;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>🌾 Amboni Agrovet</h1>
            <p>Your Trusted Agricultural Partner</p>
        </div>
        
        <!-- Login Form -->
        <div id="login-form" class="form-section active">
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <h2 style="text-align: center; margin-bottom: 20px; color: #2c5f2d;">Customer Login</h2>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" name="login">Login</button>
                
                <div class="toggle-form">
                    Don't have an account? <a onclick="toggleForms()">Register here</a>
                </div>
            </form>
        </div>
        
        <!-- Register Form -->
        <div id="register-form" class="form-section">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo $error; ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <h2 style="text-align: center; margin-bottom: 20px; color: #2c5f2d;">Create Account</h2>
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                
                <button type="submit" name="register">Register</button>
                
                <div class="toggle-form">
                    Already have an account? <a onclick="toggleForms()">Login here</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function toggleForms() {
            document.getElementById('login-form').classList.toggle('active');
            document.getElementById('register-form').classList.toggle('active');
        }
    </script>
</body>
</html>
