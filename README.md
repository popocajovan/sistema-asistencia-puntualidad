# 📋 Sistema de Asistencia y Puntualidad

Sistema web desarrollado en **PHP** para el control de asistencia y puntualidad de empleados. Permite registrar entradas y salidas mediante **DNI** o **código QR**, gestionar empleados, generar reportes en PDF y visualizar estadísticas del día en tiempo real.

---

## 📸 Capturas de pantalla

> <img width="1919" height="828" alt="Captura de pantalla 2025-11-19 201911" src="https://github.com/user-attachments/assets/785a2a5f-0881-4fc2-a8f5-a8a15bd60f9c" />
<img width="1919" height="827" alt="Captura de pantalla 2025-11-19 202123" src="https://github.com/user-attachments/assets/b31d8736-5d2c-453e-b98d-2232c461a047" />
<img width="1919" height="829" alt="Captura de pantalla 2025-11-19 202147" src="https://github.com/user-attachments/assets/446fa250-51e9-46b1-a527-1d2b80d997cf" />
<img width="1919" height="832" alt="Captura de pantalla 2025-11-19 202446" src="https://github.com/user-attachments/assets/b949d216-7e1b-4732-9e8c-3aaa7310bcf7" />
<img width="1919" height="826" alt="Captura de pantalla 2025-11-19 203951" src="https://github.com/user-attachments/assets/2b25436e-5777-4224-854e-41e122f5b663" />
Es importante colocar el correo, debido a que en este formato es como se envian el código QR para que inicien sesión los empleados. 
<img width="1519" height="627" alt="Captura de pantalla 2025-11-19 204253" src="https://github.com/user-attachments/assets/d5cbadaf-2839-42ac-a21a-7bd05a94c529" />

---

## ✨ Características principales

- ✅ Registro de **entrada y salida** por DNI o escaneo de código QR desde la cámara
- ✅ Clasificación automática de asistencia: **puntual**, **retardo** o **inasistencia**
- ✅ Envío de **código QR por correo electrónico** a cada empleado al registrarlo
- ✅ Reenvío de QR desde el panel de administración
- ✅ **Gráfica en tiempo real** del estado de asistencia del día
- ✅ Generación de **reportes PDF** de asistencia y empleados (FPDF)
- ✅ Gestión completa de **empleados, cargos y usuarios**
- ✅ Panel de administración con login, perfil y cambio de contraseña
- ✅ Notificaciones visuales y sonoras al registrar asistencia

---

## 🛠️ Tecnologías utilizadas

| Capa | Tecnología |
|------|-----------|
| Backend | PHP (patrón MVC) |
| Base de datos | MySQL |
| Frontend | HTML, CSS, Bootstrap |
| Gráficas | C3.js / D3.js |
| Tablas | DataTables, Bootstrap Table |
| PDF | FPDF |
| Correo | PHPMailer (SMTP Gmail) |
| QR | jsQR (escaneo), PHP QR Code (generación) |
| Alertas | PNotify, SweetAlert |

---

## 📁 Estructura del proyecto

```
sistema-asistencia-puntualidad/
│
├── index.php                         # Página pública de registro de asistencia (DNI / QR)
├── escaner.mp3                       # Sonido de confirmación al escanear QR
│
├── modelo/
│   └── conexion.php                  # Configuración de conexión a la base de datos
│
├── controlador/
│   ├── controlador_registrar_asistencia.php
│   ├── controlador_registrar_empleado.php
│   ├── controlador_registrar_cargo.php
│   ├── controlador_registrar_usuario.php
│   ├── controlador_modificar_empleado.php
│   ├── controlador_modificar_cargo.php
│   ├── controlador_modificar_usuario.php
│   ├── controlador_modificar_empresa.php
│   ├── controlador_modificar_perfil.php
│   ├── controlador_eliminar_empleado.php
│   ├── controlador_eliminar_cargo.php
│   ├── controlador_eliminar_usuario.php
│   ├── Controlador_eliminar_asistencia.php
│   ├── controlador_cambiar_clave.php
│   ├── controlador_cerrar_sesion.php
│   ├── login.php
│   ├── reenviar_correo.php           # Reenvío de QR por correo (PHPMailer + PHP QR Code)
│   └── api_grafica_asistencia.php    # API JSON para la gráfica de asistencia del día
│
├── vista/
│   ├── login/login.php
│   ├── layout/ (sidebar, topbar, footer)
│   ├── inicio.php                    # Dashboard con gráfica del día
│   ├── empleado.php
│   ├── cargo.php
│   ├── usuario.php
│   ├── reporte_asistencia.php
│   ├── grafica_asistencia.php
│   ├── perfil.php
│   ├── cambiarClave.php
│   ├── acerca.php
│   ├── PHPMailer-master/             # Librería PHPMailer
│   ├── phpqrcode/                    # Librería PHP QR Code
│   └── fpdf/                         # Librería FPDF para reportes PDF
│
└── public/
    └── app/publico/                  # CSS y JS de librerías (Bootstrap, DataTables, etc.)
```

---

## ⚙️ Requisitos previos

- PHP **7.4** o superior
- MySQL
- Servidor web: **Apache** o **Nginx** (recomendado XAMPP / WAMP para desarrollo local)
- Extensión `mysqli` habilitada en PHP
- Cuenta Gmail con **contraseña de aplicación** habilitada (para envío de correos)

---

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/popocajovan/sistema-asistencia-puntualidad.git
```

Coloca la carpeta dentro del directorio raíz de tu servidor web (por ejemplo `C:/xampp/htdocs/sis-asistencia` en XAMPP).

### 2. Crear la base de datos

Importa el archivo SQL incluido en el proyecto (si existe) o crea manualmente la base de datos `sis_asistencia` con las tablas necesarias:

- `empleado` (id_empleado, nombre, apellido, dni, correo, id_cargo, ...)
- `asistencia` (id_asistencia, id_empleado, entrada, salida)
- `cargo` (id_cargo, nombre_cargo)
- `usuario` (id_usuario, usuario, clave, ...)

### 3. Configurar la conexión a la base de datos

Edita el archivo `modelo/conexion.php`:

```php
<?php
$conexion = new mysqli("localhost", "root", "TU_CONTRASEÑA", "sis_asistencia", "3306");
$conexion->set_charset("utf8");
?>
```

### 4. Configurar el correo (PHPMailer)

Edita `controlador/reenviar_correo.php` con tus credenciales de Gmail:

```php
$mail->Username   = 'tu_correo@gmail.com';
$mail->Password   = 'tu_contraseña_de_aplicacion';
```

> ⚠️ **Importante:** Usa una [contraseña de aplicación de Google](https://myaccount.google.com/apppasswords), no tu contraseña normal. Debes tener habilitada la verificación en dos pasos.

### 5. Ajustar zona horaria (opcional)

En `controlador/api_grafica_asistencia.php` y `controlador/controlador_registrar_asistencia.php`, la zona horaria está configurada para **America/Mexico_City**. Cámbiala si es necesario:

```php
date_default_timezone_set("America/Mexico_City");
```

### 6. Acceder al sistema

- **Registro de asistencia (público):** `http://localhost/sis-asistencia/`
- **Panel de administración:** `http://localhost/sis-asistencia/vista/login/login.php`

---

## 🖥️ Uso

### Registro de asistencia (página pública)

1. El empleado ingresa su **DNI** o escanea su **código QR** con la cámara.
2. Presiona **ENTRADA** o **SALIDA** (también con las teclas `→` y `←`).
3. El sistema valida el DNI, registra el movimiento y muestra una notificación.

### Panel de administración

Desde el panel puedes:

- **Empleados:** registrar, modificar, eliminar y reenviar QR por correo.
- **Cargos:** gestionar los puestos de trabajo.
- **Usuarios:** administrar los usuarios con acceso al sistema.
- **Asistencia:** consultar registros, eliminar entradas y generar reportes PDF.
- **Gráfica del día:** ver en tiempo real cuántos empleados están presentes, tienen retardo o inasistencia.

---

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**. Consulta el archivo [LICENSE](LICENSE) para más detalles.

---

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Si encuentras algún bug o tienes una mejora, abre un *issue* o envía un *pull request*.
