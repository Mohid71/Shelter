<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireLogin();

// Only staff and admin can access manage page
if (!isStaffOrAdmin()) {
    header('Location: my_actions.php');
    exit();
}

$activeTab = $_GET['tab'] ?? 'shelters';
$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        $action = $_POST['action'];
        
        switch ($action) {
            case 'create_shelter':
                if (isStaffOrAdmin()) {
                    createShelter($_POST);
                    $message = 'Shelter created successfully!';
                    $messageType = 'success';
                }
                break;
            case 'delete_shelter':
                if (isAdmin()) {
                    deleteShelter($_POST['id']);
                    $message = 'Shelter deleted successfully!';
                    $messageType = 'success';
                }
                break;
            case 'create_bed':
                if (isStaffOrAdmin()) {
                    createBed($_POST);
                    $message = 'Bed created successfully!';
                    $messageType = 'success';
                }
                break;
            case 'delete_bed':
                if (isStaffOrAdmin()) {
                    deleteBed($_POST['id']);
                    $message = 'Bed deleted successfully!';
                    $messageType = 'success';
                }
                break;
            case 'create_resident':
                if (isStaffOrAdmin()) {
                    createResident($_POST);
                    $message = 'Resident created successfully!';
                    $messageType = 'success';
                }
                break;
            case 'delete_resident':
                if (isStaffOrAdmin()) {
                    deleteResident($_POST['id']);
                    $message = 'Resident deleted successfully!';
                    $messageType = 'success';
                }
                break;
            case 'create_volunteer':
                if (isStaffOrAdmin()) {
                    createVolunteer($_POST);
                    $message = 'Volunteer created successfully!';
                    $messageType = 'success';
                }
                break;
            case 'delete_volunteer':
                if (isStaffOrAdmin()) {
                    deleteVolunteer($_POST['user_id'], $_POST['shelter_id']);
                    $message = 'Volunteer deleted successfully!';
                    $messageType = 'success';
                }
                break;
            case 'create_meal':
                if (isStaffOrAdmin()) {
                    createMeal($_POST);
                    $message = 'Meal created successfully!';
                    $messageType = 'success';
                }
                break;
            case 'delete_meal':
                if (isStaffOrAdmin()) {
                    deleteMeal($_POST['id']);
                    $message = 'Meal deleted successfully!';
                    $messageType = 'success';
                }
                break;
            case 'create_donation':
                createDonation($_POST);
                $message = 'Donation created successfully!';
                $messageType = 'success';
                break;
            case 'delete_donation':
                if (isStaffOrAdmin()) {
                    deleteDonation($_POST['id']);
                    $message = 'Donation deleted successfully!';
                    $messageType = 'success';
                }
                break;
            case 'create_waitlist':
                createWaitlistEntry($_POST);
                $message = 'Waitlist entry created successfully!';
                $messageType = 'success';
                break;
            case 'delete_waitlist':
                if (isStaffOrAdmin()) {
                    deleteWaitlistEntry($_POST['id']);
                    $message = 'Waitlist entry deleted successfully!';
                    $messageType = 'success';
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
$users = getAllUsers();
$beds = getAllBeds();
$residents = getAllResidents();
$volunteers = getAllVolunteers();
$meals = getAllMeals();
$donations = getAllDonations();
$waitlist = getAllWaitlist();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Data</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<script src="../assets/js/search.js"></script>
    <div class="header">
        <h1>Shelter Management System</h1>
        <div class="nav">
            <a href="../index.php">Home</a>
            <a href="views.php">Database Views</a>
            <a href="manage.php">Manage Data</a>
            <div class="user-info">
                Welcome, <?php echo getUserFullName(); ?>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Manage Data</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="tabs">
            <span class="tab <?php echo $activeTab == 'shelters' ? 'active' : ''; ?>" onclick="location.href='?tab=shelters'">Shelters</span>
            <span class="tab <?php echo $activeTab == 'beds' ? 'active' : ''; ?>" onclick="location.href='?tab=beds'">Beds</span>
            <span class="tab <?php echo $activeTab == 'residents' ? 'active' : ''; ?>" onclick="location.href='?tab=residents'">Residents</span>
            <span class="tab <?php echo $activeTab == 'volunteers' ? 'active' : ''; ?>" onclick="location.href='?tab=volunteers'">Volunteers</span>
            <span class="tab <?php echo $activeTab == 'meals' ? 'active' : ''; ?>" onclick="location.href='?tab=meals'">Meals</span>
            <span class="tab <?php echo $activeTab == 'donations' ? 'active' : ''; ?>" onclick="location.href='?tab=donations'">Donations</span>
            <span class="tab <?php echo $activeTab == 'waitlist' ? 'active' : ''; ?>" onclick="location.href='?tab=waitlist'">Waitlist</span>
        </div>

        <!-- SHELTERS TAB -->
        <?php if ($activeTab == 'shelters'): ?>
            <h3>Shelters</h3>
            
            <?php if (isStaffOrAdmin()): ?>
                <button class="btn btn-add" onclick="openModal('shelterModal')">Add New Shelter</button>
            <?php endif; ?>

            <!-- Search Filter -->
            <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #e2e8f0;">
                <label>Search Shelters:</label>
                <input type="text" id="filterShelters" placeholder="Search by name, city, address..." onkeyup="searchTable('filterShelters', 'sheltersTable')" style="width: 100%; max-width: 400px;">
            </div>

            <table id="sheltersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Telephone</th>
                        <th>Budget</th>
                        <th>Capacity</th>
                        <?php if (isAdmin()): ?><th>Actions</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($shelters as $shelter): ?>
                        <tr>
                            <td><?php echo $shelter['shelter_id']; ?></td>
                            <td><?php echo htmlspecialchars($shelter['shelter_name']); ?></td>
                            <td><?php echo htmlspecialchars($shelter['city'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($shelter['address'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($shelter['telephone'] ?? 'N/A'); ?></td>
                            <td>$<?php echo $shelter['budget']; ?></td>
                            <td><?php echo $shelter['capacity']; ?></td>
                            <?php if (isAdmin()): ?>
                                <td>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this shelter?');">
                                        <input type="hidden" name="action" value="delete_shelter">
                                        <input type="hidden" name="id" value="<?php echo $shelter['shelter_id']; ?>">
                                        <button type="submit" class="btn-danger">Delete</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- BEDS TAB -->
        <?php if ($activeTab == 'beds'): ?>
            <h3>Beds</h3>
            
            <?php if (isStaffOrAdmin()): ?>
                <button class="btn btn-add" onclick="openModal('bedModal')">Add New Bed</button>
            <?php endif; ?>

            <!-- Search Filter -->
            <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #e2e8f0;">
                <label>Search Beds:</label>
                <input type="text" id="filterBeds" placeholder="Search by room, shelter..." onkeyup="searchTable('filterBeds', 'bedsTable')" style="width: 100%; max-width: 400px;">
            </div>

            <table id="bedsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room Label</th>
                        <th>Shelter</th>
                        <th>Notes</th>
                        <?php if (isStaffOrAdmin()): ?><th>Actions</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($beds as $bed): ?>
                        <tr>
                            <td><?php echo $bed['bed_id']; ?></td>
                            <td><?php echo htmlspecialchars($bed['room_label']); ?></td>
                            <td><?php echo htmlspecialchars($bed['shelter_name']); ?></td>
                            <td><?php echo htmlspecialchars($bed['notes'] ?? 'N/A'); ?></td>
                            <?php if (isStaffOrAdmin()): ?>
                                <td>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete?');">
                                        <input type="hidden" name="action" value="delete_bed">
                                        <input type="hidden" name="id" value="<?php echo $bed['bed_id']; ?>">
                                        <button type="submit" class="btn-danger">Delete</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Include other tabs -->
        <?php include 'manage_tabs_modal.php'; ?>
    </div>

    <!-- Shelter Modal -->
    <div id="shelterModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('shelterModal')">&times;</span>
            <h3>Add New Shelter</h3>
            <form method="POST">
                <input type="hidden" name="action" value="create_shelter">
                <label>Shelter Name: *</label>
                <input type="text" name="shelter_name" required>
                
                <label>City:</label>
                <input type="text" name="city">
                
                <label>Address:</label>
                <input type="text" name="address">
                
                <label>Telephone:</label>
                <input type="text" name="telephone">
                
                <label>Budget:</label>
                <input type="number" name="budget" value="0">
                
                <label>Capacity:</label>
                <input type="number" name="capacity">
                
                <button type="submit">Add Shelter</button>
            </form>
        </div>
    </div>

    <!-- Bed Modal -->
    <div id="bedModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('bedModal')">&times;</span>
            <h3>Add New Bed</h3>
            <form method="POST">
                <input type="hidden" name="action" value="create_bed">
                <label>Room Label: *</label>
                <input type="text" name="room_label" required>
                
                <label>Shelter: *</label>
                <select name="shelter_id" required>
                    <option value="">Select Shelter</option>
                    <?php foreach ($shelters as $shelter): ?>
                        <option value="<?php echo $shelter['shelter_id']; ?>">
                            <?php echo htmlspecialchars($shelter['shelter_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label>Notes:</label>
                <input type="text" name="notes">
                
                <button type="submit">Add Bed</button>
            </form>
        </div>
    </div>

    <!-- Include other modals -->
    <?php include 'manage_modals.php'; ?>

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
