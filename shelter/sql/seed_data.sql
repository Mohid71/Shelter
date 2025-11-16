-- Sample Data for Shelter Management System
USE shelter_db;

-- Insert Users (password is 'password123' hashed with PASSWORD())
INSERT INTO Users (user_id, email, first_name, last_name, date_of_birth, password, role) VALUES
(1, 'mohd.sohail@example.com', 'Mohd', 'Sohail', '2001-05-10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Volunteer'),
(2, 'hanzlah.imran@example.com', 'Hanzlah', 'Imran', '2001-07-15', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Volunteer'),
(3, 'sahil.asifi@example.com', 'Sahil', 'Asifi', '2002-02-20', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staff'),
(4, 'aditya.ramjas@example.com', 'Aditya', 'Ramjas', '2001-11-30', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin'),
(5, 'mo.khan@example.com', 'Mo', 'Khan', '2000-09-05', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other'),
(6, 'babar.azam@example.com', 'Babar', 'Azam', '1998-10-15', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other'),
(7, 'ali.raza@example.com', 'Ali', 'Raza', '1999-08-14', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other'),
(8, 'mariam.khan@example.com', 'Mariam', 'Khan', '2002-12-01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other'),
(9, 'ahmed.saeed@example.com', 'Ahmed', 'Saeed', '1998-06-27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other'),
(10, 'sana.naqvi@example.com', 'Sana', 'Naqvi', '2000-04-03', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other'),
(11, 'zain.abid@example.com', 'Zain', 'Abid', '2001-09-09', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other'),
(12, 'areeba.noor@example.com', 'Areeba', 'Noor', '2002-03-15', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other'),
(13, 'talha.mujeed@example.com', 'Talha', 'Mujeed', '1999-12-20', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other'),
(14, 'fatima.sohail@example.com', 'Fatima', 'Sohail', '2003-01-25', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other'),
(15, 'haseeb.javed@example.com', 'Haseeb', 'Javed', '2000-07-30', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Other');

-- Insert Shelters
INSERT INTO Shelters (shelter_id, shelter_name, address, city, telephone, budget, capacity) VALUES
(1, 'Downtown Shelter', '123 King St', 'Toronto', '416-111-1111', 150000, 50),
(2, 'East End Shelter', '45 Queen St E', 'Toronto', '416-222-2222', 120000, 40),
(3, 'Westside Shelter', '789 Dundas St W', 'Toronto', '416-333-3333', 2000, 30),
(4, 'North Haven Shelter', '12 Finch Ave N', 'Toronto', '416-444-4444', 500, 25),
(5, 'Lakeside Shelter', '77 Lake Shore Rd', 'Toronto', '416-555-5555', 3000, 35),
(6, 'Midtown Shelter', '250 Bloor St W', 'Toronto', '416-666-6666', 130000, 45);

-- Insert Beds
INSERT INTO Beds (bed_id, room_label, notes, shelter_id) VALUES
(1, '101A', 'Lower bunk near window', 1),
(2, '101B', 'Upper bunk near window', 1),
(3, '102A', 'Single bed in quiet corner', 1),
(4, '103A', 'Single bed near washroom', 1),
(5, '201A', 'Lower bunk near hallway', 2),
(6, '201B', 'Upper bunk near hallway', 2),
(7, '202A', 'Single bed near exit', 2),
(8, '202B', 'Single bed near common room', 2);

-- Insert Residents
INSERT INTO Residents (resident_id, user_id, shelter_id, bed_id, start_date, end_date, dietary_restrictions, allergies) VALUES
(1, 5, 1, 1, '2025-11-01', NULL, 'None', 'None'),
(2, 6, 1, 2, '2025-11-02', '2025-11-18', 'None', 'Dairy'),
(3, 7, 1, 3, '2025-11-05', NULL, 'Halal', 'None'),
(4, 8, 1, 4, '2025-11-07', NULL, 'Vegetarian', 'None'),
(5, 9, 2, 6, '2025-11-09', NULL, 'None', 'Gluten'),
(6, 10, 2, 7, '2025-11-10', '2025-11-20', 'None', 'Peanuts'),
(7, 12, 2, 8, '2025-11-12', NULL, 'Halal; Vegetarian', 'None'),
(8, 13, 2, 5, '2025-11-15', NULL, 'None', 'None');

-- Insert Volunteers
INSERT INTO Volunteers (user_id, shelter_id, task_area, join_date) VALUES
(1, 1, 'Kitchen', '2025-10-01'),
(2, 1, 'Cleaning', '2025-10-05'),
(3, 2, 'Kitchen', '2025-11-01'),
(4, 2, 'FrontDesk', '2025-11-02'),
(5, 1, 'General', '2025-10-20'),
(6, 2, 'General', '2025-11-05'),
(11, 1, 'General', '2025-11-08'),
(14, 2, 'Cleaning', '2025-11-09');

-- Insert Meals
INSERT INTO Meals (meal_id, meal_date, meal_type, notes, expected_guests, shelter_id) VALUES
(1, '2025-11-10', 'Breakfast', 'Scrambled eggs & toast', 4, 1),
(2, '2025-11-10', 'Dinner', 'Chicken curry & rice', 4, 1),
(3, '2025-11-11', 'Breakfast', 'Oatmeal & fruit', 4, 1),
(4, '2025-11-12', 'Dinner', 'Pasta with cream sauce', 4, 1),
(5, '2025-11-11', 'Breakfast', 'Parathas & chai', 2, 2),
(6, '2025-11-11', 'Dinner', 'Rice & lentils', 2, 2),
(7, '2025-11-12', 'Breakfast', 'Sandwiches & juice', 3, 2),
(8, '2025-11-15', 'Dinner', 'Mixed rice platter (no pork)', 4, 2);

-- Insert MealAttendance
INSERT INTO MealAttendance (meal_id, resident_id, status, restriction_met, notes) VALUES
(1, 1, 'Served', 'Yes', 'Standard breakfast, no restrictions'),
(1, 2, 'Served', 'No', 'Milk in tea despite dairy allergy'),
(1, 3, 'Served', 'Yes', 'Halal-friendly meal'),
(1, 4, 'Served', 'Yes', 'Vegetarian option chosen'),
(2, 1, 'Served', 'Yes', 'No restrictions'),
(2, 2, 'Declined', 'Yes', 'Not hungry'),
(2, 3, 'Served', 'Yes', 'Halal chicken used'),
(2, 4, 'Served', 'No', 'Chicken dish, not vegetarian'),
(3, 1, 'Served', 'Yes', 'Ate full oatmeal bowl'),
(3, 2, 'Served', 'Yes', 'No dairy added'),
(3, 3, 'Served', 'Yes', 'Halal-safe meal'),
(3, 4, 'NoShow', 'N/A', 'Did not come down'),
(4, 1, 'Served', 'Yes', 'No issues reported'),
(4, 2, 'Served', 'No', 'Cream sauce contains dairy'),
(4, 3, 'Served', 'Yes', 'Halal vegetarian portion'),
(4, 4, 'Served', 'Yes', 'Pasta without meat'),
(5, 5, 'Served', 'No', 'Parathas may contain gluten'),
(5, 6, 'Served', 'Yes', 'No peanuts present'),
(6, 5, 'Served', 'Yes', 'Rice & lentils safe for gluten'),
(6, 6, 'Served', 'Yes', 'No peanuts in ingredients'),
(7, 5, 'Served', 'No', 'Bread may contain gluten'),
(7, 6, 'NoShow', 'N/A', 'Missed breakfast'),
(7, 7, 'Served', 'No', 'Sandwich not fully vegetarian'),
(8, 5, 'Served', 'Yes', 'Rice-based dish, no gluten issue'),
(8, 6, 'Served', 'Yes', 'No peanuts used'),
(8, 7, 'Served', 'Yes', 'Vegetarian portion, Halal-friendly'),
(8, 8, 'Served', 'Yes', 'No restrictions reported');

-- Insert Donations
INSERT INTO Donations (donation_id, donation_type, amount, in_kind_details, donation_date, user_id) VALUES
(1, 'Money', 200, NULL, '2025-11-01', 5),
(2, 'Food', NULL, 'Canned goods (24 pcs)', '2025-11-02', 6),
(3, 'Clothing', NULL, 'Winter jackets (8)', '2025-11-03', 7),
(4, 'Money', 75, NULL, '2025-11-04', 8),
(5, 'Other', NULL, 'Blankets (15)', '2025-11-05', 9),
(6, 'Money', 150, NULL, '2025-11-06', 10),
(7, 'Food', NULL, 'Restaurant trays (5)', '2025-11-07', 12),
(8, 'Clothing', NULL, 'Shoes (12 pairs)', '2025-11-08', 13);

-- Insert Waitlist
INSERT INTO Waitlist (waitlist_id, user_id, expected_start_date, request_date, status, notes) VALUES
(1, 13, '2025-11-15', '2025-10-25', 'Accepted', 'Offered bed at East End Shelter; moved into ro...'),
(2, 11, '2025-11-18', '2025-10-27', 'Waiting', 'Prefers Downtown Shelter'),
(3, 12, '2025-11-19', '2025-10-28', 'Waiting', 'Flexible on location'),
(4, 14, '2025-11-21', '2025-10-29', 'Waiting', 'Needs access to transit'),
(5, 15, '2025-11-23', '2025-10-30', 'Waiting', 'Referred by community worker'),
(6, 11, '2025-11-25', '2025-11-02', 'Waiting', 'Updated request for later date'),
(7, 12, '2025-11-27', '2025-11-03', 'Waiting', 'Still interested, can wait'),
(8, 15, '2025-11-29', '2025-11-04', 'Cancelled', 'Found alternative housing');
