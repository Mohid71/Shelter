<!-- RESIDENTS TAB -->
<?php if ($activeTab == 'residents'): ?>
    <h3>Residents</h3>
    
    <?php if (isStaffOrAdmin()): ?>
        <h4>Add New Resident</h4>
        <form method="POST">
            <input type="hidden" name="action" value="create_resident">
            <label>User:</label>
            <select name="user_id" required>
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>">
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label>Shelter:</label>
            <select name="shelter_id" required>
                <option value="">Select Shelter</option>
                <?php foreach ($shelters as $shelter): ?>
                    <option value="<?php echo $shelter['shelter_id']; ?>">
                        <?php echo htmlspecialchars($shelter['shelter_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label>Bed (Optional):</label>
            <select name="bed_id">
                <option value="">No Bed</option>
                <?php foreach ($beds as $bed): ?>
                    <option value="<?php echo $bed['bed_id']; ?>">
                        <?php echo htmlspecialchars($bed['shelter_name'] . ' - ' . $bed['room_label']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label>Start Date:</label>
            <input type="date" name="start_date" required>
            
            <label>End Date (Optional):</label>
            <input type="date" name="end_date">
            
            <label>Dietary Restrictions:</label>
            <input type="text" name="dietary_restrictions">
            
            <label>Allergies:</label>
            <input type="text" name="allergies">
            
            <button type="submit">Add Resident</button>
        </form>
    <?php endif; ?>

    <h4>All Residents</h4>
    <table>
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
        <h4>Add New Volunteer</h4>
        <form method="POST">
            <input type="hidden" name="action" value="create_volunteer">
            <label>User:</label>
            <select name="user_id" required>
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>">
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label>Shelter:</label>
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
            <input type="date" name="join_date">
            
            <button type="submit">Add Volunteer</button>
        </form>
    <?php endif; ?>

    <h4>All Volunteers</h4>
    <table>
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
        <h4>Add New Meal</h4>
        <form method="POST">
            <input type="hidden" name="action" value="create_meal">
            <label>Meal Date:</label>
            <input type="date" name="meal_date" required>
            
            <label>Meal Type:</label>
            <select name="meal_type" required>
                <option value="">Select Type</option>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
            </select>
            
            <label>Shelter:</label>
            <select name="shelter_id" required>
                <option value="">Select Shelter</option>
                <?php foreach ($shelters as $shelter): ?>
                    <option value="<?php echo $shelter['shelter_id']; ?>">
                        <?php echo htmlspecialchars($shelter['shelter_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label>Expected Guests:</label>
            <input type="number" name="expected_guests" min="0">
            
            <label>Notes:</label>
            <input type="text" name="notes">
            
            <button type="submit">Add Meal</button>
        </form>
    <?php endif; ?>

    <h4>All Meals</h4>
    <table>
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
    
    <h4>Add New Donation</h4>
    <form method="POST">
        <input type="hidden" name="action" value="create_donation">
        <label>User:</label>
        <select name="user_id" required>
            <option value="">Select User</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['user_id']; ?>">
                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label>Donation Type:</label>
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
        <input type="text" name="in_kind_details">
        
        <label>Donation Date:</label>
        <input type="date" name="donation_date" required>
        
        <button type="submit">Add Donation</button>
    </form>

    <h4>All Donations</h4>
    <table>
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
    
    <h4>Add New Waitlist Entry</h4>
    <form method="POST">
        <input type="hidden" name="action" value="create_waitlist">
        <label>User:</label>
        <select name="user_id" required>
            <option value="">Select User</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['user_id']; ?>">
                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label>Status:</label>
        <select name="status" required>
            <option value="Waiting">Waiting</option>
            <option value="Offered">Offered</option>
            <option value="Accepted">Accepted</option>
            <option value="Cancelled">Cancelled</option>
            <label>Request Date: *</label>
            <input type="date" name="request_date" required>
            
            <label>Notes:</label>      <input type="text" name="notes">
        
        <button type="submit">Add to Waitlist</button>
    </form>

    <h4>All Waitlist Entries</h4>
    <table>
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
