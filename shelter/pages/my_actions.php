<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireLogin();

$message = '';
$messageType = '';
$currentUserId = $_SESSION['user_id'];
$userRole = getUserRole();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        $action = $_POST['action'];
        
        switch ($action) {
            case 'donate':
                $_POST['user_id'] = $currentUserId;
                createDonation($_POST);
                $message = 'Thank you for your donation!';
                $messageType = 'success';
                break;
                
            case 'volunteer':
                if (!isStaffOrAdmin()) {
                    $_POST['user_id'] = $currentUserId;
                    createVolunteer($_POST);
                    $message = 'Thank you for volunteering!';
                    $messageType = 'success';
                } else {
                    $message = 'Admin and Staff cannot sign up as volunteers.';
                    $messageType = 'error';
                }
                break;
                
            case 'request_bed':
                if (!isStaffOrAdmin()) {
                    $_POST['user_id'] = $currentUserId;
                    createWaitlistEntry($_POST);
                    $message = 'Your bed request has been submitted!';
                    $messageType = 'success';
                } else {
                    $message = 'Admin and Staff cannot request beds.';
                    $messageType = 'error';
                }
                break;
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Fetch data
$shelters = getAllShelters();

$conn = getDbConnection();

$myDonations = $conn->query("
    SELECT d.*, d.donation_date, d.donation_type, d.amount, d.in_kind_details
    FROM Donations d
    WHERE d.user_id = $currentUserId
    ORDER BY d.donation_date DESC
")->fetch_all(MYSQLI_ASSOC);

$myVolunteer = $conn->query("
    SELECT v.*, s.shelter_name, v.task_area, v.join_date
    FROM Volunteers v
    JOIN Shelters s ON v.shelter_id = s.shelter_id
    WHERE v.user_id = $currentUserId
")->fetch_all(MYSQLI_ASSOC);

$myWaitlist = $conn->query("
    SELECT w.*, w.request_date, w.status, w.notes, s.shelter_name
    FROM Waitlist w
    LEFT JOIN Shelters s ON w.shelter_id = s.shelter_id
    WHERE w.user_id = $currentUserId
    ORDER BY w.request_date DESC
")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Actions</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="header">
        <h1>Shelter Management System</h1>
        <div class="nav">
            <a href="../index.php">Home</a>
            <?php if (isStaffOrAdmin()): ?>
                <a href="views.php">Database Views</a>
                <a href="manage.php">Manage Data</a>
            <?php else: ?>
                <a href="my_actions.php">My Actions</a>
            <?php endif; ?>
            <div class="user-info">
                Welcome, <?php echo getUserFullName(); ?>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>My Actions</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div style="margin: 20px 0;">
            <button class="btn btn-add" onclick="openModal('donateModal')">Make a Donation</button>
            <?php if (!isStaffOrAdmin()): ?>
                <button class="btn btn-add" onclick="openModal('volunteerModal')">Sign Up as Volunteer</button>
                <button class="btn btn-add" onclick="openModal('waitlistModal')">Request a Bed</button>
            <?php endif; ?>
        </div>

        <!-- My Donations -->
        <h3>My Donations</h3>
        <?php if (empty($myDonations)): ?>
            <p>You haven't made any donations yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($myDonations as $donation): ?>
                        <tr>
                            <td><?php echo $donation['donation_date']; ?></td>
                            <td><?php echo $donation['donation_type']; ?></td>
                            <td><?php echo $donation['amount'] ? '$' . $donation['amount'] : 'N/A'; ?></td>
                            <td><?php echo htmlspecialchars($donation['in_kind_details'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- My Volunteer Info -->
        <?php if (!isStaffOrAdmin()): ?>
            <h3>My Volunteer Assignments</h3>
            <?php if (empty($myVolunteer)): ?>
                <p>You are not currently signed up as a volunteer.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Shelter</th>
                            <th>Task Area</th>
                            <th>Join Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($myVolunteer as $vol): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vol['shelter_name']); ?></td>
                                <td><?php echo htmlspecialchars($vol['task_area'] ?? 'N/A'); ?></td>
                                <td><?php echo $vol['join_date'] ?? 'N/A'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- My Waitlist Requests -->
            <h3>My Bed Requests</h3>
            <?php if (empty($myWaitlist)): ?>
                <p>You have no pending bed requests.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Request Date</th>
                            <th>Preferred Shelter</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($myWaitlist as $wait): ?>
                            <tr>
                                <td><?php echo $wait['request_date']; ?></td>
                                <td><?php echo htmlspecialchars($wait['shelter_name'] ?? 'Not specified'); ?></td>
                                <td><?php echo $wait['status']; ?></td>
                                <td><?php echo htmlspecialchars($wait['notes'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Donate Modal -->
    <div id="donateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('donateModal')">&times;</span>
            <h3>Make a Donation</h3>
            <form method="POST">
                <input type="hidden" name="action" value="donate">
                
                <label>Donation Type: *</label>
                <select name="donation_type" required>
                    <option value="">Select Type</option>
                    <option value="Money">Money</option>
                    <option value="Food">Food</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Other">Other</option>
                </select>
                
                <label>Amount (for Money donations):</label>
                <input type="number" name="amount" min="1">
                
                <label>In-Kind Details:</label>
                <input type="text" name="in_kind_details" placeholder="Describe non-monetary donations">
                
                <label>Donation Date: *</label>
                <input type="date" name="donation_date" required value="<?php echo date('Y-m-d'); ?>">
                
                <button type="submit">Submit Donation</button>
            </form>
        </div>
    </div>

    <!-- Volunteer Modal -->
    <?php if (!isStaffOrAdmin()): ?>
    <div id="volunteerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('volunteerModal')">&times;</span>
            <h3>Sign Up as Volunteer</h3>
            <form method="POST">
                <input type="hidden" name="action" value="volunteer">
                
                <label>Shelter: *</label>
                <select name="shelter_id" required>
                    <option value="">Select Shelter</option>
                    <?php foreach ($shelters as $shelter): ?>
                        <option value="<?php echo $shelter['shelter_id']; ?>">
                            <?php echo htmlspecialchars($shelter['shelter_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label>Task Area:</label>
                <select name="task_area">
                    <option value="">Select Task</option>
                    <option value="Kitchen">Kitchen</option>
                    <option value="FrontDesk">Front Desk</option>
                    <option value="Cleaning">Cleaning</option>
                    <option value="General">General</option>
                </select>
                
                <label>Join Date:</label>
                <input type="date" name="join_date" value="<?php echo date('Y-m-d'); ?>">
                
                <button type="submit">Sign Up</button>
            </form>
        </div>
    </div>

    <!-- Waitlist Modal -->
    <div id="waitlistModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('waitlistModal')">&times;</span>
            <h3>Request a Bed</h3>
            <form method="POST">
                <input type="hidden" name="action" value="request_bed">
                
                <label>Preferred Shelter: *</label>
                <select name="shelter_id" required>
                    <option value="">-- Select Shelter --</option>
                    <?php foreach ($shelters as $shelter): ?>
                        <option value="<?php echo $shelter['shelter_id']; ?>">
                            <?php echo htmlspecialchars($shelter['shelter_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label>Request Date: *</label>
                <input type="date" name="request_date" required value="<?php echo date('Y-m-d'); ?>">
                
                <label>Notes:</label>
                <textarea name="notes" rows="3" placeholder="Any special requests or requirements..."></textarea>
                
                <button type="submit">Submit Request</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>