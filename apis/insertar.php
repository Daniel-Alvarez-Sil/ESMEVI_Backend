<?php

include 'conexion.php';

if ($_GET) {
    try {
        // Sanitize GET parameters
        $temp = filter_input(INPUT_GET, 'temp', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $hum = filter_input(INPUT_GET, 'hum', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $contacust = filter_input(INPUT_GET, 'contacust', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $codos = filter_input(INPUT_GET, 'codos', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $caliaire = filter_input(INPUT_GET, 'caliaire', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $comp1 = filter_input(INPUT_GET, 'comp1', FILTER_SANITIZE_NUMBER_INT);
        $comp2 = filter_input(INPUT_GET, 'comp2', FILTER_SANITIZE_NUMBER_INT);
        $comp3 = filter_input(INPUT_GET, 'comp3', FILTER_SANITIZE_NUMBER_INT);
        $comp4 = filter_input(INPUT_GET, 'comp4', FILTER_SANITIZE_NUMBER_INT);

        // Measurement IDs
        $centigrados = 1;
        $porcentaje = 2;
        $soundlevel = 3;
        $ppm = 4;

        // Array of data to insert
        $insertData = [
            ['table' => 'temperatura', 'value' => $temp, 'measure_id' => $centigrados, 'component_id' => $comp1],
            ['table' => 'humedad', 'value' => $hum, 'measure_id' => $porcentaje, 'component_id' => $comp1],
            ['table' => 'contaminacion_acustica', 'value' => $contacust, 'measure_id' => $soundlevel, 'component_id' => $comp2],
            ['table' => 'dioxido_de_carbono', 'value' => $codos, 'measure_id' => $ppm, 'component_id' => $comp3],
            ['table' => 'calidad_de_aire', 'value' => $caliaire, 'measure_id' => $ppm, 'component_id' => $comp4],
        ];

        // Insert each record
        foreach ($insertData as $data) {
            $sql = "INSERT INTO {$data['table']} (valor, id_medida, id_componente) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$data['value'], $data['measure_id'], $data['component_id']]);

            if ($result) {
                echo ucfirst(str_replace('_', ' ', $data['table'])) . " guardada correctamente.<br>";
            } else {
                throw new Exception("Error al insertar en la tabla {$data['table']}: " . implode(', ', $stmt->errorInfo()));
            }
        }

    } catch (Exception $e) {
        // Handle exceptions
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the PDO connection
        $pdo = null;
    }
} else {
    echo 'No hay método GET con los parámetros necesarios';
}

?>
