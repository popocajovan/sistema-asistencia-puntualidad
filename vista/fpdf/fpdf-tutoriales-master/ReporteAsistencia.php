<?php
// Evitar la salida de mensajes de error (advertencias de deprecated y notice) y usar buffering
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 0);
ob_start();

require('./fpdf.php');

class PDF extends FPDF
{
   // Cabecera de página
   function Header()
   {
      include '../../../modelo/conexion.php'; // Llamamos a la conexión a la BD

      $consulta_info = $conexion->query("SELECT * FROM empresa");
      $dato_info = $consulta_info->fetch_object();

      $this->Image('logo.png', 230, 5, 55);
      $this->SetFont('Arial', 'B', 19);
      $this->Cell(95);
      $this->SetTextColor(0, 0, 0);
      
      // Verificar que el dato exista, sino asignar cadena vacía
      $nombreEmpresa = isset($dato_info->nombre) ? utf8_decode($dato_info->nombre) : '';
      $this->Cell(110, 15, $nombreEmpresa, 1, 1, 'C', 0);
      $this->Ln(3);
      $this->SetTextColor(103);

      // UBICACION
      $this->Cell(180);
      $this->SetFont('Arial', 'B', 10);
      $ubicacion = isset($dato_info->ubicacion) ? $dato_info->ubicacion : '';
      $this->Cell(96, 10, utf8_decode("Ubicación : " . $ubicacion), 0, 0, '', 0);
      $this->Ln(5);

      // TELEFONO
      $this->Cell(180);
      $this->SetFont('Arial', 'B', 10);
      $telefono = isset($dato_info->telefono) ? $dato_info->telefono : '';
      $this->Cell(59, 10, utf8_decode("Teléfono : " . $telefono), 0, 0, '', 0);
      $this->Ln(5);

      // RUC
      $this->Cell(180);
      $this->SetFont('Arial', 'B', 10);
      $ruc = isset($dato_info->ruc) ? $dato_info->ruc : '';
      $this->Cell(85, 10, utf8_decode("RUC : " . $ruc), 0, 0, '', 0);
      $this->Ln(10);

      // TÍTULO DE LA TABLA
      $this->SetTextColor(0, 95, 189);
      $this->Cell(100);
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(100, 10, utf8_decode("REPORTE DE ASISTENCIAS "), 0, 1, 'C', 0);
      $this->Ln(7);

      // CAMPOS DE LA TABLA
      $this->SetFillColor(125, 173, 221);
      $this->SetTextColor(0, 0, 0);
      $this->SetDrawColor(163, 163, 163);
      $this->SetFont('Arial', 'B', 11);
      $this->Cell(15, 10, utf8_decode('N°'), 1, 0, 'C', 1);
      $this->Cell(80, 10, utf8_decode('EMPLEADO'), 1, 0, 'C', 1);
      $this->Cell(30, 10, utf8_decode('DNI'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('CARGO'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('ENTRADA'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('SALIDA'), 1, 1, 'C', 1);
   }

   // Pie de página
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

// Crear el objeto PDF y configurar parámetros
$pdf = new PDF();
$pdf->AddPage("landscape"); // Formato horizontal (landscape)
$pdf->AliasNbPages();

$i = 0;
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163);

$consulta_reporte_asistencia = $conexion->query("SELECT asistencia.entrada, asistencia.salida, empleado.nombre, empleado.apellido, empleado.dni, cargo.nombre as 'nomCargo' FROM asistencia
INNER JOIN empleado ON asistencia.id_empleado = empleado.id_empleado
INNER JOIN cargo ON empleado.cargo = cargo.id_cargo");

while ($datos_reporte = $consulta_reporte_asistencia->fetch_object()) {
   $i++;

   // Se usa (string) para asegurar que nunca se pase un null a utf8_decode()
   $pdf->Cell(15, 10, utf8_decode((string)$i), 1, 0, 'C', 0);
   $empleado = (isset($datos_reporte->nombre) ? $datos_reporte->nombre : '') . ' ' . (isset($datos_reporte->apellido) ? $datos_reporte->apellido : '');
   $pdf->Cell(80, 10, utf8_decode($empleado), 1, 0, 'C', 0);
   $pdf->Cell(30, 10, utf8_decode(isset($datos_reporte->dni) ? $datos_reporte->dni : ''), 1, 0, 'C', 0);
   $pdf->Cell(50, 10, utf8_decode(isset($datos_reporte->nomCargo) ? $datos_reporte->nomCargo : ''), 1, 0, 'C', 0);
   $pdf->Cell(50, 10, utf8_decode(isset($datos_reporte->entrada) ? $datos_reporte->entrada : ''), 1, 0, 'C', 0);
   $pdf->Cell(50, 10, utf8_decode(isset($datos_reporte->salida) ? $datos_reporte->salida : ''), 1, 1, 'C', 0);
}

$pdf->Output('Reporte Asistencia.pdf', 'I');

// Finaliza el buffer y envía la salida
ob_end_flush();
?>
