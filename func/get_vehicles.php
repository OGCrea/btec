<?php
include 'conn.php';
include 'ida.php'; // asegúrate de que $ida esté disponible

$type = $_GET['type'] ?? 'oneway';

$sql = "SELECT * FROM cars WHERE status = 1 AND ida = '$ida' AND type = '$type' ORDER BY max ASC";
$result = $conn->query($sql);

$options = "";

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $value = htmlspecialchars($row['car']);
    $label = $row['car'] . ' - up to ' . $row['max'] . ' pax';
    $options .= "<option value=\"$value\">$label</option>";
  }
} else {
  $options = "<option value=\"0\">No vehicles available</option>";
}

echo $options;
