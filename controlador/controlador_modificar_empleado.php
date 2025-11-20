<?php
if (!empty($_POST["btnmodificar"])) {
    if (!empty($_POST["txtid"]) and !empty($_POST["txtnombre"]) and !empty($_POST["txtapellido"]) and !empty($_POST["txtcargo"]) and !empty($_POST["txtcorreo"])) {
        
        $id = $_POST["txtid"];
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
            $sql = $conexion->query("UPDATE empleado SET nombre='$nombre', apellido='$apellido', cargo=$cargo, correo='$correo' WHERE id_empleado=$id");
            if ($sql == true) { ?>
                <script>
                    $(function notification() {
                        new PNotify({
                            title: "CORRECTO",
                            type: "success",
                            text: "El empleado se ha modificado correctamente.",
                            styling: "bootstrap3"
                        })
                    })
                </script>
            <?php 
            } else { ?>
                <script>
                    $(function notification() {
                        new PNotify({
                            title: "INCORRECTO",
                            type: "error",
                            text: "Error al modificar empleado",
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
                    title: "INCORRECTO",
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
