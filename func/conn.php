<?php
// Modo desarrollo (cámbialo a false en producción)
$debug = false;

// Mostrar errores si está activado
if ($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Datos de conexión
$host_name = 'localhost';
$database = 'status';
$user_name = 'root';
$password  = 'Ju6zqfvX6dh!';

// Conexión
$conn = new mysqli($host_name, $user_name, $password, $database);

// Forzar charset UTF8MB4
$conn->set_charset('utf8mb4');

// Verificar errores de conexión
if ($conn->connect_error) {
    if ($debug) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo conectar a la base de datos.']);
    }
    exit;
}
?>
