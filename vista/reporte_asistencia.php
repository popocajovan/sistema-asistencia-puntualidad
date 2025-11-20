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
    $sql = $conexion->query("select * from empleado");
    ?>

    <form id="formReporte" action="fpdf/fpdf-tutoriales-master/ReporteAsistenciaFecha.php">
        <input required type="date" name="txtfechainicio" id="fechaInicio" class="input input__text mb-2">
        <input required type="date" name="txtfechafinal" id="fechaFinal" class="input input__text mb-2">
        <select required name="txtempleado" class="input input__select mb-2">
            <option value="todos">Todos los empleados</option>
            <?php while ($datos = $sql->fetch_object()) { ?>
                <option value="<?= $datos->id_empleado ?>"><?= $datos->nombre . " " . $datos->apellido ?></option>
            <?php } ?>
        </select>
        <button type="submit" name="btngenerar" class="btn btn-primary w-100 p-3">Generar Reporte</button>
    </form>

</div>
</div>
<!-- fin del contenido principal -->

<!-- por ultimo se carga el footer -->
<?php require('./layout/footer.php'); ?>

<!-- Validación con PNotify -->
<script>
    document.getElementById('formReporte').addEventListener('submit', function (e) {
        const fechaInicio = new Date(document.getElementById('fechaInicio').value);
        const fechaFinal = new Date(document.getElementById('fechaFinal').value);
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);

        if (fechaInicio > hoy || fechaFinal > hoy) {
            e.preventDefault();
            $(function () {
                new PNotify({
                    title: "Fecha inválida",
                    text: "No puedes seleccionar una fecha futura.",
                    type: "error",
                    styling: "bootstrap3"
                });
            });
        } else if (fechaInicio > fechaFinal) {
            e.preventDefault();
            $(function () {
                new PNotify({
                    title: "Rango de fechas inválido",
                    text: "La fecha de inicio no puede ser mayor que la fecha final.",
                    type: "error",
                    styling: "bootstrap3"
                });
            });
        }
    });
</script>