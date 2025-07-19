<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conn.php';
include 'ida.php';
require_once 'send_confirmation.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        throw new Exception("JSON malformado");
    }

 
    $name     = trim($data['name'] ?? '');
    $email    = trim($data['email'] ?? '');
    $phone    = trim($data['phone'] ?? '');
    $service  = trim($data['service'] ?? '');
    $car      = trim($data['car'] ?? '');
    $total    = floatval($data['total'] ?? 0);
    $pickup   = trim($data['pickup'] ?? '');
    $dest     = trim($data['destination'] ?? '');
    $date     = trim($data['date'] ?? '');
    $time     = trim($data['time'] ?? '');
    $extras   = trim($data['extras'] ?? '');
    $payMethod= trim($data['pay_method'] ?? '');
    $status   = 'Confirmed';
    $creada   = date('Y-m-d H:i:s');
    $idr      = strtoupper(uniqid('R'));

    if (!$name || !$email || !$phone || !$service || !$car || !$pickup || !$dest || !$date || !$time || $total <= 0) {
        throw new Exception("Campos obligatorios incompletos");
    }

    $sql = "INSERT INTO reservas_mobile 
        (name, email, phone, service, car, total, pickup, destination, date, time, extras, pay_method, status, creada, idr, ida)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssss",
        $name, $email, $phone, $service, $car, $total,
        $pickup, $dest, $date, $time, $extras, $payMethod,
        $status, $creada, $idr, $ida
    );

    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar: " . $stmt->error);
    }

    echo json_encode(['success' => true, 'message' => 'Reserva insertada correctamente']);

    // Preparar datos para correo
    $emailData = [
    'name'        => $name,
    'email'       => $email,
    'phone'       => $phone,
    'service'     => $service,
    'car'         => $car,
    'pickup'      => $pickup,
    'destination' => $dest,
    'date'        => $date,
    'time'        => $time,
    'extras'      => $extras,
    'pay_method'  => $payMethod,
    'total'       => $total
    ];

    $result = enviarConfirmacion($emailData);

    // Si falla el correo, no afecta el INSERT, pero lo reportamos
    if (!$result['success']) {
        error_log('✉️ Error al enviar email: ' . $result['message']);
    }


} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'ERROR DEBUG: ' . $e->getMessage()]);
}
?>

