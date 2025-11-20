<?php
ob_start(); // Inicia el búfer de salida para evitar errores de encabezado

if (!empty($_GET["txtfechainicio"]) && !empty($_GET["txtfechafinal"]) && !empty($_GET["txtempleado"])) {
    require('./fpdf.php');

    $fechaInicio = $_GET["txtfechainicio"] . " 00:00:00";
    $fechaFinal  = $_GET["txtfechafinal"] . " 23:59:59";
    $empleado    = $_GET["txtempleado"];

    class PDF extends FPDF
    {
        function Header()
        {
            include '../../../modelo/conexion.php';
            if (!$conexion) {
                die("Error en la conexión: " . mysqli_connect_error());
            }

            $consulta_info = $conexion->query("SELECT * FROM empresa");
            $dato_info = $consulta_info->fetch_object();

            if (file_exists('logo.png')) {
                $this->Image('logo.png', 230, 5, 55);
            }

            $this->SetFont('Arial', 'B', 19);
            $this->Cell(95);
            $this->SetTextColor(0, 0, 0);
            // Usamos utf8_decode para que se muestre bien el acento
            $this->Cell(110, 15, utf8_decode($dato_info->nombre ?? ''), 1, 1, 'C', 0);
            $this->Ln(3);
            $this->SetTextColor(103);

            $this->Cell(180);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(96, 10, utf8_decode("Ubicación : " . ($dato_info->ubicacion ?? '')), 0, 0, '', 0);
            $this->Ln(5);

            $this->Cell(180);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(59, 10, utf8_decode("Teléfono : " . ($dato_info->telefono ?? '')), 0, 0, '', 0);
            $this->Ln(5);

            $this->Cell(180);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(85, 10, utf8_decode("RUC : " . ($dato_info->ruc ?? '')), 0, 0, '', 0);
            $this->Ln(10);

            $this->SetTextColor(0, 95, 189);
            $this->Cell(100);
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(100, 10, utf8_decode("REPORTE DE ASISTENCIAS POR FECHAS"), 0, 1, 'C', 0);
            $this->Ln(7);

            $this->SetFillColor(125, 173, 221);
            $this->SetTextColor(0, 0, 0);
            $this->SetDrawColor(163, 163, 163);
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(15, 10, utf8_decode('N°'), 1, 0, 'C', 1);
            $this->Cell(80, 10, utf8_decode('EMPLEADO'), 1, 0, 'C', 1);
            $this->Cell(50, 10, utf8_decode('CARGO'), 1, 0, 'C', 1);
            $this->Cell(50, 10, utf8_decode('ENTRADA'), 1, 0, 'C', 1);
            $this->Cell(50, 10, utf8_decode('SALIDA'), 1, 0, 'C', 1);
            $this->Cell(30, 10, utf8_decode('TOTAL HRS'), 1, 1, 'C', 1);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');

            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $hoy = date('d/m/Y');
            $this->Cell(540, 10, utf8_decode($hoy), 0, 0, 'C');
        }
    }

    include '../../../modelo/conexion.php';
    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $pdf = new PDF();
    $pdf->AddPage("landscape");
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetDrawColor(163, 163, 163);

    if ($empleado == "todos") {
        $sql = $conexion->query("
            SELECT asistencia.id_asistencia, asistencia.id_empleado,
            DATE_FORMAT(asistencia.entrada, '%d-%m-%Y %H:%i:%s') AS entrada,
            DATE_FORMAT(asistencia.salida, '%d-%m-%Y %H:%i:%s') AS salida,
            TIMEDIFF(asistencia.salida, asistencia.entrada) AS totalHR,
            empleado.nombre, empleado.apellido, empleado.dni,
            cargo.nombre AS cargo
            FROM asistencia
            INNER JOIN empleado ON asistencia.id_empleado = empleado.id_empleado
            INNER JOIN cargo ON empleado.cargo = cargo.id_cargo
            WHERE asistencia.entrada BETWEEN '$fechaInicio' AND '$fechaFinal'
            ORDER BY empleado.id_empleado ASC
        ");
    } else {
        $sql = $conexion->query("
            SELECT asistencia.id_asistencia, asistencia.id_empleado,
            DATE_FORMAT(asistencia.entrada, '%d-%m-%Y %H:%i:%s') AS entrada,
            DATE_FORMAT(asistencia.salida, '%d-%m-%Y %H:%i:%s') AS salida,
            TIMEDIFF(asistencia.salida, asistencia.entrada) AS totalHR,
            empleado.nombre, empleado.apellido, empleado.dni,
            cargo.nombre AS cargo
            FROM asistencia
            INNER JOIN empleado ON asistencia.id_empleado = empleado.id_empleado
            INNER JOIN cargo ON empleado.cargo = cargo.id_cargo
            WHERE asistencia.id_empleado = $empleado AND asistencia.entrada BETWEEN '$fechaInicio' AND '$fechaFinal'
            ORDER BY asistencia.id_asistencia ASC
        ");
    }

    $i = 0;
    if ($sql && $sql->num_rows > 0) {
        while ($datos = $sql->fetch_object()) {
            $i++;
            $pdf->Cell(15, 10, $i, 1, 0, 'C', 0);
            $pdf->Cell(80, 10, utf8_decode(($datos->nombre ?? '') . ' ' . ($datos->apellido ?? '')), 1, 0, 'C', 0);
            $pdf->Cell(50, 10, utf8_decode($datos->cargo ?? ''), 1, 0, 'C', 0);
            $pdf->Cell(50, 10, utf8_decode($datos->entrada ?? ''), 1, 0, 'C', 0);
            $pdf->Cell(50, 10, utf8_decode($datos->salida ?? ''), 1, 0, 'C', 0);
            $pdf->Cell(30, 10, utf8_decode($datos->totalHR ?? ''), 1, 1, 'C', 0);
        }
    } else {
        $pdf->Cell(0, 10, utf8_decode('No se encontraron registros para los parámetros seleccionados.'), 1, 1, 'C');
    }

    ob_end_clean(); // Limpia el búfer antes de enviar el PDF
    $pdf->Output('I', 'Reporte Asistencia.pdf');
} else {
    echo utf8_decode("Error: Faltan parámetros necesarios (txtfechainicio, txtfechafinal, txtempleado)");
}
?>
