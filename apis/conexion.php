<?php 
    // Conexión a la BD (classicmodels)
    ini_set('display_errors', 1);
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=esmevi', 'root', '');
        echo '<h1>Conexión exitosA - ESMEVI</h1>';
    } catch(PDOException $e) {
        echo '<h2>Error al conectarse a la BD</h2>';
        print 'Error: ' . $e->getMessage();
        exit();
    }
?>