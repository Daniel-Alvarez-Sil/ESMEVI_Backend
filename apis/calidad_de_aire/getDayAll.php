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
$host = "localhost";         // Database host
$user = "root";              // Database username
$password = "";              // Database password
$dbname = "esmevi";   // Replace with your database name

// Establish database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// SQL query to fetch all records from the 'calidad_de_aire' table
$sql = "SELECT id_calidad_de_aire, AVG(valor) AS valor, fechahora, id_componente, id_medida FROM calidad_de_aire GROUP BY DATE(fechahora)";

// Execute the query
$result = $conn->query($sql);

// Check if records exist
if ($result && $result->num_rows > 0) {
    $data = [];
    
    // Fetch all rows as associative array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Set response header to JSON
    header('Content-Type: application/json');

    // Return data as JSON
    echo json_encode($data);
} else {
    // No records found
    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "message" => "No records found."]);
}

// Close the connection
$conn->close();
?>
