<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

if (isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $role = $_POST['role'] ?? 'Other';
    
    // Validation
    if (empty($email) || empty($password) || empty($first_name) || empty($last_name) || empty($date_of_birth)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 3) {
        $error = 'Password must be at least 3 characters long.';
    } else {
        // Check if email already exists
        $conn = getDbConnection();
        $stmt = $conn->prepare("SELECT user_id FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email already exists. Please use a different email or login.';
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO Users (email, password, first_name, last_name, date_of_birth, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $email, $password, $first_name, $last_name, $date_of_birth, $role);
            
            if ($stmt->execute()) {
                $success = 'Account created successfully! You can now login.';
                // Optionally auto-login the user
                // $user_id = $conn->insert_id;
                // $_SESSION['user_id'] = $user_id;
                // header('Location: ../index.php');
                // exit();
            } else {
                $error = 'Error creating account. Please try again.';
            }
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Shelter Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Create Account</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
                <br><a href="login.php">Click here to login</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>First Name: *</label>
            <input type="text" name="first_name" required value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
            
            <label>Last Name: *</label>
            <input type="text" name="last_name" required value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
            
            <label>Email: *</label>
            <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            
            <label>Date of Birth: *</label>
            <input type="date" name="date_of_birth" required value="<?php echo htmlspecialchars($_POST['date_of_birth'] ?? ''); ?>">
            
            <label>Role: *</label>
            <select name="role" required>
                <option value="Other">Other</option>
                <option value="Volunteer">Volunteer</option>
                <option value="Staff">Staff</option>
            </select>
            <small style="color: #666; font-size: 12px;">Note: Admin role can only be assigned by existing admins</small>
            
            <label>Password: *</label>
            <input type="password" name="password" required minlength="3">
            <small style="color: #666; font-size: 12px;">Minimum 3 characters</small>
            
            <label>Confirm Password: *</label>
            <input type="password" name="confirm_password" required minlength="3">
            
            <button type="submit">Create Account</button>
        </form>
        
        <p style="margin-top: 20px; text-align: center;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
        
        <p style="text-align: center;">
            <a href="../index.php">Back to Home</a>
        </p>
    </div>
</body>
</html>
