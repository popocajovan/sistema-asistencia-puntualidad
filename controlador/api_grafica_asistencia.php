<?php
include "../modelo/conexion.php";

date_default_timezone_set("America/Mexico_City");
$fechaHoy = date("Y-m-d");
$horaLimite = "15:00:00";

$totalEmpleados = $conexion->query("SELECT COUNT(*) AS total FROM empleado")->fetch_object()->total;

$query = $conexion->query("
    SELECT entrada
    FROM asistencia
    WHERE DATE(entrada) = '$fechaHoy'
");

$puntual = 0;
$retardo = 0;

while ($row = $query->fetch_assoc()) {
    $horaEntrada = date("H:i:s", strtotime($row['entrada']));
    if ($horaEntrada <= $horaLimite) {
        $puntual++;
    } else {
        $retardo++;
    }
}

$inasistencia = $totalEmpleados - ($puntual + $retardo);

echo json_encode([
    "puntual" => $puntual,
    "retardo" => $retardo,
    "inasistencia" => $inasistencia
]);
?>
