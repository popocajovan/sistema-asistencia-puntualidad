<?php
session_start();
if (empty($_SESSION['nombre']) and empty($_SESSION['apellido'])) {
    header('location:login/login.php');
}

?>

<style>
    ul li:nth-child(1) .activo {
        background: rgb(11, 150, 214) !important;
    }
</style>
<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<div class="page-content">


    <h4 class="text-center text-secondary">ASISTENCIA DE EMPLEADOS</h4>
    <?php
    include "../modelo/conexion.php";
    include "../controlador/Controlador_eliminar_asistencia.php";

    $sql = $conexion->query(" SELECT
	asistencia.id_asistencia, 
	asistencia.id_empleado, 
	asistencia.entrada, 
	asistencia.salida, 
	empleado.id_empleado, 
	empleado.nombre as 'nom_empleado', 
	empleado.apellido, 
	empleado.dni, 
	empleado.cargo, 
	empleado.correo, 
	cargo.id_cargo, 
	cargo.nombre as 'nom_cargo'
FROM
	cargo
	INNER JOIN
	empleado
	ON 
		cargo.id_cargo = empleado.cargo
	INNER JOIN
	asistencia
	ON 
		asistencia.id_empleado = empleado.id_empleado")
    ?>
<a href="grafica_asistencia.php" target="_blank" class="btn btn-danger">
  <i class="fas fa-chart-pie"></i> Generar gráfica
</a>

    <div class="text-right mb-2">

        <a href="fpdf/fpdf-tutoriales-master/ReporteAsistencia.php" target="_blank" class="btn btn-success"> <i class="fas fa-file-pdf"></i>Generar reportes</a>
    </div>
    <div class="text-right mb-2">

        <a href="reporte_asistencia.php" target="_blank" class="btn btn-primary"> <i class="fas fa-plus"></i> Mas reportes</a>
    </div>
    <table class="table table-bordered table-hover col-12" id="example" style="width: 100%;">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">EMPLEADO</th>
                <!-- <th scope="col">DNI</th> -->
                <th scope="col">CARGO</th>
                <th scope="col">CORREO</th>
                <th scope="col">ENTRADA</th>
                <th scope="col">SALIDA</th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($datos = $sql->fetch_object()) { ?>
                <tr>
                    <td><?= $datos->id_asistencia ?></td>
                    <td><?= $datos->nom_empleado . " " . $datos->apellido   ?></td>
                    <!-- <td><?= $datos->dni ?></td> -->
                    <td><?= $datos->nom_cargo ?></td>
                    <td><?= $datos->correo ?></td>
                    <td><?= $datos->entrada ?></td>
                    <td><?= $datos->salida ?></td>
                    <td>
                        <a href="inicio.php?id=<?= $datos->id_asistencia ?>" onclick=" advertencia(event)" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                    </td>

                </tr>

            <?php }
            ?>


        </tbody>
    </table>
</div>
</div>
<!-- fin del contenido principal -->


<!-- por ultimo se carga el footer -->
<?php require('./layout/footer.php'); ?>