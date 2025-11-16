<?php
require_once '../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        $result = login($email, $password);
        if ($result['success']) {
            header('Location: ../index.php');
            exit();
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Shelter Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        
        <p style="margin-top: 20px; font-size: 14px;">
            <strong>Test Account:</strong><br>
            Email: aditya.ramjas@example.com<br>
            Password: pw4
        </p>
        
        <p style="text-align: center; margin-top: 20px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
        
        <p style="text-align: center;">
            <a href="../index.php">Back to Home</a>
        </p>
    </div>
</body>
</html>
