<?php
include '../modelo/conexion.php';

// Incluir PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../../www/sis-asistencia/vista/PHPMailer-master/src/Exception.php';
require '../../../www/sis-asistencia/vista/PHPMailer-master/src/PHPMailer.php';
require '../../../www/sis-asistencia/vista/PHPMailer-master/src/SMTP.php';

// Incluir PHP QR Code
require_once '../../../www/sis-asistencia/vista/phpqrcode/qrlib.php';

if (isset($_GET['reenviar'])) {
    $idEmpleado = intval($_GET['reenviar']);

    // Obtener los datos del empleado
    $sql = $conexion->query("SELECT nombre, apellido, correo, dni FROM empleado WHERE id_empleado = $idEmpleado");
    if ($sql && $empleado = $sql->fetch_object()) {
        $nombre = $empleado->nombre;
        $apellido = $empleado->apellido;
        $correo = $empleado->correo;
        $dni = $empleado->dni;

        // Generar QR temporal
        $rutaQR = "../../../www/sis-asistencia/temp_qr/";
        if (!file_exists($rutaQR)) {
            mkdir($rutaQR, 0777, true);
        }
        $nombreQR = "qr_" . $dni . ".png";
        $rutaCompletaQR = $rutaQR . $nombreQR;

        QRcode::png($dni, $rutaCompletaQR, QR_ECLEVEL_H, 10, 2);

        // Configurar y enviar correo
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jovanincorporation@gmail.com';
            $mail->Password   = 'xvlh uhot dvfv dsid'; // contraseña de aplicación
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('jovanincorporation@gmail.com', 'Asistec');
            $mail->addAddress($correo, "$nombre $apellido");
            $mail->Subject = 'Bienvenido/a a Asistec';
            $mail->Body = "
                ¡Hola, $nombre $apellido! 👋\n\n
                Bienvenido/a a Asistec.\n
                Tu DNI es: $dni\n\n
                Adjuntamos tu código QR personal. Guárdalo y mantenlo seguro.
            ";

            $mail->addAttachment($rutaCompletaQR, 'codigo_qr.png');

            $mail->send();

            echo "<script>
                    $(function notification(){
                        new PNotify({
                            title: 'CORRECTO',
                            type: 'success',
                            text: 'Correo reenviado correctamente a $correo',
                            styling: 'bootstrap3'
                        });
                    });
                    setTimeout(function(){
                        window.location.href = 'empleado.php';
                    }, 1500);
                  </script>";
        } catch (Exception $e) {
            echo "<script>
                    $(function notification(){
                        new PNotify({
                            title: 'ADVERTENCIA',
                            type: 'warning',
                            text: 'Error al reenviar el correo: {$mail->ErrorInfo}',
                            styling: 'bootstrap3'
                        });
                    });
                    setTimeout(function(){
                        window.location.href = 'empleado.php';
                    }, 3000);
                  </script>";
        }

        // Eliminar QR generado (opcional)
        if (file_exists($rutaCompletaQR)) {
            unlink($rutaCompletaQR);
        }

    } else {
        echo "<script>
                $(function notification(){
                    new PNotify({
                        title: 'ERROR',
                        type: 'error',
                        text: 'Empleado no encontrado.',
                        styling: 'bootstrap3'
                    });
                });
                setTimeout(function(){
                    window.location.href = 'empleado.php';
                }, 1500);
              </script>";
    }
}
?>
