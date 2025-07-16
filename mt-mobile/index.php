<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservation Wizard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.js"></script>
  <link rel="stylesheet" href="css/mobile-optim.css">

</head>
<body>

<div class="logo-container">
  <img src="../img/logo.png" alt="Company Logo" class="logo">
</div>


<div class="wizard-container">
  <!-- Paso 1: Servicio y Vehículo -->
  <section class="step step-1 active">
    <h2 class="step-title" data-text="select_service">Select Your Service</h2>
    <div class="btn-group">
      <label class="btn btn-outline" for="service-oneway" required>
        <input type="radio" name="service" id="service-oneway" value="oneway"> One Way (Arrival)
      </label>
      <label class="btn btn-outline" for="service-departure">
        <input type="radio" name="service" id="service-departure" value="dep"> One Way (Departure)
      </label>
      <label class="btn btn-outline" for="service-roundtrip">
        <input type="radio" name="service" id="service-roundtrip" value="roundtrip"> Round Trip
      </label>
    </div>
    <div class="form-group">
      <label for="vehicle-select" data-text="select_vehicle">Select Vehicle</label>
      <select id="vehicle-select" name="vehicle" class="form-select" required>
        <option value="">-- Select Vehicle --</option>
      </select>
    </div>
    <div class="form-navigation">
      <button type="button" class="btn btn-next">Next</button>
    </div>
  </section>

  <!-- Paso 2: Información de traslado -->
  <section class="step step-2">
    <h2 class="step-title">Trip Details</h2>
    <div class="form-group">
      <label for="pickup-location">Pickup Location</label>
      <input type="text" id="pickup-location" name="pickup_location" class="form-control" value="Airport" readonly>
    </div>
    <div class="form-group">
      <label for="destination">Destination</label>
      <input type="text" id="destination" name="destination" class="form-control awesomplete" autocomplete="off" required/>

    </div>
    <div class="form-group">
      <label for="trip-date">Date</label>
      <input type="date" id="trip-date" name="trip_date" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="trip-time">Time</label>
      <input type="time" id="trip-time" name="trip_time" class="form-control" required>
    </div>
    <div id="tarifa-box" class="tarifa-box" style="display: none;">
      <p class="tarifa-label">Estimated Total:</p>
      <p class="tarifa-valor" id="tarifa-total">$0.00 USD</p>
    </div>

    <div class="form-navigation">
      <button type="button" class="btn btn-back">Back</button>
      <button type="button" class="btn btn-next">Next</button>
    </div>
  </section>

  <!-- Paso 3: Extras -->
  <section class="step step-3">
    <h2 class="step-title">Extras</h2>
    <div class="form-group">
      <label for="baby-seat">Baby Seat</label>
      <select id="baby-seat" name="baby_seat" class="form-select">
        <option value="0">No, Thanks</option>
        <option value="1">1</option>
        <option value="2">2</option>
      </select>
    </div>
    <div class="form-group">
      <label for="special-occasion">Special Occasion</label>
      <select id="special-occasion" name="special_occasion" class="form-select">
        <option value="0">None</option>
        <option value="Birthday">Birthday</option>
        <option value="Wedding">Wedding</option>
      </select>
    </div>
    <div class="form-navigation">
      <button type="button" class="btn btn-back">Back</button>
      <button type="button" class="btn btn-next">Next</button>
    </div>
  </section>

  <!-- Paso 4: Información personal -->
  <section class="step step-4">
    <h2 class="step-title">Personal Info</h2>
    <div class="form-group">
      <label for="fullname">Full Name</label>
      <input type="text" id="fullname" name="fullname" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="phone">Phone</label>
      <input type="tel" id="phone" name="phone" class="form-control" required>
    </div>

    <button id="toggle-summary" class="btn-toggle-summary">Show Summary</button>

    <div class="reservation-summary mb-5">
  <h3 class="summary-title">Reservation Summary</h3>
  <ul class="summary-list">
    <li><strong>Service:</strong> <span id="summary-service">-</span></li>
    <li><strong>Vehicle:</strong> <span id="summary-vehicle">-</span></li>
    <li><strong>Pickup:</strong> <span id="summary-pickup">-</span></li>
    <li><strong>Destination:</strong> <span id="summary-destination">-</span></li>
    <li><strong>Date:</strong> <span id="summary-date">-</span></li>
    <li><strong>Time:</strong> <span id="summary-time">-</span></li>
    <li><strong>Extras:</strong> <span id="summary-extras">-</span></li>
    <p><strong>Total:</strong> <span id="summary-total">$0.00 USD</span></p>

  </ul>
</div>
<div class="form-group">
  <label for="payment-method">Payment Method</label>
  <select id="payment-method" name="payment_method" class="form-select" required>
    <option value="">-- Select Payment Method --</option>
    <option value="card">Credit or Debit Card</option>
    <option value="cash">Pay in Cash</option>
  </select>
</div>
<div id="payment-instructions" class="payment-info-box" style="display: none;"></div>



    <div class="form-navigation">
      <button type="button" class="btn btn-back">Back</button>
      <button id="submit-reservation" type="button" class="btn btn-submit">Book Now</button>

    </div>
  </section>
</div>

<script src="js/mobile-optim.js"></script>
<script>
  // Lista generada dinámicamente desde PHP
  const hoteles = [
    <?php
     include '../btec/func/conn.php';
     include '../btec/func/ida.php';
      $sql = "SELECT * FROM hoteles WHERE ida = '$ida' AND status = '1'";
      $result = $conn->query($sql);
      $hoteles = [];
      if ($result->num_rows > 0) {
        $hoteles = [];
while ($row = $result->fetch_assoc()) {
  $hoteles[] = '"' . str_replace('"', '\"', $row['hotel']) . '"';
}
echo implode(',', $hoteles);
      }
    ?>
  ];


  new Awesomplete(document.querySelector("#destination"), {
    list: hoteles,
    minChars: 2,
    maxItems: 8,
    autoFirst: true
  });
</script>

</body>
</html>
