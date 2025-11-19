<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
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
    <h3 style="position: fixed; bottom: 90px; right: 20px; color: black; font-weight: bold;">
    Shelter Area Weather:
    </h3>
    <div class="weather-widget" style="position: fixed; bottom: 20px; right: 20px; color: black;">
        <div id="weather-content" style="font-size: 24px; font-weight: bold;">Loading Local Weather...</div>
        <div id="weather-desc" style="font-size: 14px;"></div>
        <div id="weather-details" style="font-size: 12px;"></div>
    </div>

    <script>
function fetchOshawaWeather() {
    // current weather API instead of forecast
    var url = "https://api.openweathermap.org/data/2.5/weather?q=Oshawa,CA&units=metric&appid=9e4d5d85909b94e184d26cfc792c7ffb";

    fetch(url)
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {

            if (data.main) {
                var temp = Math.round(data.main.temp);
                var desc = data.weather[0].main;
                var feels = Math.round(data.main.feels_like);
                var hum = data.main.humidity;

                document.getElementById("weather-content").innerText = temp + "°C";
                document.getElementById("weather-desc").innerText = desc;
                document.getElementById("weather-details").innerText =
                    "Feels like " + feels + "°C • Humidity: " + hum + "%";
            } else {
                document.getElementById("weather-content").innerText = "N/A";
                document.getElementById("weather-desc").innerText = "Unable to load";
            }

        })
        .catch(function(err) {
            document.getElementById("weather-content").innerText = "N/A";
            document.getElementById("weather-desc").innerText = "Unable to load";
        });
}

window.onload = fetchOshawaWeather;
</script>

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
