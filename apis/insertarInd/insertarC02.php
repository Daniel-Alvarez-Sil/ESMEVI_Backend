<?php

include 'conexion.php';

if ($_GET) {
    try {
        // Obtener los datos
        $valor = $_GET['valor'];
        $idmedida = 4;
        $idcomponente = 3;
        echo $valor;
        // Construir query
        $sql_agregar = 'INSERT INTO dioxido_de_carbono(valor, id_medida, id_componente) 
            VALUES (?, ?, ?)';
        $agregar = $pdo->prepare($sql_agregar);
        // Ejecutar Query
        $resultado = $agregar->execute(array($valor, $idmedida, $idcomponente));
        if ($resultado == true) {
            $agregar = null;
            $pdo = null;
            echo 'Datos guardados correctamente';
        } else {
            echo 'Error al insertar: ' . $resultado;
        }
    } catch (Exception $e) {
        print($e->getMessage());
        die();
    }
        
} else {
    echo 'No hay método GET con los parámetros necesarios';
}

?>