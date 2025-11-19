<!-- RESIDENTS TAB -->
<?php if ($activeTab == 'residents'): ?>
    <h3>Residents</h3>
    
    <?php if (isStaffOrAdmin()): ?>
        <button class="btn btn-add" onclick="openModal('residentModal')">Add New Resident</button>
    <?php endif; ?>

    <!-- Search Filter -->
    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #e2e8f0;">
        <label>Search Residents:</label>
        <input type="text" id="filterResidents" placeholder="Search by name, shelter..." onkeyup="searchTable('filterResidents', 'residentsTable')" style="width: 100%; max-width: 400px;">
    </div>

    <table id="residentsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Shelter</th>
                <th>Bed</th>
                <th>Start Date</th>
                <th>End Date</th>
                <?php if (isStaffOrAdmin()): ?><th>Actions</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($residents as $resident): ?>
                <tr>
                    <td><?php echo $resident['resident_id']; ?></td>
                    <td><?php echo htmlspecialchars($resident['first_name'] . ' ' . $resident['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($resident['shelter_name']); ?></td>
                    <td><?php echo htmlspecialchars($resident['room_label'] ?? 'N/A'); ?></td>
                    <td><?php echo $resident['start_date']; ?></td>
                    <td><?php echo $resident['end_date'] ?? 'N/A'; ?></td>
                    <?php if (isStaffOrAdmin()): ?>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete?');">
                                <input type="hidden" name="action" value="delete_resident">
                                <input type="hidden" name="id" value="<?php echo $resident['resident_id']; ?>">
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- VOLUNTEERS TAB -->
<?php if ($activeTab == 'volunteers'): ?>
    <h3>Volunteers</h3>
    
    <?php if (isStaffOrAdmin()): ?>
        <button class="btn btn-add" onclick="openModal('volunteerModal')">Add New Volunteer</button>
    <?php endif; ?>

    <!-- Search Filter -->
    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #e2e8f0;">
        <label>Search Volunteers:</label>
        <input type="text" id="filterVolunteers" placeholder="Search by name, shelter, task..." onkeyup="searchTable('filterVolunteers', 'volunteersTable')" style="width: 100%; max-width: 400px;">
    </div>

    <table id="volunteersTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Shelter</th>
                <th>Task Area</th>
                <th>Join Date</th>
                <?php if (isStaffOrAdmin()): ?><th>Actions</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($volunteers as $volunteer): ?>
                <tr>
                    <td><?php echo htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($volunteer['shelter_name']); ?></td>
                    <td><?php echo htmlspecialchars($volunteer['task_area'] ?? 'N/A'); ?></td>
                    <td><?php echo $volunteer['join_date'] ?? 'N/A'; ?></td>
                    <?php if (isStaffOrAdmin()): ?>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete?');">
                                <input type="hidden" name="action" value="delete_volunteer">
                                <input type="hidden" name="user_id" value="<?php echo $volunteer['user_id']; ?>">
                                <input type="hidden" name="shelter_id" value="<?php echo $volunteer['shelter_id']; ?>">
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- MEALS TAB -->
<?php if ($activeTab == 'meals'): ?>
    <h3>Meals</h3>
    
    <?php if (isStaffOrAdmin()): ?>
        <button class="btn btn-add" onclick="openModal('mealModal')">Add New Meal</button>
    <?php endif; ?>

    <!-- Search Filter -->
    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #e2e8f0;">
        <label>Search Meals:</label>
        <input type="text" id="filterMeals" placeholder="Search by date, type, shelter..." onkeyup="searchTable('filterMeals', 'mealsTable')" style="width: 100%; max-width: 400px;">
    </div>

    <table id="mealsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Type</th>
                <th>Shelter</th>
                <th>Expected Guests</th>
                <?php if (isStaffOrAdmin()): ?><th>Actions</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($meals as $meal): ?>
                <tr>
                    <td><?php echo $meal['meal_id']; ?></td>
                    <td><?php echo $meal['meal_date']; ?></td>
                    <td><?php echo $meal['meal_type']; ?></td>
                    <td><?php echo htmlspecialchars($meal['shelter_name']); ?></td>
                    <td><?php echo $meal['expected_guests'] ?? 'N/A'; ?></td>
                    <?php if (isStaffOrAdmin()): ?>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete?');">
                                <input type="hidden" name="action" value="delete_meal">
                                <input type="hidden" name="id" value="<?php echo $meal['meal_id']; ?>">
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- DONATIONS TAB -->
<?php if ($activeTab == 'donations'): ?>
    <h3>Donations</h3>
    
    <button class="btn btn-add" onclick="openModal('donationModal')">Add New Donation</button>

    <!-- Search Filter -->
    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #e2e8f0;">
        <label>Search Donations:</label>
        <input type="text" id="filterDonations" placeholder="Search by user, type, date..." onkeyup="searchTable('filterDonations', 'donationsTable')" style="width: 100%; max-width: 400px;">
    </div>

    <table id="donationsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Date</th>
                <?php if (isStaffOrAdmin()): ?><th>Actions</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donations as $donation): ?>
                <tr>
                    <td><?php echo $donation['donation_id']; ?></td>
                    <td><?php echo htmlspecialchars($donation['first_name'] . ' ' . $donation['last_name']); ?></td>
                    <td><?php echo $donation['donation_type']; ?></td>
                    <td><?php echo $donation['amount'] ? '$' . $donation['amount'] : 'N/A'; ?></td>
                    <td><?php echo $donation['donation_date']; ?></td>
                    <?php if (isStaffOrAdmin()): ?>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete?');">
                                <input type="hidden" name="action" value="delete_donation">
                                <input type="hidden" name="id" value="<?php echo $donation['donation_id']; ?>">
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- WAITLIST TAB -->
<?php if ($activeTab == 'waitlist'): ?>
    <h3>Waitlist</h3>
    
    <button class="btn btn-add" onclick="openModal('waitlistModal')">Add New Waitlist Entry</button>

    <!-- Search Filter -->
    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #e2e8f0;">
        <label>Search Waitlist:</label>
        <input type="text" id="filterWaitlist" placeholder="Search by user, status, date..." onkeyup="searchTable('filterWaitlist', 'waitlistTable')" style="width: 100%; max-width: 400px;">
    </div>

    <table id="waitlistTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Request Date</th>
                <th>Status</th>
                <?php if (isStaffOrAdmin()): ?><th>Actions</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($waitlist as $entry): ?>
                <tr>
                    <td><?php echo $entry['waitlist_id']; ?></td>
                    <td><?php echo htmlspecialchars($entry['first_name'] . ' ' . $entry['last_name']); ?></td>
                    <td><?php echo $entry['request_date']; ?></td>
                    <td><?php echo $entry['status']; ?></td>
                    <?php if (isStaffOrAdmin()): ?>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete?');">
                                <input type="hidden" name="action" value="delete_waitlist">
                                <input type="hidden" name="id" value="<?php echo $entry['waitlist_id']; ?>">
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
