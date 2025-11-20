<?php 
if (!empty($_POST["btnmodificar"])) {
    if (!empty($_POST["txtclaveactual"]) and !empty($_POST["txtclavenueva"]) and !empty($_POST["txtid"]) and !empty($_POST["txtclavenueva2"])) {

        $claveactual=md5($_POST["txtclaveactual"]);
        $clavenueva=md5($_POST["txtclavenueva"]);
        $clavenueva2=md5($_POST["txtclavenueva2"]);
        $id=$_POST["txtid"];
        if ($clavenueva==$clavenueva2) {
            
        
        $verificarClaveActual=$conexion->query("select password from usuario where id_usuario=$id");
        if ($verificarClaveActual->fetch_object()->password==$claveactual) {
            $sql=$conexion->query("update usuario set password='$clavenueva' where id_usuario=$id");
            if ($sql==true) { ?>
                <script>
                    $(function notification() {
                        new PNotify({
                            title: "CORRECTO",
                            type: "success",
                            text: "La contraseña se ha modificado correctamente.",
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
                        text: "Error al modificar la contraseña",
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
                        text: "La contraseña actual es incorrecta",
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
                    text: "No coinciden las contraseñas nuevas",
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
                    text: "Los campos estan vacíos",
                    styling: "bootstrap3"
                });
            });
        </script>
<?php }
    
}

?>


<script>
    setTimeout(() => {
        window.history.replaceState(null, null, window.location.pathname);
    }, 0);
</script>