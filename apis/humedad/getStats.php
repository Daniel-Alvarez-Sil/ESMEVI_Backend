<?php
// Allow access from any origin
header("Access-Control-Allow-Origin: *");
// Allow specific HTTP methods
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// If the request is OPTIONS, stop further execution
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database credentials
$host = "localhost";         // Replace with your database host
$user = "root";              // Replace with your database username
$password = "";              // Replace with your database password
$dbname = "esmevi";   // Replace with your database name

// Connect to the database
$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// Prepare the SQL query to calculate AVG, MAX, and MIN
$sql = "SELECT AVG(valor) AS avg, MAX(valor) AS max, MIN(valor) AS min FROM humedad";

// Execute the query
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $stats = $result->fetch_assoc();

    // Return the results as JSON
    header('Content-Type: application/json');
    echo json_encode($stats);
} else {
    // Handle the case where no data exists
    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "message" => "No data found in the table."]);
}

// Close the connection
$conn->close();
?>