// mobile-optim.js

document.addEventListener("DOMContentLoaded", function () {
  const steps = document.querySelectorAll(".step");
  const nextBtns = document.querySelectorAll(".btn-next");
  const backBtns = document.querySelectorAll(".btn-back");
  const form = document.querySelector("form"); // in case needed later

  let currentStep = 0;

  function showStep(index) {
    steps.forEach((step, i) => {
      step.classList.remove("active");
      if (i === index) step.classList.add("active");
    });
    if (index === 1 || index === 2) {
      calcularTarifa();
    }
    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  nextBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      if (validateStep(currentStep)) {
        currentStep = Math.min(currentStep + 1, steps.length - 1);
        showStep(currentStep);
          if (currentStep === 3) {
            fillSummary();
          }
      }
    });
  });

  backBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      currentStep = Math.max(currentStep - 1, 0);
      showStep(currentStep);
    });
  });

function validateStep(index) {
  const inputs = steps[index].querySelectorAll("input, select, textarea");
  let valid = true;

  inputs.forEach((input) => {
    if (
      !input.disabled &&
      input.offsetParent !== null && // visible
      input.hasAttribute("required")
    ) {
      if (!input.value.trim()) {
        input.classList.add("invalid");

        // Mostrar borde rojo
        input.style.borderColor = "red";

        // Mostrar mensaje si no existe aún
        if (!input.nextElementSibling || !input.nextElementSibling.classList.contains("error-msg")) {
          const msg = document.createElement("div");
          msg.className = "error-msg";
          msg.textContent = "Required field";
          msg.style.color = "red";
          msg.style.fontSize = "0.8rem";
          msg.style.marginTop = "4px";
          input.parentNode.appendChild(msg);
        }

        valid = false;
      } else {
        input.classList.remove("invalid");
        input.style.borderColor = "";

        // Eliminar mensaje si el campo ya es válido
        const next = input.nextElementSibling;
        if (next && next.classList.contains("error-msg")) {
          next.remove();
        }
      }

      if (!valid) {
        const firstInvalid = steps[index].querySelector(".invalid"); if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
       }
      }

    }
  });

  

  return valid;
}


 function fillSummary() {
 

  const serviceInput = document.querySelector('input[name="service"]:checked');
  const service = serviceInput ? serviceInput.nextSibling?.textContent?.trim() : '-';


  const vehicle = document.getElementById('vehicle-select')?.value || '-';
  const pickup = document.getElementById('pickup-location')?.value || '-';
  const destination = document.getElementById('destination')?.value || '-';
  const date = document.getElementById('trip-date')?.value || '-';
  const time = document.getElementById('trip-time')?.value || '-';
  const baby = document.getElementById('baby-seat')?.value;
  const special = document.getElementById('special-occasion')?.value;


  const extras = [];
  if (baby > 0) extras.push(`Baby Seat x${baby}`);
  if (special !== '0') extras.push(special);


  // Llenar elementos
  document.getElementById('summary-service').textContent = service;
  document.getElementById('summary-vehicle').textContent = vehicle;
  document.getElementById('summary-pickup').textContent = pickup;
  document.getElementById('summary-destination').textContent = destination;
  document.getElementById('summary-date').textContent = date;
  document.getElementById('summary-time').textContent = time;
  document.getElementById('summary-extras').textContent = extras.length ? extras.join(', ') : 'None';
   document.getElementById('summary-total').textContent = `$${tarifaFinal.toFixed(2)} USD`;

}



  showStep(currentStep);
  console.log(currentStep) ;
  fillSummary();


  // Activar cálculo al cambiar vehículo, hotel o servicio
document.getElementById('vehicle-select').addEventListener('change', calcularTarifa);
document.getElementById('destination').addEventListener('change', calcularTarifa);
document.querySelectorAll('input[name="service"]').forEach(radio => {
  radio.addEventListener('change', calcularTarifa);
});


const toggleBtn = document.getElementById("toggle-summary");
  const summaryBox = document.querySelector(".reservation-summary");

  toggleBtn.addEventListener("click", function () {
    summaryBox.classList.toggle("open");

    if (summaryBox.classList.contains("open")) {
      toggleBtn.textContent = "Hide Summary";
    } else {
      toggleBtn.textContent = "Show Summary";
    }
  });

  document.getElementById("payment-method").addEventListener("change", function () {
  const instructions = document.getElementById("payment-instructions");
  const method = this.value;

  if (method === "card") {
    instructions.innerHTML = "You will be redirected to a secure payment gateway to complete your transaction.";
    instructions.style.display = "block";
  } else if (method === "cash") {
    instructions.innerHTML = "Please pay in cash to the driver upon arrival.";
    instructions.style.display = "block";
  } else {
    instructions.style.display = "none";
  }
});

const isDevMode = false; // ⚠️ cámbialo a false en producción
if (isDevMode) {
  // Paso 1
  document.querySelector('#service-oneway').checked = true;
  document.querySelector('#vehicle-select').value = "Suburban";

  // Paso 2
  document.querySelector('#pickup-location').value = "Aeropuerto";
  document.querySelector('#destination').value = "Hotel Riu";
  document.querySelector('#trip-date').value = "2025-08-01";
  document.querySelector('#trip-time').value = "15:30";

  // Paso 3
  document.querySelector('#baby-seat').value = "1";
  document.querySelector('#special-occasion').value = "Birthday";

  // Paso 4
  document.querySelector('#fullname').value = "John Doe";
  document.querySelector('#email').value = "john@example.com";
  document.querySelector('#phone').value = "5551234567";

  // Simula avance hasta paso deseado (ej. paso 3)
  currentStep = 3;
  showStep(currentStep);

  // Forzamos llenar el resumen
  fillSummary();

  
}
if (typeof calcularTarifa === "function") {
  setTimeout(calcularTarifa, 500); // Espera un momento por si los selects aún cargan
}


});

function loadVehiclesByType(type) {
  const select = document.getElementById('vehicle-select');
  select.innerHTML = '<option value="">Loading...</option>';

  fetch(`../btec/func/get_vehicles.php?type=${encodeURIComponent(type)}`)
    .then(response => response.text())
    .then(data => {
      select.innerHTML = data;
    })
    .catch(error => {
      console.error("Error loading vehicles:", error);
      select.innerHTML = '<option value="">Error loading vehicles</option>';
    });
}

// Escuchar el cambio de servicio
document.querySelectorAll('input[name="service"]').forEach(radio => {
  radio.addEventListener('change', e => {
    loadVehiclesByType(e.target.value);
  });
});

// Cargar por defecto el primero seleccionado
const checkedService = document.querySelector('input[name="service"]:checked');
if (checkedService) {
  loadVehiclesByType(checkedService.value);
}

let tarifaFinal = 0; // global

function calcularTarifa() {
  const hotel = document.getElementById('destination')?.value;
  const car = document.getElementById('vehicle-select')?.value;
  const service = document.querySelector('input[name="service"]:checked')?.value;
  

  if (hotel && car && service) {
    const formData = new FormData();
    formData.append("hotel", hotel);
    formData.append("car", car);
    formData.append("service", service);

    

    fetch("../btec/func/get_tarifa.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(tarifa => {
       tarifaFinal = parseFloat(tarifa);
      document.getElementById("tarifa-total").textContent = `$${tarifaFinal.toFixed(2)} USD`;
      document.getElementById("tarifa-box").style.display = "block";
    })
    .catch(err => {
      console.error("Error obteniendo tarifa:", err);
      document.getElementById("tarifa-box").style.display = "none";
    });
  } else {
    document.getElementById("tarifa-box").style.display = "none";
  }
}

document.getElementById('submit-reservation').addEventListener('click', function () {
  const serviceInput = document.querySelector('input[name="service"]:checked');
  const service = serviceInput ? serviceInput.value : '';
  const car = document.getElementById('vehicle-select').value;
  const pickup = document.getElementById('pickup-location').value;
  const destination = document.getElementById('destination').value;
  const date = document.getElementById('trip-date').value;
  const time = document.getElementById('trip-time').value;
  const name = document.getElementById('fullname').value;
  const email = document.getElementById('email').value;
  const phone = document.getElementById('phone').value;
  const extras = document.getElementById('summary-extras').textContent;
  const totalText = document.getElementById('summary-total').textContent || '$0.00';
  const total = parseFloat(totalText.replace(/[^0-9.]/g, '')) || 0;
  const pay_method = document.getElementById('payment-method').value;

  const data = {
    service,
    car,
    pickup,
    destination,
    date,
    time,
    name,
    email,
    phone,
    extras,
    pay_method,
    total
  };

  fetch('../../btec/func/insert_reserva.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  })
  .then(res => res.text())
.then(text => {
  console.log('🔍 Respuesta cruda del servidor:', text);
  const response = JSON.parse(text);
  if (response.success) {
    alert('✅ Reserva confirmada correctamente');
  } else {
    alert('❌ Error al registrar la reserva: ' + response.message);
  }
})
.catch(error => {
  console.error('Error al enviar la reserva:', error);
  alert('❌ Error al conectar con el servidor.');
});

});

