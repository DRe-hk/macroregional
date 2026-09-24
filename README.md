# 🏆 Plataforma Deportiva Macroregional 2026

Plataforma web oficial desarrollada en **Laravel 13** para la administración, fixtures, resultados en vivo, clasificación en tiempo real y registro de nóminas de atletas de la **Competencia Deportiva Macroregional 2026**.

Diseñada bajo los estándares de máxima legibilidad, velocidad y experiencia de usuario (UI limpia *White/Slate*), **sin pie de página** (footer), con arquitectura **100% directa por disciplina (sin series)**, acceso administrativo protegido, defensas de seguridad multicapa y optimizada para ejecutarse en entornos **Laragon** con **MySQL 8.0+**.

Repositorio oficial: [https://github.com/DRe-hk/macroregional.git](https://github.com/DRe-hk/macroregional.git)

---

## 📑 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Reglas de Negocio y Seguridad](#-reglas-de-negocio-y-seguridad)
- [Credenciales de Acceso](#-credenciales-de-acceso)
- [Puesta en Marcha con MySQL en Laragon](#-puesta-en-marcha-con-mysql-en-laragon)
  - [Opción 1: Carga directa en HeidiSQL (Recomendada)](#opción-1-carga-directa-en-heidisql-recomendada)
  - [Opción 2: Migraciones y Seeders vía Terminal](#opción-2-migraciones-y-seeders-vía-terminal)
- [Mapa de Rutas y Endpoints](#-mapa-de-rutas-y-endpoints)
- [Estructura de Base de Datos](#-estructura-de-base-de-datos)
- [Servicio de Clasificación Automática](#-servicio-de-clasificación-automática)
- [Seguridad y Protección contra Vulnerabilidades](#-seguridad-y-protección-contra-vulnerabilidades)
- [Comandos de Desarrollo y Pruebas](#-comandos-de-desarrollo-y-pruebas)
- [Arquitectura del Proyecto](#-arquitectura-del-proyecto)
- [Licencia](#-licencia)

---

## 🌟 Características Principales

- **Diseño Minimalista e Impecable:** Interfaz en tema claro puro (*White / Slate*), tipografía *Instrument Sans*, alto contraste y sin pie de página en ninguna vista.
- **Enfoque Deportivo Unificado (Sin Series):**
  - Cada disciplina deportiva gestiona sus encuentros, llaves y tabla de posiciones de forma directa e independiente.
  - 6 Disciplinas oficiales: Fútbol Libre, Vóley Damas, Futsal Varones, Futsal Damas, Vóley Mixto y Básquetbol.
  - Fotografía de escenario/sede de referencia por deporte.
  - Tabla de posiciones individual por deporte con cálculo automático de PJ, PG, PE, PP, GF, GC, DG y PTS.
  - Medallero General Macroregional con podio de Oro, Plata y Bronce.
  - Directorio de las 15 delegaciones provinciales / UGELs con escudo oficial editable.
- **Interactividad Reactiva con Alpine.js:** Selector instantáneo entre Llaves de Eliminación (*Brackets*) y Tabla de Posiciones, pestañas administrativas fluidas y modales sin recarga innecesaria.
- **Portada Oficial JEDPA:** Imagen oficial integrada y editable desde el panel de administración.

---

## 🛡️ Reglas de Negocio y Seguridad

1. **Privacidad del Panel Administrativo:**
   - La barra de navegación pública **no contiene enlaces ni botones hacia `/admin`**.
   - Solo existe un botón discreto **"Ingresar"** que apunta a `/entrar`.
   - La ruta `/admin` solo es accesible escribiendo la URL manualmente o tras autenticarse como administrador general.
2. **Autenticación Unificada y Redirección Inteligente:**
   - En [`/entrar`](/entrar) inician sesión tanto administradores como delegados.
   - Detección de rol con redirección automática:
     - `ADMIN`: Redirigido a [`/admin`](/admin).
     - `DELEGADO`: Redirigido a [`/delegado`](/delegado).
3. **Aislamiento Estricto por Delegación (Prevención IDOR):**
   - Un delegado únicamente puede ver y cargar resultados de los encuentros en los que compite su propia delegación asignada.
   - No puede alterar partidos de otras delegaciones ni nóminas ajenas.
4. **Nómina Oficial de Atletas:**
   - Cada delegado inscribe a sus atletas con DNI, Nombres y Apellidos, Número de Camiseta y Rol.
5. **Cero Footers:**
   - Todas las vistas están diseñadas para maximizar el área de trabajo y visualización deportiva.

---

## 👥 Credenciales de Acceso

El sistema incluye cuentas precargadas en la base de datos:

| Rol | Delegación | Usuario | Contraseña | URL de Acceso | Alcance y Permisos |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Administrador** | Todas | `admin` | `drep2026` | `/admin` o `/entrar` | Control total del torneo, disciplinas, delegaciones, fixture, marcadores y delegados. |
| **Delegado** | UGEL Puno | `delegado_puno` | `puno2026` | `/entrar` | Carga de resultados de Puno y nómina de atletas de Puno. |
| **Delegado** | UGEL San Román | `delegado_sanroman` | `sanroman2026` | `/entrar` | Carga de resultados de San Román y nómina de atletas de San Román. |

---

## 🚀 Puesta en Marcha con MySQL en Laragon

### Requisitos Previos
- **Laragon** con PHP 8.2 o superior y servicio **MySQL** activo.
- Extensión PHP `pdo_mysql` habilitada.
- Composer y Git.

---

### Opción 1: Carga directa en HeidiSQL (Recomendada)

1. En Laragon, pulsa en **Database** (o abre **HeidiSQL**).
2. Conéctate a tu servidor local (Host: `127.0.0.1`, Usuario: `root`, Contraseña: vacía).
3. Ve al menú **Archivo** $\rightarrow$ **Cargar archivo SQL...** y selecciona:
   ```
   database/macroregional.sql
   ```
4. Presiona **F9** (o el botón azul de Ejecutar).
5. ¡Listo! La base de datos `macroregional`, todas las tablas, relaciones de integridad y datos semilla quedarán creados e indexados al instante.

---

### Opción 2: Migraciones y Seeders vía Terminal

Si prefieres usar la consola de Artisan:

1. Configura tu `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=macroregional
   DB_USERNAME=root
   DB_PASSWORD=
   ```
2. Ejecuta las migraciones y el seeder oficial:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

---

## 🗺️ Mapa de Rutas y Endpoints

### 🌐 Rutas Públicas
| Método | URL | Descripción |
| :--- | :--- | :--- |
| `GET` | `/` | Portada principal con portada oficial JEDPA, métricas y disciplinas deportivas. |
| `GET` | `/clasificacion` | Medallero General Macroregional con selector de tablas por disciplina. |
| `GET` | `/equipos` | Catálogo de las 15 delegaciones provinciales con sus escudos oficiales. |
| `GET` | `/campeones` | Cuadro de campeones oficiales por disciplina deportiva. |
| `GET` | `/d/{slug}` | Vista de la disciplina: conmutador entre Llaves (*Brackets*) y Tabla de Posiciones. |

### 🔐 Autenticación
| Método | URL | Descripción |
| :--- | :--- | :--- |
| `GET` | `/entrar` | Pantalla de inicio de sesión unificada. |
| `POST` | `/entrar` | Procesa credenciales con **Rate Limiting** (5 intentos/minuto). |
| `POST` | `/logout` | Cierre de sesión seguro con regeneración de token CSRF. |

### 👤 Portal del Delegado (`/delegado`)
*Protegido por middleware `EnsureIsDelegado`.*
| Método | URL | Acción |
| :--- | :--- | :--- |
| `GET` | `/delegado` | Panel principal del delegado: partidos de su UGEL y nómina de atletas. |
| `POST` | `/delegado/marcador` | Actualización de resultado de un partido donde participa su delegación. |
| `POST` | `/delegado/atletas` | Inscripción de un deportista en la nómina de su delegación. |
| `DELETE`| `/delegado/atletas/{id}` | Retiro de un deportista de la nómina propia. |

### ⚙️ Panel de Administración (`/admin`)
*Protegido por middleware `EnsureIsAdmin`.*
| Método | URL | Acción |
| :--- | :--- | :--- |
| `GET` | `/admin` | Panel integral con 6 pestañas de gestión deportiva. |
| `POST` | `/admin/torneo` | Edición del torneo, logo oficial y foto de portada. |
| `POST` | `/admin/deportes` | Registro y edición de disciplinas con foto de referencia y escenario. |
| `DELETE`| `/admin/deportes/{id}` | Eliminación de disciplina y sus partidos. |
| `POST` | `/admin/delegaciones` | Alta y edición de delegaciones con escudo oficial. |
| `DELETE`| `/admin/delegaciones/{id}`| Eliminación de delegación. |
| `POST` | `/admin/partidos` | Programación de partidos directamente por disciplina deportiva. |
| `POST` | `/admin/marcadores` | Carga de marcadores finales y actualización de tabla. |
| `DELETE`| `/admin/partidos/{id}` | Eliminación de un partido del fixture. |
| `POST` | `/admin/delegados` | Creación de credenciales para delegados provinciales. |
| `DELETE`| `/admin/delegados/{id}` | Eliminación de cuenta de delegado. |
| `POST` | `/admin/reiniciar` | Reinicio de la base de datos al estado inicial. |

---

## 🗄️ Estructura de Base de Datos

El modelo relacional normalizado **prescinde de la capa intermedia de series**, vinculando los partidos directamente con cada disciplina:

```text
┌──────────────┐       ┌─────────────────┐       ┌────────────────┐
│   torneos    │       │  delegaciones   │       │  disciplinas   │
└──────────────┘       └────────┬────────┘       └───────┬────────┘
                                │                        │
                       ┌────────▼────────┐               │
                       │     users       │               │
                       │ (ADMIN/DELEGADO)│               │
                       └─────────────────┘               │
                                │                        │
                       ┌────────▼────────┐       ┌───────▼────────┐
                       │     nominas     │       │    partidos    │
                       └─────────────────┘       └────────────────┘
```

- **`torneos`**: Nombre, sede, año, portada oficial (`portada_url`) y logo (`logo_url`).
- **`delegaciones`**: Las 15 UGELs con su estadística deportiva acumulada y logo oficial (`logo_url`).
- **`disciplinas`**: Slug, nombre, categoría, color de acento, sede principal y foto de referencia (`foto_referencia_url`).
- **`partidos`**: Fixture directo por disciplina (`disciplina_id`), ronda, equipo local, visitante, goles, estado y horario.
- **`nominas`**: Nómina de atletas por delegación y disciplina con DNI, dorsal y rol.
- **`users`**: Administradores y delegados con rol, delegación asignada y estado activo.

---

## ⚡ Servicio de Clasificación Automática

La clase [`app/Services/TournamentService.php`](app/Services/TournamentService.php) computa el rendimiento deportivo:

1. **Tabla por Disciplina:** Computa los partidos propios de cada deporte ordenando por:
   $$\text{Puntos (PTS)} \rightarrow \text{Diferencia de Goles (DG)} \rightarrow \text{Goles a Favor (GF)} \rightarrow \text{Nombre}$$
2. **Criterios de Puntuación:**
   - Victoria: **3 puntos**
   - Empate: **1 punto**
   - Derrota: **0 puntos**
3. **Consistencia General:** Al actualizar cualquier marcador, se sincroniza en cascada tanto la tabla individual de la disciplina como la clasificación acumulada general.

---

## 🔒 Seguridad y Protección contra Vulnerabilidades

El proyecto implementa medidas de seguridad defensiva a través de múltiples capas:

1. **Protección contra Subida de Archivos Arbitrarios (RCE Defense):**
   - Inspección del tipo MIME real mediante `fileinfo` (`$file->extension()`) en lugar del nombre enviado por el cliente.
   - Lista blanca estricta de extensiones seguras (`jpg`, `jpeg`, `png`, `webp`, `avif`).
   - Nombres de archivo criptográficamente aleatorios (`bin2hex(random_bytes(8))`) para prevenir colisiones y *Path Traversal*.
   - Archivo de protección [`public/uploads/.htaccess`](public/uploads/.htaccess) que inhabilita los motores de PHP y prohíbe la ejecución de scripts en la carpeta de subidas.
2. **Defensa contra Ataques de Fuerza Bruta:**
   - Rate limiting activo en el endpoint de autenticación (`Route::post('/entrar')` limitado a 5 intentos por minuto por IP).
3. **Encabezados HTTP de Seguridad (Defensa en Profundidad):**
   - Middleware global [`SecurityHeadersMiddleware`](app/Http/Middleware/SecurityHeadersMiddleware.php) adjuntando:
     - `X-Frame-Options: SAMEORIGIN` (prevención de *Clickjacking*).
     - `X-Content-Type-Options: nosniff` (prevención de *MIME-Confusion*).
     - `Referrer-Policy: strict-origin-when-cross-origin`.
     - `Permissions-Policy: camera=(), microphone=(), geolocation=()`.
4. **Protección contra XSS e Inyecciones SQL:**
   - 100% de consultas construidas con el ORM Eloquent y parámetros enlazados (cero consultas crudas).
   - 100% de vistas Blade renderizan con escape automático HTML (`{{ ... }}`).
5. **Mitigación de IDOR (Insecure Direct Object References):**
   - El portal del delegado valida que el `partido_id` pertenezca a su propia delegación antes de permitir cualquier modificación de resultado.

---

## 🧪 Comandos de Desarrollo y Pruebas

```bash
# Ejecutar suite completa de pruebas sobre MySQL
php vendor/phpunit/phpunit/phpunit

# Formatear el código bajo el estándar de Laravel Pint
vendor/bin/pint --dirty --format agent

# Compilar assets de frontend para producción
npm run build

# Limpiar caché de la aplicación
php artisan optimize:clear
```

---

## 📁 Arquitectura del Proyecto

```text
deportes/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php         # Control administrativo y subidas seguras
│   │   │   ├── AuthController.php          # Login unificado con rate limiting
│   │   │   ├── DelegadoController.php      # Portal de delegados
│   │   │   └── HomeController.php          # Vistas públicas del torneo
│   │   └── Middleware/
│   │       ├── EnsureIsAdmin.php           # Control estricto de /admin
│   │       ├── EnsureIsDelegado.php        # Control estricto de /delegado
│   │       └── SecurityHeadersMiddleware.php # Cabeceras HTTP de seguridad
│   ├── Models/                             # Modelos Eloquent (Torneo, Disciplina, Delegacion, Partido, Nomina)
│   └── Services/
│       └── TournamentService.php           # Motor de cálculo de tablas y fixtures
├── database/
│   ├── macroregional.sql                   # Script SQL maestro para HeidiSQL
│   ├── migrations/                         # Migraciones relacionales
│   └── seeders/DatabaseSeeder.php          # Seeder oficial sin series
├── public/
│   ├── images/portada_macroregional.jpeg   # Portada oficial JEDPA
│   └── uploads/.htaccess                   # Bloqueo de ejecución de scripts en uploads
├── resources/
│   └── views/                              # Vistas Blade en Tailwind CSS v4 + Alpine.js
├── routes/web.php                          # Rutas protegidas y públicas
└── tests/Feature/TournamentFeatureTest.php # Pruebas funcionales en MySQL
```

---

## 📄 Licencia

Este proyecto es software de código abierto bajo la licencia [MIT](LICENSE).
