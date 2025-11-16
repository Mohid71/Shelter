<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireLogin();

// Only staff and admin can access views
if (!isStaffOrAdmin()) {
    header('Location: my_actions.php');
    exit();
}

$activeView = $_GET['view'] ?? '1';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Views</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
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
        <h2>Database Views</h2>
        
        <div class="tabs">
            <span class="tab <?php echo $activeView == '1' ? 'active' : ''; ?>" onclick="location.href='?view=1'">View 1</span>
            <span class="tab <?php echo $activeView == '2' ? 'active' : ''; ?>" onclick="location.href='?view=2'">View 2</span>
            <span class="tab <?php echo $activeView == '3' ? 'active' : ''; ?>" onclick="location.href='?view=3'">View 3</span>
            <span class="tab <?php echo $activeView == '4' ? 'active' : ''; ?>" onclick="location.href='?view=4'">View 4</span>
            <span class="tab <?php echo $activeView == '5' ? 'active' : ''; ?>" onclick="location.href='?view=5'">View 5</span>
            <span class="tab <?php echo $activeView == '6' ? 'active' : ''; ?>" onclick="location.href='?view=6'">View 6</span>
            <span class="tab <?php echo $activeView == '7' ? 'active' : ''; ?>" onclick="location.href='?view=7'">View 7</span>
            <span class="tab <?php echo $activeView == '8' ? 'active' : ''; ?>" onclick="location.href='?view=8'">View 8</span>
            <span class="tab <?php echo $activeView == '9' ? 'active' : ''; ?>" onclick="location.href='?view=9'">View 9</span>
            <span class="tab <?php echo $activeView == '10' ? 'active' : ''; ?>" onclick="location.href='?view=10'">View 10</span>
        </div>

        <?php
        $data = [];
        $title = '';
        
        switch ($activeView) {
            case '1':
                $data = getResidentMealDetails();
                $title = 'View 1: Resident Meal Details';
                break;
            case '2':
                $data = getSheltersAboveCityMoneyDonation();
                $title = 'View 2: Shelters Above City Money Donation';
                break;
            case '3':
                $data = getResidentsWithManyNoShows();
                $title = 'View 3: Residents With Many No-Shows';
                break;
            case '4':
                $data = getResidentAndWaitlistStatus();
                $title = 'View 4: Resident and Waitlist Status';
                break;
            case '5':
                $data = getResidentVolunteers();
                $title = 'View 5: Resident Volunteers';
                break;
            case '6':
                $data = getShelterOccupancy();
                $title = 'View 6: Shelter Occupancy';
                break;
            case '7':
                $data = getMealsByShelter();
                $title = 'View 7: Meals by Shelter';
                break;
            case '8':
                $data = getResidentBasicInfo();
                $title = 'View 8: Resident Basic Info';
                break;
            case '9':
                $data = getVolunteerBasicInfo();
                $title = 'View 9: Volunteer Basic Info';
                break;
            case '10':
                $data = getDonationTotalsByUser();
                $title = 'View 10: Donation Totals by User';
                break;
        }
        ?>

        <h3><?php echo $title; ?></h3>

        <?php if (empty($data)): ?>
            <p>No data available.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <?php foreach (array_keys($data[0]) as $column): ?>
                            <th><?php echo ucwords(str_replace('_', ' ', $column)); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?php echo htmlspecialchars($value ?? 'N/A'); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><strong>Total Records:</strong> <?php echo count($data); ?></p>
            <button onclick="exportToCSV()">Export to CSV</button>
            <button onclick="window.print()">Print/Export PDF</button>
        <?php endif; ?>
    </div>
    <script>
    function exportToCSV() {
        const table = document.querySelector('table');
        let csv = [];
        
        // Get headers
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent);
        csv.push(headers.join(','));
        
        // Get rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cols = Array.from(row.querySelectorAll('td')).map(td => {
                let text = td.textContent.trim();
                if (text.includes(',') || text.includes('"')) {
                    text = '"' + text.replace(/"/g, '""') + '"';
                }
                return text;
            });
            csv.push(cols.join(','));
        });
        
        // Download
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'view_<?php echo $activeView; ?>.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    }
    </script>
</body>
</html>
