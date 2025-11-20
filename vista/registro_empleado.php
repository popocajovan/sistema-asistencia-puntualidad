<?php
session_start();
if (empty($_SESSION['nombre']) && empty($_SESSION['apellido'])) {
    header('location:login/login.php');
    exit;
}

// Se incluye el topbar y sidebar (manteniendo la funcionalidad previa)
require('./layout/topbar.php');
require('./layout/sidebar.php');
?>
<!-- Inicio del contenido principal -->
<div class="page-content">
    <h4 class="text-center text-secondary">REGISTRO DE EMPLEADOS</h4>
    <?php
    include '../modelo/conexion.php';
    
    // Incluir PHPMailer usando la ruta de tu proyecto
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../../../www/sis-asistencia/vista/PHPMailer-master/src/Exception.php';
    require '../../../www/sis-asistencia/vista/PHPMailer-master/src/PHPMailer.php';
    require '../../../www/sis-asistencia/vista/PHPMailer-master/src/SMTP.php';
    
    // Incluir la librería PHP QR Code
    require_once '../../../www/sis-asistencia/vista/phpqrcode/qrlib.php';

    if (!empty($_POST["btnregistrar"])) {
        if (!empty($_POST["txtnombre"]) && !empty($_POST["txtapellido"]) && !empty($_POST["txtcorreo"]) && !empty($_POST["txtcargo"])) {

            $nombre   = $_POST["txtnombre"];
            $apellido = $_POST["txtapellido"];
            $correo   = $_POST["txtcorreo"];
            $cargo    = $_POST["txtcargo"];
            
            // Inserción en la base de datos; se asume que el trigger asigna automáticamente el DNI
            $sql = $conexion->query("INSERT INTO empleado(nombre, apellido, correo, cargo) VALUES ('$nombre', '$apellido', '$correo', '$cargo')");
            
            if ($sql == true) {
                // Obtener el ID de inserción y luego el DNI asignado por el trigger
                $idEmpleado = $conexion->insert_id;
                $queryDNI = $conexion->query("SELECT dni FROM empleado WHERE id_empleado = $idEmpleado");
                if ($queryDNI && $row = $queryDNI->fetch_object()){
                    $dni = $row->dni;
                } else {
                    $dni = 'Desconocido';
                }
                
                // Definir carpeta y nombre para el archivo QR
                $dirTemp = '../temp/';
                if (!file_exists($dirTemp)) {
                    mkdir($dirTemp, 0777, true);
                }
                $qrFile = $dirTemp . 'qr_' . $dni . '.png';
                
                // Generar el código QR con el DNI como dato
                // Parámetros: dato, nombre de archivo, nivel de corrección, tamaño (escala)
                QRcode::png($dni, $qrFile, QR_ECLEVEL_L, 4);

                // Envío de correo al registrar usando Gmail como SMTP
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';  
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'jovanincorporation@gmail.com';  // Tu cuenta de Gmail
                    $mail->Password   = 'xvlh uhot dvfv dsid';             // Contraseña de aplicación
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;
                    
                    // Se establece el remitente
                    $mail->setFrom('jovanincorporation@gmail.com', 'Asistec - Bienvenido');
                    // Se añade el destinatario
                    $mail->addAddress($correo, "$nombre $apellido");
                    
                    // Asunto y cuerpo: incluir mensaje de bienvenida y el DNI
                    $mail->Subject = 'Bienvenido a Asistec';
                    
                    // Cuerpo en formato HTML para poder incrustar información de forma más amigable
                    $mail->isHTML(true);
                    $mailContent = "
                        <h2>Bienvenido/a a Asistec</h2>
                        <p>Estimado(a) $nombre $apellido,</p>
                        <p>Gracias por unirte a nuestra empresa. Tu DNI asignado es: <strong>$dni</strong></p>
                        <p>Adjuntamos tu código QR, el cual se generó a partir de tu DNI, para fines de identificación.</p>
                        <p>Saludos,<br>Equipo de Asistec</p>
                    ";
                    $mail->Body = $mailContent;
                    
                    // Adjuntar el archivo QR al correo
                    $mail->addAttachment($qrFile, 'codigoQR_'.$dni.'.png');
                    
                    $mail->send();
                    ?>
                    <script>
                        $(function notification(){
                            new PNotify({
                                title: "CORRECTO",
                                type: "success",
                                text: "El empleado se ha registrado correctamente y el correo fue enviado.",
                                styling: "bootstrap3"
                            });
                        });
                    </script>
                    <?php
                } catch (Exception $e) {
                    ?>
                    <script>
                        $(function notification(){
                            new PNotify({
                                title: "ADVERTENCIA",
                                type: "warning",
                                text: "El empleado se registró, pero hubo un error al enviar el correo: <?php echo $mail->ErrorInfo; ?>",
                                styling: "bootstrap3"
                            });
                        });
                    </script>
                    <?php
                }
            } else {
                ?>
                <script>
                    $(function notification(){
                        new PNotify({
                            title: "INCORRECTO",
                            type: "error",
                            text: "Error al registrar empleado.",
                            styling: "bootstrap3"
                        });
                    });
                </script>
                <?php
            }
        } else {
            ?>
            <script>
                $(function notification(){
                    new PNotify({
                        title: "ERROR",
                        type: "error",
                        text: "Los campos están vacíos.",
                        styling: "bootstrap3"
                    });
                });
            </script>
            <?php
        }
    }
    ?>
    <div class="row">
        <form action="" method="POST">
            <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
                <input type="text" placeholder="Nombre" class="input input__text" name="txtnombre">
            </div>
            <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
                <input type="text" placeholder="Apellido" class="input input__text" name="txtapellido">
            </div>
            <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
                <input type="text" placeholder="Correo Electrónico" class="input input__text" name="txtcorreo">
            </div>
            <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
                <select name="txtcargo" class="input input__select"> 
                    <option value="">Seleccionar...</option>
                    <?php 
                    $sqlCargo = $conexion->query("SELECT * FROM cargo");
                    while($datos = $sqlCargo->fetch_object()){ ?>
                        <option value="<?= $datos->id_cargo ?>"><?= $datos->nombre ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="text-right p-2">
                <a href="empleado.php" class="btn btn-secondary btn-rounded">Atrás</a>
                <button type="submit" value="ok" name="btnregistrar" class="btn btn-primary btn-rounded">Registrar</button>
            </div>
        </form>
    </div>
</div>
</div>
<!-- Fin del contenido principal -->
<?php require('./layout/footer.php'); ?>