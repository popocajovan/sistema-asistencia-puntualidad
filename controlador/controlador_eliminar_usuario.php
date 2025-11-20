<?php

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = $conexion->query("delete from usuario where id_usuario=$id");
    if ($sql == true) { ?>
        <script>
            $(function notification() {
                new PNotify({
                    title: "CORRECTO",
                    type: "success",
                    text: "El usuario se ha eliminado correctamente",
                    styling: "bootstrap3"
                })
            })
        </script>
    <?php } else { ?>
        <script>
            $(function notification() {
                new PNotify({
                    title: "INCORRECTO",
                    type: "errpr",
                    text: "Error al eliminar el usuario",
                    styling: "bootstrap3"
                })
            })
        </script>
    <?php
    } ?>
    <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname);

        }, 0);
    </script>


<?php }
?>