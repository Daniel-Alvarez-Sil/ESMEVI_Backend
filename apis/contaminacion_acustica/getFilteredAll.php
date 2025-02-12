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

// Get the input parameters from the request
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

if (!$fecha_inicio || !$fecha_fin) {
    die(json_encode(["status" => "error", "message" => "Both 'fecha_inicio' and 'fecha_fin' parameters are required."]));
}

// Prepare the SQL query
$sql = "SELECT valor, fechahora, id_medida, id_componente 
        FROM contaminacion_acustica 
        INNER JOIN componente USING(id_componente) 
        WHERE id_modulo = 1 AND DATE(fechahora) >= ? AND DATE(fechahora) <= ?";

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(["status" => "error", "message" => "Failed to prepare the SQL statement."]));
}

// Bind parameters
$stmt->bind_param("ss", $fecha_inicio, $fecha_fin);

// Execute the statement
if (!$stmt->execute()) {
    die(json_encode(["status" => "error", "message" => "Failed to execute the SQL query: " . $stmt->error]));
}

// Get the results
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Return the results as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // No rows found
    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "message" => "No rows found for the given date range."]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>