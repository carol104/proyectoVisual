# Sistema de Gestión de Estudiantes - UTA

Sistema integral de gestión académica desarrollado con arquitectura híbrida: **Aplicación Web (PHP MVC)** y **Aplicación de Escritorio (Java Swing)**, ambas conectadas a una misma base de datos MySQL para la administración de estudiantes y generación de reportes.

---

## 🛠️ Tecnologías Utilizadas

### Módulo Web
* **PHP** (8.x): Backend estructurado bajo el patrón Modelo-Vista-Controlador (MVC).
* **MySQL / MariaDB**: Motor de base de datos relacional.
* **jQuery & jQuery EasyUI**: Componentes interactivos para interfaz de usuario (DataGrid, Diálogos modales, validaciones en formularios).
* **FPDF (v1.86)**: Generación de reportes PDF dinámicos y reportes individuales por cédula.
* **PHPJasperXML & Geekcom PHPJasper**: Integración de reportes JasperReports (.jrxml) en PHP.
* **Composer**: Gestor de dependencias de PHP.

### Módulo Escritorio (Java)
* **Java SE** (JDK 8 / 17 / 21): Aplicación de escritorio.
* **Swing & NetBeans GUI Builder**: Interfaz gráfica de usuario con controles de eventos.
* **JDBC (MySQL Connector/J 9.1.0)**: Conexión persistente a MySQL mediante consultas preparadas (`PreparedStatement`).
* **JasperReports**: Visualización e impresión de reportes compilados (.jrxml / .jasper).

---

## 📁 Estructura del Proyecto

```plaintext
visual/
│
├── Controllers/                  # Controladores del sistema web (MVC)
│   ├── controller.php            # Controlador principal de navegación
│   ├── estudianteController.php  # Controlador CRUD de estudiantes con validaciones
│   ├── middleware.php            # Control de acceso y sesiones
│   ├── statusSesion.php          # Verificación de estado de sesión JSON
│   ├── validarDatosLogin.php     # Autenticación segura de usuarios
│   └── logout.php                # Cierre de sesión
│
├── Models/                       # Modelos y acceso a datos (PHP)
│   ├── conexion.php              # Clase de conexión a MySQL
│   ├── model.php                 # Enlaces de navegación entre vistas
│   ├── acceder.php               # Endpoint de listado (DataGrid)
│   ├── guardar.php               # Inserción con validación y sentencias preparadas
│   ├── editar.php                # Actualización con validación
│   └── borrar.php                # Eliminación con validación
│
├── Views/                        # Vistas web (PHP / HTML)
│   ├── template.php              # Plantilla base con cabecera y menú
│   ├── home.php                  # Pantalla de bienvenida e integrantes
│   ├── about.php                 # Información de nosotros
│   ├── services.php              # Módulo principal CRUD de estudiantes (EasyUI)
│   └── contact.php               # Información de contacto
│
├── Cuarto/                       # Proyecto Java NetBeans (Swing)
│   ├── src/cuarto/
│   │   ├── estudiantes.java      # Ventana principal JFrame con CRUD y controles
│   │   ├── Conexion.java         # Conexión JDBC a MySQL
│   │   └── ...                   # Clases auxiliares
│   ├── src/reportes/             # Diseños de reportes JasperReports (.jrxml / .jasper)
│   ├── plugins/                  # Conector mysql-connector-j-9.1.0.jar
│   └── nbproject/                # Configuración del proyecto NetBeans
│
├── css/                          # Hojas de estilo del sistema web
├── js/                           # Scripts de soporte JavaScript
├── jquery/                       # Librería jQuery y temas de EasyUI
├── fpdf186/                      # Librería FPDF
├── phpjasperxml-master/          # Librería PHPJasperXML
├── index.php                     # Punto de entrada web
├── login.php                     # Pantalla de inicio de sesión
├── reporte.php                   # Reporte general en PDF con FPDF
├── reporteIndividual.php         # Reporte individual filtrado por cédula con FPDF
├── ireporte.php                  # Reporte con PHPJasperXML
└── composer.json                 # Dependencias Composer
```

---

## 🗄️ Base de Datos

### Parámetros de Conexión
* **Servidor / Host:** `localhost`
* **Puerto:** `3306`
* **Base de datos:** `cuarto`
* **Usuario:** `root`
* **Contraseña:** *(vacía)*

### Script SQL de Inicialización

Ejecuta el siguiente script en **phpMyAdmin** o en la consola de MySQL:

```sql
CREATE DATABASE IF NOT EXISTS `cuarto` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cuarto`;

-- Tabla de estudiantes
CREATE TABLE IF NOT EXISTS `estudiantes` (
  `estCedula` VARCHAR(10) NOT NULL,
  `estNombre` VARCHAR(50) NOT NULL,
  `estApellido` VARCHAR(50) NOT NULL,
  `estTelefono` VARCHAR(10) NOT NULL,
  `estDireccion` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`estCedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de usuarios del sistema
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(50) NOT NULL UNIQUE,
  `clave` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos iniciales de prueba
INSERT INTO `usuarios` (`nombre`, `clave`) VALUES 
('admin', 'admin123'),
('usuario', '1234');

INSERT INTO `estudiantes` (`estCedula`, `estNombre`, `estApellido`, `estTelefono`, `estDireccion`) VALUES
('1804567890', 'Carlos', 'Perez', '0987654321', 'Av. Cevallos y Montalvo'),
('1710034065', 'Maria', 'Lopez', '0991234567', 'Ficoa Las Palmas');
```

---

## 🚀 Cómo Correr el Proyecto

### 1. Iniciar los Servicios Locales
1. Abre **XAMPP** (o WampServer / Laragon).
2. Inicia los módulos **Apache** y **MySQL**.

---

### 2. Ejecutar el Sistema Web (PHP)

#### Opción A: A través de XAMPP `htdocs` (Recomendada)
1. Coloca la carpeta `visual` dentro del directorio `htdocs` de XAMPP (ejemplo: `C:\xampp\htdocs\visual`).
2. Abre tu navegador web y entra a:
   ```
   http://localhost/visual/
   ```

#### Opción B: Mediante el Servidor Integrado de PHP
Si tienes PHP instalado en tu terminal:
```bash
cd c:\Users\USER\Documents\Visual\visual
php -S localhost:8000
```
Y abre en tu navegador:
```
http://localhost:8000
```

#### Credenciales para Iniciar Sesión
Para realizar cambios en la tabla (agregar, editar, eliminar):
* **Usuario:** `admin`
* **Contraseña:** `admin123`

---

### 3. Ejecutar la Aplicación de Escritorio (Java Swing)

1. Abre **NetBeans IDE** (o IntelliJ IDEA / Eclipse).
2. Ve a **File > Open Project...** y selecciona la carpeta **`Cuarto`** (`visual/Cuarto`).
3. Asegúrate de que las librerías del proyecto estén cargadas:
   * El conector `mysql-connector-j-9.1.0.jar` se encuentra en la carpeta `Cuarto/plugins/`.
4. Para la generación de reportes desde Java, copia los archivos `.jrxml` de `Cuarto/src/reportes/` a `C:\reportes\` (ruta que busca la aplicación para compilar JasperReports).
5. Haz clic derecho sobre `estudiantes.java` en el paquete `cuarto` y selecciona **Run File** (o `Shift + F6`).

---

## 🛡️ Controles y Validaciones Implementadas

### En Java Swing (`estudiantes.java`):
* **Restricción de Teclado en Tiempo Real (`KeyListeners`):**
  * **Cédula:** Solo permite dígitos numéricos y un máximo de 10 caracteres.
  * **Teléfono:** Solo permite dígitos numéricos y un máximo de 10 caracteres.
  * **Nombre y Apellido:** Solo permite letras (incluye acentos y 'ñ') y espacios; longitud máxima de 50 caracteres.
  * **Dirección:** Máximo 100 caracteres.
* **Validación de Cédula Ecuatoriana (Módulo 10):**
  * Algoritmo matemático para verificar la autenticidad de la cédula ecuatoriana antes de guardar.
* **Control de Duplicados:**
  * Previene duplicación de registros por clave primaria (`estCedula`).
* **Control de Flujo de Interfaz y Botones:**
  * Estados automáticos de botones (**Nuevo**, **Guardar**, **Editar / Actualizar**, **Borrar**, **Cancelar**).
  * En modo edición, la cédula se mantiene bloqueada para garantizar la integridad referencial.
* **Seguridad:** Consultas parametrizadas con `PreparedStatement`.

### En la Aplicación Web PHP:
* **Controlador MVC (`Controllers/estudianteController.php`):**
  * Verificación obligatoria de sesión activa antes de modificar datos.
  * Validación de tipo de datos y expresiones regulares en el servidor.
  * Manejo seguro contra inyecciones SQL (`$stmt->prepare()` y `bind_param()`).
* **Validaciones en Interfaz (EasyUI):**
  * Reglas de validación en tiempo real:
    * `cedulaEcuatoriana`: comprueba el algoritmo de 10 dígitos.
    * `soloLetras`: restringe a caracteres alfabéticos.
    * `telefono`: restringe a exactamente 10 números.
  * Respuestas asíncronas con mensajes descriptivos de éxito o error en ventanas emergentes (`messager`).

---

## 👥 Desarrolladores
Proyecto académico realizado por estudiantes de la Universidad Técnica de Ambato (@cuartoSoftware).
