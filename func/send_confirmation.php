<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function enviarConfirmacion($data) {
    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.ionos.mx'; // Cambia esto
        $mail->SMTPAuth   = true;
        $mail->Username   = 'reservations@statusluxuryservices.com'; // Cambia esto
        $mail->Password   = '!KH#BgW7t$mhQgg';          // Cambia esto
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('no-reply@statusluxuryservices.com', 'Status Luxury Services');
        //$mail->addAddress($data['email'], $data['name']); // Cliente
        $mail->addAddress('ia@transbook.mx', 'Developer'); // Developer
        //$mail->addBCC('reservations@statusluxuryservices.com');             // Copia al admin

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = '🚌 Confirmación de reserva';
        $mail->Body    = generarHTML($data);
        $mail->AltBody = strip_tags(generarHTML($data));

        $mail->send();
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $mail->ErrorInfo];
    }
}

function generarHTML($d) {
    return "
        <h2>Reserva confirmada</h2>
        <p>Gracias, <strong>{$d['name']}</strong>. Aquí tienes los detalles de tu reserva:</p>
        <ul>
            <li><strong>Servicio:</strong> {$d['service']}</li>
            <li><strong>Vehículo:</strong> {$d['car']}</li>
            <li><strong>Origen:</strong> {$d['pickup']}</li>
            <li><strong>Destino:</strong> {$d['destination']}</li>
            <li><strong>Fecha:</strong> {$d['date']}</li>
            <li><strong>Hora:</strong> {$d['time']}</li>
            <li><strong>Extras:</strong> {$d['extras']}</li>
            <li><strong>Total:</strong> $".number_format($d['total'], 2)." USD</li>
            <li><strong>Forma de pago:</strong> {$d['pay_method']}</li>
        </ul>
        <p>Nos vemos pronto 👋</p>
    ";
}
