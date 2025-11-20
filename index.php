<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de bienvenida</title>
    <link rel="stylesheet" href="public/estilos/estilos2.css">

    <audio id="confirmationSound" src="../sis-asistencia/escaner.mp3"></audio>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link href="public/pnotify/css/pnotify.css" rel="stylesheet" />
    <link href="public/pnotify/css/pnotify.buttons.css" rel="stylesheet" />
    <link href="public/pnotify/css/custom.min.css" rel="stylesheet" />
    <script src="public/pnotify/js/jquery.min.js"></script>
    <script src="public/pnotify/js/pnotify.js"></script>
    <script src="public/pnotify/js/pnotify.buttons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
</head>

<body>
    <h1>BIENVENIDOS, REGISTRA TU ASISTENCIA</h1>
    <h2 id="fecha"></h2>
    <?php
    include "modelo/conexion.php";
    include "controlador/controlador_registrar_asistencia.php";
    ?>

    <div class="container">
        <a href="../sis-asistencia/vista/login/login.php"> Ingresar al sistema</a>
        <p>Ingrese su DNI o escanee el QR</p>
        <form action="" method="POST">
            <input type="number" placeholder="DNI del empleado" name="txtdni" id="txtdni">
            <video id="preview" style="width: 320px; height: 240px; display: none;"></video>
            <button type="button" id="scanButton"><i class="fas fa-qrcode"></i> Escanear QR</button>

            <div class="botones">
                <button id="salida" class="salida" type="submit" name="btnsalida" value="ok">SALIDA</button>
                <button id="entrada" class="entrada" type="submit" name="btnentrada" value="ok">ENTRADA</button>
            </div>
        </form>
    </div>

    <script>
 const video = document.getElementById('preview');
const scanButton = document.getElementById('scanButton');
let dni = document.getElementById("txtdni");
const entradaBtn = document.getElementById("entrada");
const salidaBtn = document.getElementById("salida");

let tickInterval;
let scanning = false;  // Variable para evitar múltiples escaneos

// Función para actualizar la fecha y hora en tiempo real
function actualizarFechaHora() {
    let fecha = new Date();
    let fechaHora = fecha.toLocaleString();
    document.getElementById("fecha").textContent = fechaHora;
}
setInterval(actualizarFechaHora, 1000);
actualizarFechaHora(); // Actualizar inmediatamente al cargar la página

// Limitar el campo de DNI a 8 caracteres
dni.addEventListener("input", function() {
    if (this.value.length > 8) {
        this.value = this.value.slice(0, 8);
    }
});

// Escuchar las teclas ← y → para activar botones
document.addEventListener("keyup", function(event) {
    if (event.code === "ArrowLeft") {
        salidaBtn.click();
    } else if (event.code === "ArrowRight") {
        entradaBtn.click();
    }
});

// Función para escanear QR
scanButton.addEventListener('click', () => {
    if (scanning) return; // Evita múltiples activaciones
    scanning = true;

    scanButton.style.display = 'none'; // Ocultar el botón

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(stream => {
            video.srcObject = stream;
            video.play();
            video.style.display = 'block';
            tickInterval = setInterval(tick, 300); // Reducir la frecuencia para evitar spam
        })
        .catch(err => {
            console.error('Error al acceder a la cámara:', err);
            scanning = false;
            scanButton.style.display = 'block';
        });
});

function tick() {
    if (!video.videoWidth) return; // Evita procesar cuando no hay video disponible

    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
    const code = jsQR(imageData.data, imageData.width, imageData.height, {
        inversionAttempts: 'dontInvert',
    });

    if (code) {
        dni.value = code.data; // Asigna el DNI escaneado
        clearInterval(tickInterval); // Detiene la lectura repetitiva
        stopCamera(); // Apaga la cámara
        showNotification(); // Muestra la notificación
    }
}

// Función para detener la cámara
function stopCamera() {
    const stream = video.srcObject;
    if (stream) {
        const tracks = stream.getTracks();
        tracks.forEach(track => track.stop()); // Detener cada pista de la cámara
    }
    video.style.display = 'none'; // Oculta el video
    scanButton.style.display = 'block'; // Vuelve a mostrar el botón
    scanning = false; // Reinicia la variable de estado
}

// Función para mostrar la notificación de escaneo exitoso
function showNotification() {
    new PNotify({
        title: "Código QR Escaneado",
        type: "success",
        text: "El código QR ha sido escaneado correctamente.",
        styling: "bootstrap3"
    });
    document.getElementById('confirmationSound').play();
}
</script>
    
</body>

</html>