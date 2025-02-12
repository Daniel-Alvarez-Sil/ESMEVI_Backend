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

// Prepare the SQL query to retrieve the last record
$sql = "SELECT * FROM humedad INNER JOIN componente USING(id_componente) WHERE id_modulo = 1 ORDER BY fechahora DESC LIMIT 1";

// Execute the query
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Fetch the last record
    $lastRecord = $result->fetch_assoc();

    // Return the record as JSON
    header('Content-Type: application/json');
    echo json_encode($lastRecord);
} else {
    // Handle the case where no data exists
    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "message" => "No records found."]);
}

// Close the connection
$conn->close();
?>