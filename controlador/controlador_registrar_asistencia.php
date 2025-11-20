<?php

if (!empty($_POST["btnentrada"])) {
    if (!empty($_POST["txtdni"])) {
        $dni = $_POST["txtdni"];
        $consulta = $conexion->query("SELECT COUNT(*) AS total FROM empleado WHERE dni = '$dni'");
        $id = $conexion->query("SELECT id_empleado, nombre FROM empleado WHERE dni = '$dni'"); // Obtener id y nombre

        if ($consulta->fetch_object()->total > 0) {
            $ahora = new DateTime('now');
            $ahora->modify('-6 hour'); // ✅ Ajustar manualmente la hora (restar 1 hora)
            $fecha = $ahora->format('Y-m-d H:i:s');

            $empleado = $id->fetch_object(); // Obtener el objeto con id_empleado y nombre
            $id_empleado = $empleado->id_empleado;
            $nombre_empleado = $empleado->nombre; // Obtener el nombre del empleado

            $consultaFecha = $conexion->query("select entrada from asistencia where id_empleado=$id_empleado order by id_asistencia desc limit 1");
            $fechaBD = $consultaFecha->fetch_object()->entrada;

            if (substr($fecha, 0, 10) == substr($fechaBD, 0, 10)) {
?>
                <script>
                    $(function notification() {
                        new PNotify({
                            title: "INCORRECTO",
                            type: "error",
                            text: "YA REGISTRASTE TU ENTRADA",
                            styling: "bootstrap3"
                        });
                    });
                </script>
<?php
            } else {
                $sql = $conexion->query("INSERT INTO asistencia(id_empleado, entrada) VALUES ($id_empleado, '$fecha')");

                if ($sql == true) { ?>
                    <script>
                        $(function notification() {
                            new PNotify({
                                title: "CORRECTO",
                                type: "success",
                                text: "Bienvenido, <?php echo $nombre_empleado; ?>", // Incluir el nombre
                                styling: "bootstrap3"
                            });
                        });
                    </script>
<?php
                } else {
?>
                    <script>
                        $(function notification() {
                            new PNotify({
                                title: "INCORRECTO",
                                type: "error",
                                text: "Error al registrar ENTRADA",
                                styling: "bootstrap3"
                            });
                        });
                    </script>
<?php
                }
            }
        } else {
?>
            <script>
                $(function notification() {
                    new PNotify({
                        title: "INCORRECTO",
                        type: "error",
                        text: "El DNI ingresado no existe",
                        styling: "bootstrap3"
                    });
                });
            </script>
<?php
        }
    } else {
?>
        <script>
            $(function notification() {
                new PNotify({
                    title: "INCORRECTO",
                    type: "error",
                    text: "Ingrese el DNI",
                    styling: "bootstrap3"
                });
            });
        </script>
<?php
    }
}
?>

<script>
    setTimeout(() => {
        window.history.replaceState(null, null, window.location.pathname);
    }, 0);
</script>

<!-- REGISTRO DE SALIDA -->
<?php

if (!empty($_POST["btnsalida"])) {
    if (!empty($_POST["txtdni"])) {
        $dni = $_POST["txtdni"];
        $consulta = $conexion->query("SELECT COUNT(*) AS total FROM empleado WHERE dni = '$dni'");
        $id = $conexion->query("SELECT id_empleado, nombre FROM empleado WHERE dni = '$dni'"); // Obtener id y nombre

        if ($consulta->fetch_object()->total > 0) {
            $fecha = date("Y-m-d H:i:s", strtotime("-6 hour")); // ✅ Ajustar manualmente la hora

            $empleado = $id->fetch_object(); // Obtener el objeto con id_empleado y nombre
            $id_empleado = $empleado->id_empleado;
            $nombre_empleado = $empleado->nombre; // Obtener el nombre del empleado
            $busqueda = $conexion->query("select id_asistencia,entrada from asistencia where id_empleado= $id_empleado order by id_asistencia desc limit 1");

            while ($datos = $busqueda->fetch_object()) {
                $id_asistencia = $datos->id_asistencia;
                $entradaBD = $datos->entrada;
            }

            if (substr($fecha, 0, 10) != substr($entradaBD, 0, 10)) {
?>
                <script>
                    $(function notification() {
                        new PNotify({
                            title: "INCORRECTO",
                            type: "error",
                            text: "PRIMERO DEBES REGISTRAR TU ENTRADA",
                            styling: "bootstrap3"
                        });
                    });
                </script>
<?php
            } else {
                $consultaFecha = $conexion->query("select salida from asistencia where id_empleado=$id_empleado order by id_asistencia desc limit 1");
                $fechaBD = $consultaFecha->fetch_object()->salida;

                if (substr($fecha, 0, 10) == substr($fechaBD, 0, 10)) {
?>
                    <script>
                        $(function notification() {
                            new PNotify({
                                title: "INCORRECTO",
                                type: "error",
                                text: "YA REGISTRASTE TU SALIDA",
                                styling: "bootstrap3"
                            });
                        });
                    </script>
<?php
                } else {
                    $sql = $conexion->query("update asistencia set salida='$fecha' where id_asistencia=$id_asistencia");

                    if ($sql == true) {
?>
                        <script>
                            $(function notification() {
                                new PNotify({
                                    title: "CORRECTO",
                                    type: "success",
                                    text: "Hasta luego, <?php echo $nombre_empleado; ?>", // Incluir el nombre
                                    styling: "bootstrap3"
                                });
                            });
                        </script>
<?php
                    } else {
?>
                        <script>
                            $(function notification() {
                                new PNotify({
                                    title: "INCORRECTO",
                                    type: "error",
                                    text: "Error al registrar SALIDA",
                                    styling: "bootstrap3"
                                });
                            });
                        </script>
<?php
                    }
                }
            }
        } else {
?>
            <script>
                $(function notification() {
                    new PNotify({
                        title: "INCORRECTO",
                        type: "error",
                        text: "El DNI ingresado no existe",
                        styling: "bootstrap3"
                    });
                });
            </script>
<?php
        }
    } else {
?>
        <script>
            $(function notification() {
                new PNotify({
                    title: "INCORRECTO",
                    type: "error",
                    text: "Ingrese el DNI",
                    styling: "bootstrap3"
                });
            });
        </script>
<?php
    }
}
?>

<script>
    setTimeout(() => {
        window.history.replaceState(null, null, window.location.pathname);
    }, 0);
</script>
