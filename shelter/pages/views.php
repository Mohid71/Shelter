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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<script src="../assets/js/search.js"></script>
<script src="../assets/js/sort.js"></script>
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
        
        <!-- Search and Filter Section -->
        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #e2e8f0;">
            <h4 style="margin-top: 0;">Search & Filter</h4>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="searchInput">Search All Columns:</label>
                    <input type="text" id="searchInput" placeholder="Type to search..." onkeyup="searchTable('searchInput', 'viewTable')" style="width: 100%;">
                </div>
                <div style="min-width: 200px;">
                    <label for="sortSelect">Sort By:</label>
                    <select id="sortSelect" onchange="sortTableBySelect('sortSelect', 'viewTable')" style="width: 100%;">
                        <option value="">-- Select Column --</option>
                    </select>
                </div>
            </div>
            <div id="rowCount" style="margin-top: 1rem; color: #64748b; font-weight: 600;"></div>
        </div>

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
                $data = getBedAssignmentDetails();
                $title = 'View 1: Bed Assignment Details';
                break;
              case '2':
                $data = getSheltersAboveAverageDonations();
                $title = 'View 2: Shelters With Above-Average Donations';
                break;
            case '3':
                $data = getResidentsWithNoShows();
                $title = 'View 3: Residents With No-Shows';
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
            <!-- Chart Section -->
            <?php if (in_array($activeView, ['2', '3', '6', '10'])): ?>
                <div style="margin-bottom: 1.5rem;">
                    <button onclick="toggleChart()" class="btn" id="chartToggle">Show Chart</button>
                </div>
                <div id="chartContainer" style="display: none; background: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
                    <canvas id="dataChart" style="max-height: 400px;"></canvas>
                </div>
            <?php endif; ?>
            <table id="viewTable">
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
<script>
let chartInstance = null;

// Toggle chart visibility
function toggleChart() {
    const container = document.getElementById('chartContainer');
    const button = document.getElementById('chartToggle');
    
    if (container.style.display === 'none') {
        container.style.display = 'block';
        button.textContent = 'Hide Chart';
        if (!chartInstance) {
            renderChart();
        }
    } else {
        container.style.display = 'none';
        button.textContent = 'Show Chart';
    }
}

// Render chart based on active view
function renderChart() {
    const activeView = '<?php echo $activeView; ?>';
    const data = <?php echo json_encode($data); ?>;
    
    let chartConfig = null;
    
    switch(activeView) {
        case '2': // SheltersAboveCityMoneyDonation
            chartConfig = {
                type: 'bar',
                data: {
                    labels: data.map(row => row.shelter_name),
                    datasets: [{
                        label: 'Total Money Donations ($)',
                        data: data.map(row => row.total_money_donations),
                        backgroundColor: '#2563eb'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Money Donations by Shelter'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount ($)'
                            }
                        }
                    }
                }
            };
            break;
            
        case '3': // ResidentsWithManyNoShows
            chartConfig = {
                type: 'bar',
                data: {
                    labels: data.map(row => row.first_name + ' ' + row.last_name),
                    datasets: [{
                        label: 'No-Show Count',
                        data: data.map(row => row.no_show_count),
                        backgroundColor: '#ef4444'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Residents with Multiple No-Shows'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'No-Show Count'
                            }
                        }
                    }
                }
            };
            break;
            
        case '6': // ShelterOccupancy
            chartConfig = {
                type: 'bar',
                data: {
                    labels: data.map(row => row.shelter_name),
                    datasets: [
                        {
                            label: 'Capacity',
                            data: data.map(row => row.capacity),
                            backgroundColor: '#64748b'
                        },
                        {
                            label: 'Current Residents',
                            data: data.map(row => row.current_residents),
                            backgroundColor: '#10b981'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Shelter Capacity vs Current Occupancy'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of People'
                            }
                        }
                    }
                }
            };
            break;
            
        case '10': // DonationTotalsByUser
            chartConfig = {
                type: 'bar',
                data: {
                    labels: data.map(row => row.first_name + ' ' + row.last_name),
                    datasets: [
                        {
                            label: 'Total Donations Count',
                            data: data.map(row => row.total_donations),
                            backgroundColor: '#f59e0b',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Total Money Amount ($)',
                            data: data.map(row => row.total_money_amount),
                            backgroundColor: '#10b981',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Donations by User'
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Donation Count'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount ($)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            };
            break;
    }
    
    if (chartConfig) {
        const ctx = document.getElementById('dataChart').getContext('2d');
        chartInstance = new Chart(ctx, chartConfig);
    }
}

// Populate sort dropdown from table headers
function populateSortDropdown() {
    const table = document.getElementById('viewTable');
    if (!table) return;
    
    const headers = table.getElementsByTagName('th');
    const select = document.getElementById('sortSelect');
    
    // Clear existing options except the first one
    select.innerHTML = '<option value="">-- Select Column --</option>';
    
    for (let i = 0; i < headers.length; i++) {
        const headerText = headers[i].textContent;
        const lowerText = headerText.toLowerCase();
        
        // Determine sort type based on header text
        let sortType = 'string';
        if (lowerText.includes('date')) {
            sortType = 'date';
        } else if (lowerText.includes('id') || lowerText.includes('count') || lowerText.includes('amount') || lowerText.includes('capacity') || lowerText.includes('resident') || lowerText.includes('guest') || lowerText.includes('donation') || lowerText.includes('budget')) {
            sortType = 'number';
        }
        
        const option = document.createElement('option');
        option.value = i + '|' + sortType;
        option.textContent = headerText;
        select.appendChild(option);
    }
}

// Update row count on page load
window.onload = function() {
    updateRowCount('viewTable', 'rowCount');
    populateSortDropdown();
};

// Update row count after search
const originalSearch = searchTable;
searchTable = function(inputId, tableId) {
    originalSearch(inputId, tableId);
    updateRowCount(tableId, 'rowCount');
};
</script>
</body>
</html>