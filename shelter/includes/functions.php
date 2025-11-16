<?php
/**
 * Helper Functions
 * Database query functions for all tables and views
 */

require_once __DIR__ . '/../config/database.php';

// ============ VIEW FUNCTIONS ============

function getResidentMealDetails() {
    $query = "SELECT * FROM ResidentMealDetails ORDER BY last_name, first_name, meal_date";
    return fetchAll($query);
}

function getSheltersAboveCityMoneyDonation() {
    $query = "SELECT * FROM SheltersAboveCityMoneyDonation ORDER BY city, total_money_donations DESC";
    return fetchAll($query);
}

function getResidentsWithManyNoShows() {
    $query = "SELECT * FROM ResidentsWithManyNoShows ORDER BY no_show_count DESC, last_name";
    return fetchAll($query);
}

function getResidentAndWaitlistStatus() {
    $query = "SELECT * FROM ResidentAndWaitlistStatus ORDER BY last_name, first_name";
    return fetchAll($query);
}

function getResidentVolunteers() {
    $query = "SELECT * FROM ResidentVolunteers ORDER BY last_name, first_name";
    return fetchAll($query);
}

function getShelterOccupancy() {
    $query = "SELECT * FROM ShelterOccupancy ORDER BY shelter_name";
    return fetchAll($query);
}

function getMealsByShelter() {
    $query = "SELECT * FROM MealsByShelter ORDER BY shelter_name, meal_date DESC, meal_type";
    return fetchAll($query);
}

function getResidentBasicInfo() {
    $query = "SELECT * FROM ResidentBasicInfo ORDER BY shelter_name, last_name, first_name";
    return fetchAll($query);
}

function getVolunteerBasicInfo() {
    $query = "SELECT * FROM VolunteerBasicInfo ORDER BY shelter_name, last_name, first_name";
    return fetchAll($query);
}

function getDonationTotalsByUser() {
    $query = "SELECT * FROM DonationTotalsByUser ORDER BY total_money_amount DESC, last_name";
    return fetchAll($query);
}

// ============ TABLE CRUD FUNCTIONS ============

// SHELTERS
function getAllShelters() {
    return fetchAll("SELECT * FROM Shelters ORDER BY shelter_name");
}

function getShelterById($id) {
    return fetchOne("SELECT * FROM Shelters WHERE shelter_id = ?", [$id]);
}

function createShelter($data) {
    $query = "INSERT INTO Shelters (shelter_name, address, city, telephone, budget, capacity) 
              VALUES (?, ?, ?, ?, ?, ?)";
    return executeQuery($query, [
        $data['shelter_name'],
        $data['address'],
        $data['city'],
        $data['telephone'],
        $data['budget'],
        $data['capacity']
    ]);
}

function updateShelter($id, $data) {
    $query = "UPDATE Shelters SET shelter_name = ?, address = ?, city = ?, 
              telephone = ?, budget = ?, capacity = ? WHERE shelter_id = ?";
    return executeQuery($query, [
        $data['shelter_name'],
        $data['address'],
        $data['city'],
        $data['telephone'],
        $data['budget'],
        $data['capacity'],
        $id
    ]);
}

function deleteShelter($id) {
    return executeQuery("DELETE FROM Shelters WHERE shelter_id = ?", [$id]);
}

// USERS
function getAllUsers() {
    return fetchAll("SELECT user_id, email, first_name, last_name, date_of_birth, role FROM Users ORDER BY last_name, first_name");
}

function getUserById($id) {
    return fetchOne("SELECT user_id, email, first_name, last_name, date_of_birth, role FROM Users WHERE user_id = ?", [$id]);
}

// BEDS
function getAllBeds() {
    return fetchAll("SELECT b.*, s.shelter_name FROM Beds b JOIN Shelters s ON b.shelter_id = s.shelter_id ORDER BY s.shelter_name, b.room_label");
}

function getBedById($id) {
    return fetchOne("SELECT b.*, s.shelter_name FROM Beds b JOIN Shelters s ON b.shelter_id = s.shelter_id WHERE b.bed_id = ?", [$id]);
}

function createBed($data) {
    $query = "INSERT INTO Beds (room_label, notes, shelter_id) VALUES (?, ?, ?)";
    return executeQuery($query, [$data['room_label'], $data['notes'], $data['shelter_id']]);
}

function updateBed($id, $data) {
    $query = "UPDATE Beds SET room_label = ?, notes = ?, shelter_id = ? WHERE bed_id = ?";
    return executeQuery($query, [$data['room_label'], $data['notes'], $data['shelter_id'], $id]);
}

function deleteBed($id) {
    return executeQuery("DELETE FROM Beds WHERE bed_id = ?", [$id]);
}

// RESIDENTS
function getAllResidents() {
    $query = "SELECT r.*, u.first_name, u.last_name, u.email, s.shelter_name, b.room_label 
              FROM Residents r 
              JOIN Users u ON r.user_id = u.user_id 
              JOIN Shelters s ON r.shelter_id = s.shelter_id 
              LEFT JOIN Beds b ON r.bed_id = b.bed_id 
              ORDER BY s.shelter_name, u.last_name";
    return fetchAll($query);
}

function getResidentById($id) {
    $query = "SELECT r.*, u.first_name, u.last_name, u.email, s.shelter_name, b.room_label 
              FROM Residents r 
              JOIN Users u ON r.user_id = u.user_id 
              JOIN Shelters s ON r.shelter_id = s.shelter_id 
              LEFT JOIN Beds b ON r.bed_id = b.bed_id 
              WHERE r.resident_id = ?";
    return fetchOne($query, [$id]);
}

function createResident($data) {
    $query = "INSERT INTO Residents (user_id, shelter_id, bed_id, start_date, end_date, dietary_restrictions, allergies) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    return executeQuery($query, [
        $data['user_id'],
        $data['shelter_id'],
        $data['bed_id'] ?: null,
        $data['start_date'],
        $data['end_date'] ?: null,
        $data['dietary_restrictions'],
        $data['allergies']
    ]);
}

function updateResident($id, $data) {
    $query = "UPDATE Residents SET user_id = ?, shelter_id = ?, bed_id = ?, start_date = ?, 
              end_date = ?, dietary_restrictions = ?, allergies = ? WHERE resident_id = ?";
    return executeQuery($query, [
        $data['user_id'],
        $data['shelter_id'],
        $data['bed_id'] ?: null,
        $data['start_date'],
        $data['end_date'] ?: null,
        $data['dietary_restrictions'],
        $data['allergies'],
        $id
    ]);
}

function deleteResident($id) {
    return executeQuery("DELETE FROM Residents WHERE resident_id = ?", [$id]);
}

// VOLUNTEERS
function getAllVolunteers() {
    $query = "SELECT v.*, u.first_name, u.last_name, u.email, s.shelter_name 
              FROM Volunteers v 
              JOIN Users u ON v.user_id = u.user_id 
              JOIN Shelters s ON v.shelter_id = s.shelter_id 
              ORDER BY s.shelter_name, u.last_name";
    return fetchAll($query);
}

function createVolunteer($data) {
    $query = "INSERT INTO Volunteers (user_id, shelter_id, task_area, join_date) VALUES (?, ?, ?, ?)";
    return executeQuery($query, [$data['user_id'], $data['shelter_id'], $data['task_area'], $data['join_date']]);
}

function deleteVolunteer($userId, $shelterId) {
    return executeQuery("DELETE FROM Volunteers WHERE user_id = ? AND shelter_id = ?", [$userId, $shelterId]);
}

// MEALS
function getAllMeals() {
    $query = "SELECT m.*, s.shelter_name FROM Meals m JOIN Shelters s ON m.shelter_id = s.shelter_id ORDER BY m.meal_date DESC, m.meal_type";
    return fetchAll($query);
}

function getMealById($id) {
    $query = "SELECT m.*, s.shelter_name FROM Meals m JOIN Shelters s ON m.shelter_id = s.shelter_id WHERE m.meal_id = ?";
    return fetchOne($query, [$id]);
}

function createMeal($data) {
    $query = "INSERT INTO Meals (meal_date, meal_type, notes, expected_guests, shelter_id) VALUES (?, ?, ?, ?, ?)";
    return executeQuery($query, [$data['meal_date'], $data['meal_type'], $data['notes'], $data['expected_guests'], $data['shelter_id']]);
}

function updateMeal($id, $data) {
    $query = "UPDATE Meals SET meal_date = ?, meal_type = ?, notes = ?, expected_guests = ?, shelter_id = ? WHERE meal_id = ?";
    return executeQuery($query, [$data['meal_date'], $data['meal_type'], $data['notes'], $data['expected_guests'], $data['shelter_id'], $id]);
}

function deleteMeal($id) {
    return executeQuery("DELETE FROM Meals WHERE meal_id = ?", [$id]);
}

// MEAL ATTENDANCE
function getAllMealAttendance() {
    $query = "SELECT ma.*, m.meal_date, m.meal_type, s.shelter_name, 
              u.first_name, u.last_name, r.resident_id
              FROM MealAttendance ma 
              JOIN Meals m ON ma.meal_id = m.meal_id 
              JOIN Shelters s ON m.shelter_id = s.shelter_id
              JOIN Residents r ON ma.resident_id = r.resident_id
              JOIN Users u ON r.user_id = u.user_id
              ORDER BY m.meal_date DESC, u.last_name";
    return fetchAll($query);
}

function createMealAttendance($data) {
    $query = "INSERT INTO MealAttendance (meal_id, resident_id, status, restriction_met, notes) VALUES (?, ?, ?, ?, ?)";
    return executeQuery($query, [$data['meal_id'], $data['resident_id'], $data['status'], $data['restriction_met'], $data['notes']]);
}

function deleteMealAttendance($mealId, $residentId) {
    return executeQuery("DELETE FROM MealAttendance WHERE meal_id = ? AND resident_id = ?", [$mealId, $residentId]);
}

// DONATIONS
function getAllDonations() {
    $query = "SELECT d.*, u.first_name, u.last_name, u.email 
              FROM Donations d 
              JOIN Users u ON d.user_id = u.user_id 
              ORDER BY d.donation_date DESC";
    return fetchAll($query);
}

function getDonationById($id) {
    $query = "SELECT d.*, u.first_name, u.last_name, u.email 
              FROM Donations d 
              JOIN Users u ON d.user_id = u.user_id 
              WHERE d.donation_id = ?";
    return fetchOne($query, [$id]);
}

function createDonation($data) {
    $query = "INSERT INTO Donations (donation_type, amount, in_kind_details, donation_date, user_id) VALUES (?, ?, ?, ?, ?)";
    return executeQuery($query, [
        $data['donation_type'],
        $data['amount'] ?: null,
        $data['in_kind_details'],
        $data['donation_date'],
        $data['user_id']
    ]);
}

function updateDonation($id, $data) {
    $query = "UPDATE Donations SET donation_type = ?, amount = ?, in_kind_details = ?, donation_date = ?, user_id = ? WHERE donation_id = ?";
    return executeQuery($query, [
        $data['donation_type'],
        $data['amount'] ?: null,
        $data['in_kind_details'],
        $data['donation_date'],
        $data['user_id'],
        $id
    ]);
}

function deleteDonation($id) {
    return executeQuery("DELETE FROM Donations WHERE donation_id = ?", [$id]);
}

// WAITLIST
function getAllWaitlist() {
    $query = "SELECT w.*, u.first_name, u.last_name, u.email 
              FROM Waitlist w 
              JOIN Users u ON w.user_id = u.user_id 
              ORDER BY w.request_date DESC";
    return fetchAll($query);
}

function getWaitlistById($id) {
    $query = "SELECT w.*, u.first_name, u.last_name, u.email 
              FROM Waitlist w 
              JOIN Users u ON w.user_id = u.user_id 
              WHERE w.waitlist_id = ?";
    return fetchOne($query, [$id]);
}

function createWaitlistEntry($data) {
    $query = "INSERT INTO Waitlist (user_id, expected_start_date, request_date, status, notes) VALUES (?, ?, ?, ?, ?)";
    return executeQuery($query, [
        $data['user_id'],
        $data['expected_start_date'] ?: null,
        $data['request_date'],
        $data['status'],
        $data['notes']
    ]);
}

function updateWaitlistEntry($id, $data) {
    $query = "UPDATE Waitlist SET user_id = ?, expected_start_date = ?, request_date = ?, status = ?, notes = ? WHERE waitlist_id = ?";
    return executeQuery($query, [
        $data['user_id'],
        $data['expected_start_date'] ?: null,
        $data['request_date'],
        $data['status'],
        $data['notes'],
        $id
    ]);
}

function deleteWaitlistEntry($id) {
    return executeQuery("DELETE FROM Waitlist WHERE waitlist_id = ?", [$id]);
}

// ============ UTILITY FUNCTIONS ============

function formatDate($date) {
    if (!$date) return 'N/A';
    return date('M d, Y', strtotime($date));
}

function formatCurrency($amount) {
    if ($amount === null) return 'N/A';
    return '$' . number_format($amount, 2);
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
