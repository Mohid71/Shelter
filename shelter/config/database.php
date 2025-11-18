<?php
/**
 * Database Configuration
 * 
 * Update these settings to match your WAMP MySQL configuration
 */

// Database connection settings
define('DB_HOST', 'localhost');        // Usually 'localhost' for WAMP
define('DB_USER', 'root');             // Default WAMP username
define('DB_PASS', 'BabarAzam56');                 // Default WAMP password (empty)
define('DB_NAME', 'shelter');       // Database name

// Create database connection
function getDbConnection() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            // Set charset to utf8mb4 for full Unicode support
            $conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }
    
    return $conn;
}

// Helper function to execute queries safely
function executeQuery($query, $params = []) {
    $conn = getDbConnection();
    
    if (empty($params)) {
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Query failed: " . $conn->error);
        }
        return $result;
    }
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    if (!empty($params)) {
        $types = '';
        $values = [];
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
            $values[] = $param;
        }
        $stmt->bind_param($types, ...$values);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    return $result;
}

// Helper function to fetch all rows
function fetchAll($query, $params = []) {
    $result = executeQuery($query, $params);
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

// Helper function to fetch single row
function fetchOne($query, $params = []) {
    $result = executeQuery($query, $params);
    return $result->fetch_assoc();
}

// Helper function to escape strings

?>
