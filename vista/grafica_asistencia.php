<?php
session_start();
if (empty($_SESSION['nombre']) && empty($_SESSION['apellido'])) {
    header('location:login/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráfica de Asistencia</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        #graficaAsistencia {
            max-width: 280px;
            max-height: 280px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center text-primary mb-4">Gráfica de Asistencia de Hoy</h3>

    <div class="d-flex justify-content-center">
        <canvas id="graficaAsistencia"></canvas>
    </div>

    <div class="text-center mt-4">
        <a href="inicio.php" class="btn btn-secondary">Volver</a>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("../../sis-asistencia/controlador/api_grafica_asistencia.php")
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById("graficaAsistencia").getContext("2d");

            const total = data.puntual + data.retardo + data.inasistencia;

            new Chart(ctx, {
                type: "pie",
                data: {
                    labels: ["Puntual", "Retardo", "Inasistencia"],
                    datasets: [{
                        label: "Asistencia del día",
                        data: [data.puntual, data.retardo, data.inasistencia],
                        backgroundColor: ["#28a745", "#ffc107", "#dc3545"]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold'
                            },
                            formatter: function(value, context) {
                                let porcentaje = (value / total * 100).toFixed(1);
                                return porcentaje + "%";
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        })
        .catch(error => {
            console.error("Error al cargar datos de asistencia:", error);
            alert("No se pudo generar la gráfica.");
        });
});
</script>
</body>
</html>