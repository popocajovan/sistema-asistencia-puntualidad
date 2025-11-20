<?php 

if (!empty($_GET["id"])) {
   $id=$_GET["id"];
   $sql=$conexion->query("delete from cargo where id_cargo=$id");
   if ($sql==true) {?>
    <script>
    $(function notification(){
        new PNotify({
            title:"CORRECTO",
            type:"error",
            text: "Cargo eliminado correctamente",
            styling: "bootstrap3"
        })
    })  
    </script>
<?php } else {?>
            <script>
            $(function notification(){
                new PNotify({
                    title:"INCORRECTO",
                    type:"error",
                    text: "Error al eliminar el cargo",
                    styling: "bootstrap3"
                })
            })  
            </script>
        <?php }?>
    <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname);
        }, 0);
    </script>
<?php }

?>