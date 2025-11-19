<?php
require_once 'includes/auth.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shelter Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="header">
        <h1>Shelter Management System</h1>
        <div class="nav">
            <a href="index.php">Home</a>
            <?php if (isLoggedIn()): ?>
                <?php if (isStaffOrAdmin()): ?>
                    <a href="pages/views.php">Database Views</a>
                    <a href="pages/manage.php">Manage Data</a>
                <?php else: ?>
                    <a href="pages/my_actions.php">My Actions</a>
                <?php endif; ?>
                <div class="user-info">
                    Welcome, <?php echo getUserFullName(); ?> (<?php echo getUserRole(); ?>)
                    <a href="pages/logout.php">Logout</a>
                </div>
            <?php else: ?>
                <a href="pages/login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <h2>Welcome to Shelter Management System</h2>
        
        <?php if (!isLoggedIn()): ?>
            <p>Please <a href="pages/login.php">login</a> to access the system.</p>
            <p>Don't have an account? <a href="pages/register.php">Create one here</a></p>
        <?php else: ?>
            <?php if (isStaffOrAdmin()): ?>
                <p>Select an option from the menu above:</p>
                <ul>
                    <li><a href="pages/views.php">Database Views</a> - View all 10 database reports</li>
                    <li><a href="pages/manage.php">Manage Data</a> - Add, edit, or delete records</li>
                </ul>
            <?php else: ?>
                <p>Welcome! You can:</p>
                <ul>
                    <li><a href="pages/my_actions.php">Make a Donation</a></li>
                    <li><a href="pages/my_actions.php">Sign up as Volunteer</a></li>
                    <li><a href="pages/my_actions.php">Request a Bed (Join Waitlist)</a></li>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
