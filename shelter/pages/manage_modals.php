<!-- Resident Modal -->
<div id="residentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('residentModal')">&times;</span>
        <h3>Add New Resident</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create_resident">
            <label>User: *</label>
            <select name="user_id" required>
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>">
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label>Shelter: *</label>
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
            
            <label>Start Date: *</label>
            <input type="date" name="start_date" required>
            
            <label>End Date (Optional):</label>
            <input type="date" name="end_date">
            
            <label>Dietary Restrictions:</label>
            <input type="text" name="dietary_restrictions">
            
            <label>Allergies:</label>
            <input type="text" name="allergies">
            
            <button type="submit">Add Resident</button>
        </form>
    </div>
</div>

<!-- Volunteer Modal -->
<div id="volunteerModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('volunteerModal')">&times;</span>
        <h3>Add New Volunteer</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create_volunteer">
            <label>User: *</label>
            <select name="user_id" required>
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>">
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
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
            <input type="date" name="join_date">
            
            <button type="submit">Add Volunteer</button>
        </form>
    </div>
</div>

<!-- Meal Modal -->
<div id="mealModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('mealModal')">&times;</span>
        <h3>Add New Meal</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create_meal">
            <label>Meal Date: *</label>
            <input type="date" name="meal_date" required>
            
            <label>Meal Type: *</label>
            <select name="meal_type" required>
                <option value="">Select Type</option>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
            </select>
            
            <label>Shelter: *</label>
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
    </div>
</div>

<!-- Donation Modal -->
<div id="donationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('donationModal')">&times;</span>
        <h3>Add New Donation</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create_donation">
            <label>User: *</label>
            <select name="user_id" required>
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>">
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
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
            <input type="text" name="in_kind_details">
            
            <label>Donation Date: *</label>
            <input type="date" name="donation_date" required>
            
            <button type="submit">Add Donation</button>
        </form>
    </div>
</div>

<!-- Waitlist Modal -->
<div id="waitlistModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('waitlistModal')">&times;</span>
        <h3>Add New Waitlist Entry</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create_waitlist">
            <label>User: *</label>
            <select name="user_id" required>
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>">
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label>Status: *</label>
            <select name="status" required>
                <option value="Waiting">Waiting</option>
                <option value="Offered">Offered</option>
                <option value="Accepted">Accepted</option>
                <option value="Cancelled">Cancelled</option>
            </select>
            
            <label>Request Date: *</label>
            <input type="date" name="request_date" required>
            
            <label>Notes:</label>
            <input type="text" name="notes">
            
            <button type="submit">Add to Waitlist</button>
        </form>
    </div>
</div>
