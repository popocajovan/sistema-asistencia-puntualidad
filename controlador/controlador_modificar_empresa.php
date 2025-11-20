<?php
if (!empty($_POST["btnmodificar"])) {
    if (!empty($_POST["txtid"])) {
        $id = $_POST["txtid"];
        $nombre = $_POST["txtnombre"];
        $telefono = $_POST["txttelefono"];
        $ubicacion = $_POST["txtubicacion"];
        $ruc = $_POST["txttruc"];

        $sql=$conexion->query("update empresa set nombre='$nombre', telefono='$telefono', ubicacion='$ubicacion', ruc='$ruc' where id_empresa=$id");
        if ($sql==true) { ?>
            <script>
                $(function notification() {
                    new PNotify({
                        title: "CORRECTO",
                        type: "success",
                        text: "Los datos se han modificado correctamente.",
                        styling: "bootstrap3"
                    });
                });
            </script>
            <?php } else { ?>
                <script>
                    $(function notification() {
                        new PNotify({
                            title: "INCORRECTO",
                            type: "error",
                            text: "Error al modificar los datos.",
                            styling: "bootstrap3"
                        });
                    });
                </script>
                <?php }
        
    } else { ?>
        <script>
            $(function notification() {
                new PNotify({
                    title: "INCORRECTO",
                    type: "error",
                    text: "No se ha enviado el identificador",
                    styling: "bootstrap3"
                });
            });
        </script>
<?php }
}

?>