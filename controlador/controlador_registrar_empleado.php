<?php
$nombre = $apellido = $cargo = $correo = ""; // Inicializar variables

if (!empty($_POST["btnregistrar"])) {
    if (!empty($_POST["txtnombre"]) and !empty($_POST["txtapellido"]) and !empty($_POST["txtcargo"]) and !empty($_POST["txtcorreo"])) {
        
        $nombre = $_POST["txtnombre"];
        $apellido = $_POST["txtapellido"];
        $cargo = $_POST["txtcargo"];
        $correo = $_POST["txtcorreo"];

        // Validar el correo
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) { ?>
            <script>
                $(function notification() {
                    new PNotify({
                        title: "ERROR",
                        type: "error",
                        text: "Correo no válido. Introduce un correo válido.",
                        styling: "bootstrap3"
                    })
                })
            </script>
        <?php
        } else {
            $sql = $conexion->query("INSERT INTO empleado (nombre, apellido, cargo, correo) VALUES ('$nombre','$apellido',$cargo,'$correo')");
            if ($sql == true) { ?>
                <script>
                    $(function notification() {
                        new PNotify({
                            title: "CORRECTO",
                            type: "success",
                            text: "Empleado registrado correctamente.",
                            styling: "bootstrap3"
                        })
                    })
                </script>
            <?php 
                // Limpiar los valores después de un registro exitoso
                $nombre = $apellido = $cargo = $correo = "";
            } else { ?>
                <script>
                    $(function notification() {
                        new PNotify({
                            title: "INCORRECTO",
                            type: "error",
                            text: "Error al registrar el empleado.",
                            styling: "bootstrap3"
                        })
                    })
                </script>
            <?php }
        }
    } else { ?>
        <script>
            $(function notification() {
                new PNotify({
                    title: "ERROR",
                    type: "error",
                    text: "Los campos están vacíos.",
                    styling: "bootstrap3"
                })
            })
        </script>
<?php } ?>
    <script>
    setTimeout(() => {
        window.history.replaceState(null, null, window.location.pathname);
    }, 0);
    </script>
<?php } ?>
