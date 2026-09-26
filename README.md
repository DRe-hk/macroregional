# 🏆 JEDPA 2026 · Plataforma Deportiva Macrorregional (Sede Puno)

Plataforma web oficial desarrollada en **Laravel 13** para la administración, fixtures por jornadas, resultados en tiempo real, actas oficiales de mesa, clasificación deportiva y cuadro de honor de los **Juegos Escolares Deportivos y Paradeportivos 2026 · Etapa Macrorregional (Sede Puno)**.

Diseñada bajo los estándares de máxima legibilidad, velocidad y experiencia de usuario (UI limpia *White / Slate* con *Tailwind CSS* y *Alpine.js*), soporte de subcategorías agrupadas por disciplina, puntuación oficial reglamentaria (**RVM N° 092-2026-MINEDU**), control de acceso granular para delegados, carrusel dinámico en portada y pie de página institucional de la **Dirección Regional de Educación Puno - Oficina de Informática**.

- **Repositorio Oficial:** [https://github.com/DRe-hk/macroregional.git](https://github.com/DRe-hk/macroregional.git)
- **Base de Datos:** MySQL 8.0+ / MariaDB (Dump listo en `database/macroregional.sql`)

---

## 📑 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Reglas de Negocio y Sistema de Puntuación MINEDU](#-reglas-de-negocio-y-sistema-de-puntuación-minedu)
- [Deportes Oficiales y Subcategorías](#-deportes-oficiales-y-subcategorías)
- [Credenciales de Acceso](#-credenciales-de-acceso)
- [Puesta en Marcha Rápida](#-puesta-en-marcha-rápida)
  - [Opción A: Importar Base de Datos con HeidiSQL / phpMyAdmin (Recomendada)](#opción-a-importar-base-de-datos-con-heidisql--phpmyadmin-recomendada)
  - [Opción B: Consola Artisan](#opción-b-consola-artisan)
- [Mapa de Rutas y Endpoints](#-mapa-de-rutas-y-endpoints)
- [Seguridad y Control de Acceso Granular](#-seguridad-y-control-de-acceso-granular)
- [Guía de Despliegue en cPanel](#-guía-de-despliegue-en-cpanel)
- [Comandos de Desarrollo y Pruebas](#-comandos-de-desarrollo-y-pruebas)

---

## 🌟 Características Principales

1. **Gestión Dinámica de Branding desde Admin:**
   - El texto del logo (`logo_texto`), subtítulo (`logo_subtexto`), enlaces de escudo y pie de página (`footer_texto`) se editan en tiempo real desde el panel de control general sin modificar código.
2. **Carrusel Hero en Portada:**
   - La portada principal cuenta con un carrusel dinámico interactivo con rotación automática (autoplay de 5 segundos), pausa al interactuar, controles manuales e indicadores visuales.
   - Posibilidad de subir diapositivas con imágenes y textos directamente desde el panel de administración.
3. **Subcategorías Agrupadas por Disciplina:**
   - En la página principal solo se muestran las **14 disciplinas deportivas oficiales**.
   - Cada tarjeta contiene etiquetas de sus categorías por género (ej. *Fútbol Cat. B Varones*, *Cat. B Damas*, *Cat. C Varones*, etc.), navegables mediante pestañas dentro de cada deporte.
4. **Deportes Individuales sin Fixture:**
   - **Natación** y **Atletismo** se clasifican como `tipo = INDIVIDUAL`. No muestran tablas de partidos innecesarias; muestran directamente la sede, cronograma y podio oficial con medallas de Oro (1° Lugar / Ganador), Plata y Bronce.
5. **Evidencia Fotográfica y Actas de Mesa:**
   - Delegados y administradores pueden adjuntar la fotografía del acta de mesa oficial (`.jpg`, `.png`, `.webp`) firmada por los árbitros.
   - En la web pública se despliega el botón **"📄 Ver Acta Oficial / Evidencia"** con un visor modal interactivo para auditoría deportiva.
6. **Fixture con Fechas y Filtro por Jornadas:**
   - Los partidos incorporan fecha oficial, horario y cancha.
   - Barra de filtro por jornadas (*Todas las fechas*, *30 de setiembre*, *01 de octubre*, *02 de octubre*).

---

## ⚖️ Reglas de Negocio y Sistema de Puntuación MINEDU

De conformidad con las bases de los **Juegos Escolares Deportivos y Paradeportivos (RVM N° 092-2026-MINEDU)**, se eliminó cualquier ponderación artificial previa. La clasificación se ordena estrictamente por los puntos reales acumulados tras la disputa de los encuentros:

| Disciplina | Victoria | Empate | Derrota | W.O. (No Presentación) | Criterios de Desempate |
| :--- | :---: | :---: | :---: | :---: | :--- |
| **Fútbol y Futsal** | **3 pts** | **1 pt** | **0 pts** | **0 pts** (Marcador 3-0 en contra) | Puntos $\rightarrow$ Diferencia Goles $\rightarrow$ Goles a Favor |
| **Básquetbol** | **2 pts** | *No aplica* | **1 pt** | **0 pts** (Marcador 20-0 en contra) | Puntos $\rightarrow$ Gol Average $\rightarrow$ Puntos en contra |
| **Handball** | **2 pts** | **1 pt** | **0 pts** | **-2 pts** (Sanción reglamentaria) | Puntos $\rightarrow$ Diferencia Goles $\rightarrow$ Goles a Favor |
| **Voleibol y Vóley Playa** | **2-0: 3 pts**<br>**2-1: 2 pts** | *No aplica* | **1-2: 1 pt**<br>**0-2: 0 pts** | **0 pts** (Marcador 2-0 / sets a cero) | Puntos $\rightarrow$ Coeficiente de Sets $\rightarrow$ Coeficiente de Puntos |

---

## 🏅 Deportes Oficiales y Subcategorías

El cronograma oficial de la Sede Puno contempla las siguientes 14 disciplinas principales:

1. **Fútbol:** Cancha sintética UNA Puno (*Cat. B Varones, Cat. B Damas, Cat. C Varones, Cat. C Damas*).
2. **Futsal:** Coliseo UNA Puno (*Cat. B Varones, Cat. B Damas*).
3. **Básquetbol:** Coliseo Eduardo Rodríguez Ponce de León (*Cat. B Varones, Cat. B Damas, Cat. C Varones, Cat. C Damas*).
4. **Voleibol:** Coliseo IE GUE San Carlos (*Cat. B Varones, Cat. B Damas, Cat. C Varones, Cat. C Damas*).
5. **Handball:** Cancha sintética UNA Puno (*Cat. B Varones, Cat. B Damas*).
6. **Vóley Playa:** Cancha de Arena UNA Puno (*Cat. B Varones, Cat. B Damas*).
7. **Atletismo y Para-atletismo:** Estadio Enrique Torres Belón (Individual · Pruebas de pista y campo).
8. **Natación:** Piscina Municipal de Puno (Individual · Libre, espalda, pecho y mariposa).
9. **Ajedrez:** IE GUE San Carlos (Ritmo suizo escolar).
10. **Tenis de Mesa:** Coliseo Eduardo Rodríguez Ponce de León.
11. **Gimnasia:** Sede Puno.
12. **Taekwondo:** Sede Puno.
13. **Tenis de Campo:** Canchas de Tenis Puno.
14. **Paleta Frontón:** Canchas Oficiales Puno.

---

## 👥 Credenciales de Acceso

| Rol | Usuario | Contraseñas Admitidas | URL de Acceso | Alcance y Permisos |
| :--- | :--- | :--- | :--- | :--- |
| **Administrador General** | `admin` | `drep2026` o `admin123` | `/entrar` o `/admin` | Control absoluto: configuración institucional, logo, carrusel, delegaciones, disciplinas, podios, fixture y asignación de permisos granulares. |
| **Delegado General Puno** | `delegado_puno` | `puno2026` o `123456` | `/entrar` | Acceso al portal de delegado para todos los deportes donde compite DRE Puno. |
| **Delegado Fútbol** | `delegado.futbol` | `123456` | `/entrar` | Edición exclusiva de encuentros y nóminas de Fútbol (Cat. B y C). |
| **Delegado Básquet** | `delegado.basquet` | `123456` | `/entrar` | Edición exclusiva de encuentros y nóminas de Básquetbol. |
| **Delegado Voleibol** | `delegado.voley` | `123456` | `/entrar` | Edición exclusiva de encuentros de Voleibol y Vóley Playa. |
| **Delegado Futsal** | `delegado.futsal` | `123456` | `/entrar` | Edición exclusiva de encuentros de Futsal. |
| **Delegado Handball** | `delegado.handball` | `123456` | `/entrar` | Edición exclusiva de encuentros de Handball. |
| **Delegados Individuales** | `delegado.atletismo`<br>`delegado.natacion`<br>`delegado.ajedrez`<br>`delegado.tenismesa` | `123456` | `/entrar` | Registro de nóminas y control técnico de sus respectivas disciplinas. |

---

## 🚀 Puesta en Marcha Rápida

### Requisitos
- PHP 8.2 o superior con extensiones `pdo_mysql`, `mbstring`, `fileinfo`.
- Servidor MySQL 8.0+ o MariaDB (vía Laragon, XAMPP o Docker).
- Node.js y Composer.

---

### Opción A: Importar Base de Datos con HeidiSQL / phpMyAdmin (Recomendada)
1. Crea la base de datos `macroregional` en tu gestor MySQL.
2. Abre y ejecuta el archivo:
   ```text
   database/macroregional.sql
   ```
3. Verifica que tu archivo `.env` contenga la conexión:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=macroregional
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. ¡Listo! Todo el sistema quedará poblado con deportes, delegaciones y usuarios.

---

### Opción B: Consola Artisan
```bash
# 1. Instalar dependencias
composer install
npm install

# 2. Configurar entorno y clave de aplicación
cp .env.example .env
php artisan key:generate

# 3. Ejecutar migraciones y poblar datos oficiales
php artisan migrate:fresh --seed

# 4. Compilar assets frontend
npm run build

# 5. Iniciar servidor de desarrollo
php artisan serve
```

---

## 🗺️ Mapa de Rutas y Endpoints

### 🌐 Rutas Públicas
| Método | URL | Descripción |
| :--- | :--- | :--- |
| `GET` | `/` | Portada con Hero, carrusel dinámico y las 14 disciplinas oficiales. |
| `GET` | `/clasificacion` | Tabla general por delegación y clasificación individual por deporte/subcategoría. |
| `GET` | `/equipos` | Catálogo de las 8 delegaciones oficiales de la Macro Región Sur. |
| `GET` | `/campeones` | Palmarés acumulado de títulos, podios individuales y campeones consagrados. |
| `GET` | `/d/{slug}` | Vista de la disciplina: conmutador entre subcategorías, fixture por jornadas y tabla de posiciones. |

### 🔐 Autenticación Unificada
| Método | URL | Descripción |
| :--- | :--- | :--- |
| `GET` | `/entrar` | Pantalla de login (botón "Ingresar"). |
| `POST` | `/entrar` | Procesa credenciales con Rate Limiting (5 intentos/min). |
| `POST` | `/logout` | Cierre de sesión y regeneración de sesión. |

### 👤 Portal del Delegado (`/delegado`)
*Protegido por middleware `EnsureIsDelegado`.*
| Método | URL | Acción |
| :--- | :--- | :--- |
| `GET` | `/delegado` | Partidos asignados por permisos granulares y nómina oficial. |
| `POST` | `/delegado/marcador` | Registro de marcador, W.O. y subida de acta/evidencia oficial. |
| `POST` | `/delegado/atletas` | Inscripción de deportistas con DNI, número y rol. |
| `DELETE`| `/delegado/atletas/{id}` | Retiro de deportista de la nómina. |

### ⚙️ Panel de Administración (`/admin`)
*Protegido por middleware `EnsureIsAdmin`.*
| Método | URL | Acción |
| :--- | :--- | :--- |
| `GET` | `/admin` | Panel integral de gestión del torneo. |
| `POST` | `/admin/torneo` | Edición de textos de logo, subtexto, footer y porcentaje de avance. |
| `POST` | `/admin/torneo/carrusel` | Adición de nuevas diapositivas al carrusel Hero. |
| `DELETE`| `/admin/torneo/carrusel/{index}`| Eliminación de diapositiva del carrusel. |
| `POST` | `/admin/deportes` | Registro y edición de disciplinas y subcategorías. |
| `POST` | `/admin/deportes/{id}/podio` | Publicación de podio oficial (Oro, Plata, Bronce) para deportes individuales. |
| `POST` | `/admin/delegaciones` | Alta y edición de delegaciones oficiales. |
| `POST` | `/admin/partidos` | Programación de partidos con fecha, horario y cancha. |
| `POST` | `/admin/marcadores` | Carga de resultados oficiales y actas como administrador. |
| `POST` | `/admin/delegados/{id}/permisos`| Asignación de permisos granulares por deporte y partido. |

---

## 🔒 Seguridad y Control de Acceso Granular

1. **Aislamiento Granular por Delegado:**
   - La tabla pivote `delegado_disciplinas` define qué deportes puede gestionar cada delegado.
   - El sistema comprueba mediante `$user->puedeEditarPartido($partido)` la autorización estricta. Si intenta modificar un partido no asignado, el servidor responde con **`HTTP 403 Forbidden`**.
2. **Inspección de Archivos y Blindaje de Subidas (RCE Defense):**
   - Validación estricta del tipo MIME mediante `fileinfo`.
   - Nombres aleatorios criptográficos para evitar sobrescrituras y ataques de *Path Traversal*.
## 🔒 Seguridad y Control de Acceso Granular

1. **Aislamiento Granular por Delegado:**
   - La tabla pivote `delegado_disciplinas` define qué deportes puede gestionar cada delegado.
   - El sistema comprueba mediante `$user->puedeEditarPartido($partido)` la autorización estricta. Si intenta modificar un partido no asignado, el servidor responde con **`HTTP 403 Forbidden`**.
2. **Inspección de Archivos y Blindaje de Subidas (RCE Defense):**
   - Validación estricta del tipo MIME real mediante `fileinfo` (no se fía del nombre enviado por el cliente).
   - Nombres aleatorios criptográficos para evitar colisiones y ataques de *Path Traversal*.
   - Inhabilitación estricta de ejecución de scripts PHP en la carpeta pública de subidas mediante [`public/uploads/.htaccess`](public/uploads/.htaccess).
3. **Seguridad contra Inyecciones y XSS:**
   - Consultas parametrizadas al 100% mediante Eloquent ORM (cero concatenaciones SQL).
   - Escape automático de salida en plantillas Blade (`{{ ... }}`).
   - Cabeceras HTTP defensivas mediante [`SecurityHeadersMiddleware`](app/Http/Middleware/SecurityHeadersMiddleware.php): `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `X-XSS-Protection: 1; mode=block`.
4. **Protección de la Raíz en cPanel:**
   - El archivo [`.htaccess`](.htaccess) en la raíz bloquea cualquier intento de descarga de `.env`, `.git`, logs y archivos de configuración, además de redirigir de forma transparente las peticiones a la carpeta `public/`.
5. **Bloqueo de Destrucción Accidental de BD:**
   - El comando de reinicio masivo de BD en el controlador está protegido contra ejecución en entornos con `APP_ENV=production`.

---

## 📦 Guía de Despliegue en cPanel (Paso a Paso)

El proyecto cuenta con un paquete listo para producción comprimido en la raíz:
```text
jedpa_macroregional_cpanel.zip  (19.3 MB)
```
Este archivo ZIP incluye todas las dependencias (`vendor/`), los assets compilados (`public/build`), el script SQL de la base de datos (`database/macroregional.sql`), los archivos `.htaccess` de seguridad y la plantilla de entorno. **No se requiere instalar Composer ni Node.js en el servidor cPanel**.

---

### Paso 1: Configurar el Subdominio en cPanel

1. Ingresa a tu panel de control de **cPanel**.
2. Dirígete a la sección **Dominios** (Domains) $\rightarrow$ **Crear un nuevo dominio / subdominio** (ej: `deportes.drepuno.gob.pe`).
3. Configuración del directorio raíz (**Document Root**):
   - **Caso Recomendado:** Desmarca la casilla "Share document root" y define la ruta apuntando a la subcarpeta `public`:
     ```text
     /home/usuario/deportes/public
     ```
   - **Caso Alternativo (si cPanel fija `public_html/deportes`):**  
     Puedes subir el proyecto directamente en `public_html/deportes`. Gracias al archivo `.htaccess` incluido en la raíz del proyecto, el tráfico se redirigirá internamente a `public/` y todos los archivos confidenciales (`.env`, `storage`, código fuente) quedarán 100% protegidos contra accesos directos desde la web.

---

### Paso 2: Subir y Descomprimir el Proyecto

1. En cPanel, abre el **Administrador de Archivos** (File Manager).
2. Navega hasta el directorio de tu subdominio (ej: `/home/usuario/deportes` o `public_html/deportes`).
3. Haz clic en **Cargar** (Upload) en la barra superior y sube el archivo `jedpa_macroregional_cpanel.zip`.
4. Una vez completada la carga (barra en verde), regresa al Administrador de Archivos, selecciona el archivo ZIP y pulsa **Extraer** (Extract).
5. Verifica que se hayan descomprimido las carpetas principales (`app`, `bootstrap`, `config`, `database`, `public`, `resources`, `routes`, `storage`, `vendor`). Puedes eliminar el archivo ZIP una vez extraído para ahorrar espacio.

---

### Paso 3: Crear la Base de Datos e Importar los Datos Oficiales

1. En cPanel, ve a **Bases de datos MySQL** (MySQL Databases):
   - **Crear nueva base de datos:** Asigna un nombre (ej: `drepuno_macroregional`).
   - **Crear nuevo usuario MySQL:** Crea un usuario (ej: `drepuno_user`) con una contraseña segura.
   - **Añadir usuario a la base de datos:** Asocia el usuario a la base de datos marcando la casilla **TODOS LOS PRIVILEGIOS** (ALL PRIVILEGES) y haz clic en *Hacer cambios*.
2. En la sección de bases de datos de cPanel, abre **phpMyAdmin**:
   - En la columna izquierda, selecciona tu base de datos recién creada (`drepuno_macroregional`).
   - Haz clic en la pestaña superior **Importar** (Import).
   - En *Seleccionar archivo*, busca y sube el script ubicado en:
     ```text
     database/macroregional.sql
     ```
   - Pulsa el botón inferior **Continuar** (Import).
   - Se importarán todas las tablas, las 14 disciplinas, las subcategorías por género, los podios de natación y atletismo, el fixture y los usuarios administradores y delegados.

---

### Paso 4: Configurar el Archivo de Entorno `.env`

1. En el Administrador de Archivos de cPanel, asegúrate de activar la opción **"Mostrar archivos ocultos (dotfiles)"** en la esquina superior derecha (Configuración / Settings).
2. Localiza el archivo `.env.cpanel.example`, haz clic derecho sobre él y selecciona **Rename** para renombrarlo a:
   ```text
   .env
   ```
3. Edita el archivo `.env` con los datos de tu servidor:
   ```dotenv
   APP_NAME="JEDPA 2026 Macroregional"
   APP_ENV=production
   APP_KEY=base64:iB7YaC5FEZNyNEBzFVqMLbc/w2qs/ajOxCc9ERoGxkU=
   APP_DEBUG=false
   APP_URL=https://deportes.drepuno.gob.pe

   APP_LOCALE=es
   APP_FALLBACK_LOCALE=es

   # Conexión a la base de datos creada en el Paso 3
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=drepuno_macroregional
   DB_USERNAME=drepuno_user
   DB_PASSWORD=TuContrasenaSeguraDeMySQL

   SESSION_DRIVER=database
   SESSION_LIFETIME=120
   CACHE_STORE=database
   QUEUE_CONNECTION=sync
   FILESYSTEM_DISK=local
   ```
4. Guarda los cambios.

---

### Paso 5: Permisos de Carpetas y Seguridad

Verifica que las siguientes carpetas tengan permisos de lectura y escritura para el servidor web:

| Directorio | Permiso Recomendado | Propósito |
| :--- | :---: | :--- |
| `storage/` | **`775`** o **`755`** | Almacenamiento de logs de auditoría y sesiones. |
| `bootstrap/cache/` | **`775`** o **`755`** | Caché optimizada de la aplicación. |
| `public/uploads/` | **`775`** o **`755`** | Almacenamiento de actas de mesa y evidencias subidas por delegados. |

*(En el Administrador de Archivos de cPanel puedes hacer clic derecho sobre la carpeta $\rightarrow$ **Change Permissions**).*

---

### Paso 6: Versión de PHP y Extensiones en cPanel

1. En cPanel, ingresa a **Seleccionar Versión de PHP** (Select PHP Version) o **Administrador MultiPHP** (MultiPHP Manager).
2. Selecciona **PHP 8.2** o superior (8.2 / 8.3 / 8.4) para el subdominio.
3. Asegúrate de que las siguientes extensiones estándar se encuentren activadas:
   - `pdo_mysql`
   - `mbstring`
   - `fileinfo` (necesaria para la inspección segura de imágenes de actas)
   - `curl`
   - `openssl`
   - `bcmath`

---

### Paso 7: Comprobación y Acceso Inicial

1. Abre tu navegador e ingresa a la URL de tu subdominio (ej: `https://deportes.drepuno.gob.pe`).
2. Verifica la carga de la portada, el carrusel Hero y las 14 disciplinas oficiales agrupadas.
3. Haz clic en **"Ingresar"** (`/entrar`) y prueba el acceso con las credenciales de administrador:
   - **Usuario:** `admin`
   - **Contraseña:** `drep2026` o `admin123`
4. ¡El sistema se encuentra listo para la cobertura deportiva en vivo!

---

## 🛠️ Solución de Problemas Comunes en cPanel

- **Error HTTP 500:** Verifica que el archivo `.env` exista y no tenga errores de sintaxis en las contraseñas con caracteres especiales (si tu clave tiene caracteres como `#` o `$`, enciérrala entre comillas dobles `"tu_clave#123"`). Revisa el log en `storage/logs/laravel.log`.
- **Los estilos no cargan:** Asegúrate de que el `APP_URL` en tu `.env` coincida exactamente con la URL (con `https://`) del subdominio.
- **Error de conexión a la base de datos (Access denied):** Verifica en cPanel que hayas asociado el usuario a la base de datos en *Bases de Datos MySQL* y otorgado **ALL PRIVILEGES**.
- **No se pueden subir fotos de actas:** Revisa que la carpeta `public/uploads/evidencias/` tenga permisos `775` o `755`.

---

## 🧪 Comandos de Desarrollo y Pruebas

```bash
# Ejecutar suite completa de 15 pruebas PHPUnit (100% aprobadas)
php vendor/phpunit/phpunit/phpunit

# Formatear el código con Laravel Pint
vendor/bin/pint --format agent

# Compilar assets frontend listos para producción
npm run build

# Limpiar caché de rutas, vistas y configuración
php artisan optimize:clear
```

---

## 🏛️ Créditos Institucionales

**Dirección Regional de Educación Puno - Oficina de Informática**  
Todos los derechos reservados © 2026.
