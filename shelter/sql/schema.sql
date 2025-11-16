-- Shelter Management System Database Schema
-- Drop existing database and create fresh
DROP DATABASE IF EXISTS shelter_db;
CREATE DATABASE shelter_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shelter_db;

-- 1. Users Table
CREATE TABLE Users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL UNIQUE,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  date_of_birth DATE,
  password VARCHAR(255) NOT NULL,
  role ENUM('Admin','Staff','Volunteer','Other') NOT NULL DEFAULT 'Other',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Shelters Table
CREATE TABLE Shelters (
  shelter_id INT PRIMARY KEY AUTO_INCREMENT,
  shelter_name VARCHAR(100) NOT NULL,
  address VARCHAR(150),
  city VARCHAR(50),
  telephone VARCHAR(20),
  budget INT DEFAULT 0,
  capacity INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Beds Table
CREATE TABLE Beds (
  bed_id INT PRIMARY KEY AUTO_INCREMENT,
  room_label VARCHAR(20) NOT NULL,
  notes VARCHAR(200),
  shelter_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (shelter_id) REFERENCES Shelters(shelter_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Residents Table
CREATE TABLE Residents (
  resident_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL UNIQUE,
  shelter_id INT NOT NULL,
  bed_id INT,
  start_date DATE NOT NULL,
  end_date DATE NULL,
  dietary_restrictions VARCHAR(200),
  allergies VARCHAR(200),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (shelter_id) REFERENCES Shelters(shelter_id) ON DELETE CASCADE,
  FOREIGN KEY (bed_id) REFERENCES Beds(bed_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. Volunteers Table
CREATE TABLE Volunteers (
  user_id INT NOT NULL,
  shelter_id INT NOT NULL,
  task_area ENUM('Kitchen','FrontDesk','Cleaning','General') NULL,
  join_date DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, shelter_id),
  FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (shelter_id) REFERENCES Shelters(shelter_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Meals Table
CREATE TABLE Meals (
  meal_id INT PRIMARY KEY AUTO_INCREMENT,
  meal_date DATE NOT NULL,
  meal_type ENUM('Breakfast','Lunch','Dinner') NOT NULL,
  notes VARCHAR(200),
  expected_guests INT,
  shelter_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_meal (meal_date, meal_type, shelter_id),
  CHECK (expected_guests IS NULL OR expected_guests >= 0),
  FOREIGN KEY (shelter_id) REFERENCES Shelters(shelter_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. MealAttendance Table
CREATE TABLE MealAttendance (
  meal_id INT NOT NULL,
  resident_id INT NOT NULL,
  status ENUM('Served','Declined','NoShow') NOT NULL DEFAULT 'Served',
  restriction_met ENUM('Yes','No','N/A') NOT NULL DEFAULT 'N/A',
  notes VARCHAR(200),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (meal_id, resident_id),
  FOREIGN KEY (meal_id) REFERENCES Meals(meal_id) ON DELETE CASCADE,
  FOREIGN KEY (resident_id) REFERENCES Residents(resident_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. Donations Table
CREATE TABLE Donations (
  donation_id INT PRIMARY KEY AUTO_INCREMENT,
  donation_type ENUM('Money','Food','Clothing','Other') NOT NULL,
  amount INT NULL,
  in_kind_details VARCHAR(200),
  donation_date DATE NOT NULL,
  user_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CHECK (
    (donation_type = 'Money' AND amount IS NOT NULL AND amount > 0)
    OR (donation_type <> 'Money')
  ),
  FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. Waitlist Table
CREATE TABLE Waitlist (
  waitlist_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  expected_start_date DATE,
  request_date DATE NOT NULL,
  status ENUM('Waiting','Offered','Accepted','Cancelled') DEFAULT 'Waiting',
  notes VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- CREATE VIEWS
-- ============================================

-- View 1: ResidentMealDetails
CREATE VIEW ResidentMealDetails AS
SELECT
  r.resident_id,
  u.first_name,
  u.last_name,
  s.shelter_name,
  m.meal_date,
  m.meal_type,
  ma.status,
  ma.restriction_met
FROM MealAttendance ma
JOIN Residents r ON ma.resident_id = r.resident_id
JOIN Users u ON r.user_id = u.user_id
JOIN Meals m ON ma.meal_id = m.meal_id
JOIN Shelters s ON m.shelter_id = s.shelter_id;

-- View 2: SheltersAboveCityMoneyDonation
CREATE VIEW SheltersAboveCityMoneyDonation AS
SELECT
  s.shelter_id,
  s.shelter_name,
  s.city,
  SUM(d.amount) AS total_money_donations
FROM Shelters s
JOIN Residents r ON r.shelter_id = s.shelter_id
JOIN Donations d ON d.user_id = r.user_id AND d.donation_type = 'Money'
GROUP BY s.shelter_id, s.shelter_name, s.city
HAVING SUM(d.amount) > ANY (
  SELECT SUM(d2.amount)
  FROM Shelters s2
  JOIN Residents r2 ON r2.shelter_id = s2.shelter_id
  JOIN Donations d2 ON d2.user_id = r2.user_id AND d2.donation_type = 'Money'
  WHERE s2.city = s.city
  GROUP BY s2.shelter_id
);

-- View 3: ResidentsWithManyNoShows
CREATE VIEW ResidentsWithManyNoShows AS
SELECT
  r.resident_id,
  u.first_name,
  u.last_name,
  r.shelter_id,
  (
    SELECT COUNT(*)
    FROM MealAttendance ma
    WHERE ma.resident_id = r.resident_id AND ma.status = 'NoShow'
  ) AS no_show_count
FROM Residents r
JOIN Users u ON r.user_id = u.user_id
WHERE (
  SELECT COUNT(*)
  FROM MealAttendance ma
  WHERE ma.resident_id = r.resident_id AND ma.status = 'NoShow'
) >= 3;

-- View 4: ResidentAndWaitlistStatus
CREATE VIEW ResidentAndWaitlistStatus AS
SELECT
  r.resident_id AS record_id,
  u.user_id,
  u.first_name,
  u.last_name,
  r.shelter_id,
  r.start_date,
  r.end_date,
  w.status AS waitlist_status
FROM Residents r
LEFT JOIN Waitlist w ON r.user_id = w.user_id
JOIN Users u ON u.user_id = r.user_id

UNION

SELECT
  w.waitlist_id AS record_id,
  u.user_id,
  u.first_name,
  u.last_name,
  NULL AS shelter_id,
  NULL AS start_date,
  NULL AS end_date,
  w.status AS waitlist_status
FROM Waitlist w
LEFT JOIN Residents r ON w.user_id = r.user_id
JOIN Users u ON u.user_id = w.user_id
WHERE r.user_id IS NULL;

-- View 5: ResidentVolunteers
CREATE VIEW ResidentVolunteers AS
SELECT
  u.user_id,
  u.first_name,
  u.last_name
FROM Users u
WHERE u.user_id IN (
  SELECT r.user_id FROM Residents r
)
AND u.user_id IN (
  SELECT v.user_id FROM Volunteers v
);

-- View 6: ShelterOccupancy
CREATE VIEW ShelterOccupancy AS
SELECT
  s.shelter_id,
  s.shelter_name,
  s.capacity,
  COUNT(r.resident_id) AS current_residents
FROM Shelters s
LEFT JOIN Residents r ON r.shelter_id = s.shelter_id
  AND (r.end_date IS NULL OR r.end_date >= CURRENT_DATE)
GROUP BY s.shelter_id, s.shelter_name, s.capacity;

-- View 7: MealsByShelter
CREATE VIEW MealsByShelter AS
SELECT
  m.meal_id,
  m.meal_date,
  m.meal_type,
  m.expected_guests,
  s.shelter_name
FROM Meals m
JOIN Shelters s ON m.shelter_id = s.shelter_id;

-- View 8: ResidentBasicInfo
CREATE VIEW ResidentBasicInfo AS
SELECT
  r.resident_id,
  u.first_name,
  u.last_name,
  s.shelter_name,
  r.start_date,
  r.end_date
FROM Residents r
JOIN Users u ON r.user_id = u.user_id
JOIN Shelters s ON r.shelter_id = s.shelter_id;

-- View 9: VolunteerBasicInfo
CREATE VIEW VolunteerBasicInfo AS
SELECT
  v.shelter_id,
  s.shelter_name,
  u.user_id,
  u.first_name,
  u.last_name,
  v.task_area,
  v.join_date
FROM Volunteers v
JOIN Shelters s ON v.shelter_id = s.shelter_id
JOIN Users u ON v.user_id = u.user_id;

-- View 10: DonationTotalsByUser
CREATE VIEW DonationTotalsByUser AS
SELECT
  u.user_id,
  u.first_name,
  u.last_name,
  COUNT(d.donation_id) AS total_donations,
  SUM(COALESCE(d.amount, 0)) AS total_money_amount
FROM Users u
JOIN Donations d ON d.user_id = u.user_id
GROUP BY u.user_id, u.first_name, u.last_name;
