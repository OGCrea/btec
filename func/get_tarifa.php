<?php
include 'conn.php';
include 'ida.php'; // Aquí se define $ida

// Sanitizar entradas
$hotel = $_POST["hotel"] ?? '';
$service_type = strtolower($_POST["service"] ?? '');
$car = $_POST["car"] ?? '';

// Función: obtener tarifa base de vehículo
function getCarRate($conn, $car, $service, $ida) {
    $stmt = $conn->prepare("SELECT rate FROM cars WHERE car = ? AND type = ? AND ida = ? AND status = 1 LIMIT 1");
    $stmt->bind_param("sss", $car, $service, $ida);
    $stmt->execute();
    $stmt->bind_result($rate);
    return $stmt->fetch() ? $rate : 0;
}

// Función: obtener zona del hotel
function getHotelZone($conn, $hotel, $ida) {
    $stmt = $conn->prepare("SELECT zona FROM hoteles WHERE hotel = ? AND ida = ? LIMIT 1");
    $stmt->bind_param("ss", $hotel, $ida);
    $stmt->execute();
    $stmt->bind_result($zona);
    return $stmt->fetch() ? $zona : null;
}

// Función: obtener tarifa base por zona y servicio
function getBaseTarifa($conn, $zona, $service, $ida) {
    $stmt = $conn->prepare("SELECT tarifa FROM tarifas WHERE zona = ? AND service_type = ? AND ida = ? AND status = 1 LIMIT 1");
    $stmt->bind_param("sss", $zona, $service, $ida);
    $stmt->execute();
    $stmt->bind_result($tarifa);
    return $stmt->fetch() ? $tarifa : 0;
}

// Lógica de ejecución
$zona = getHotelZone($conn, $hotel, $ida);

if ($zona) {
    $base_tarifa = getBaseTarifa($conn, $zona, $service_type, $ida);
    $car_rate = getCarRate($conn, $car, $service_type, $ida);
    $total = $base_tarifa + $car_rate;
    echo $total;
} else {
    echo '0';
}
?>

