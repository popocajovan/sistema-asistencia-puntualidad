<?php 
session_start();
session_destroy();
header("location:/sis-asistencia/vista/login/login.php");
?>