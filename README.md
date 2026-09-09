# Plataforma Deportiva Macroregional 2026 (Laravel 13)

Sistema integral desarrollado en **Laravel 13** para la gestión, seguimiento de fixtures, clasificación en tiempo real, nóminas de atletas y resultados deportivos de la **Competencia Deportiva Macroregional 2026**.

Diseñado bajo la filosofía de máxima claridad y rendimiento con **Tailwind CSS v4** y **Alpine.js**, optimizado para ejecutarse de manera nativa en entornos locales como **Laragon** (Apache / Nginx, PHP 8.2+, MySQL / SQLite).

---

## 🌟 Características Principales

- **Diseño Limpio y Minimalista:** Tema claro (White / Slate) con alta legibilidad, sin distracciones y sin pie de página (footer) en ninguna de las vistas.
- **Enfoque 100% Deportivo:** Presentación de disciplinas (Fútbol, Futsal, Vóleibol, Baloncesto, Atletismo, Ajedrez), series, tablas de posiciones y árboles de eliminación (brackets interactivos).
- **Acceso Administrativo Seguro:** No existen enlaces visibles al panel de administración en las páginas públicas. El acceso a `/admin` está protegido y restringido por credenciales directas.
- **Portal de Delegados por Delegación:**
  - Login unificado en `/entrar` que redirige automáticamente según el rol (`ADMIN` o `DELEGADO`).
  - Cada delegado únicamente visualiza y puede registrar/editar los marcadores de los partidos en los que participa su propia delegación.
  - Registro y gestión de la nómina de atletas de su delegación (DNI, nombres, dorsal y rol).
- **Recálculo Automático de Clasificación:** Servicio `TournamentService` que recalcula de inmediato los puntos (3 por victoria, 1 por empate, 0 por derrota), partidos jugados (PJ), partidos ganados (PG), empatados (PE), perdidos (PP), goles/puntos a favor (GF), en contra (GC) y diferencia (DG).
- **Panel de Administración Completo (`/admin`):**
  - Configuración general del torneo (nombre, sede, año, estado).
  - Gestión completa de disciplinas y sedes deportivas.
  - Gestión de delegaciones (UGELs/equipos).
  - Generación y control de fixtures, rondas y resultados oficiales.
  - Creación de delegados con credenciales y asignación a su delegación.
  - Herramienta de reinicio rápido y limpieza de base de datos.
- **Doble Compatibilidad de Frontend:** Soporta compilación moderna mediante Vite y fallback inmediato a CDN para funcionar sin necesidad de levantar servicios de compilación en Laragon.

---

## 🚀 Puesta en Marcha en Laragon

### 1. Requisitos
- [Laragon](https://laragon.org/) con PHP 8.2 o superior (probado y verificado en PHP 8.5).
- Extensiones PHP activas: `pdo_sqlite`, `sqlite3` (o MySQL si se prefiere).
- Composer 2.x instalado.

### 2. Ubicación del Proyecto en Laragon
Coloca la carpeta del proyecto dentro de la raíz web de Laragon, por ejemplo:
```text
C:\laragon\www\macroregional
```
*(Si usas virtual hosts automáticos de Laragon, se creará el dominio `http://macroregional.test` apuntando a `public/`).*

### 3. Configuración del Entorno
Dentro de la carpeta del proyecto:
```bash
# 1. Copiar archivo de entorno
cp .env.example .env

# 2. Generar clave de aplicación
php artisan key:generate

# 3. Ejecutar migraciones con datos iniciales limpios
php artisan migrate:fresh --seed
```

### 4. Iniciar el Servidor
Puedes acceder a través del virtual host de Laragon (`http://macroregional.test`) o ejecutando el servidor de desarrollo de Laravel:
```bash
php artisan serve
```
La aplicación estará disponible en `http://127.0.0.1:8000`.

---

## 👥 Credenciales de Acceso

El sistema incluye cuentas de prueba configuradas mediante el seeder oficial:

| Rol | Usuario | Contraseña | Acceso | Permisos |
| :--- | :--- | :--- | :--- | :--- |
| **Administrador** | `admin` | `drep2026` | `/admin` o `/entrar` | Acceso total al sistema, configuración, deportes, fixtures y usuarios. |
| **Delegado Puno** | `delegado_puno` | `puno2026` | `/entrar` | Reportar resultados de UGEL Puno y gestionar nómina de atletas. |
| **Delegado San Román** | `delegado_sanroman` | `sanroman2026` | `/entrar` | Reportar resultados de UGEL San Román y gestionar nómina de atletas. |

> *Nota: Nuevos delegados para cualquiera de las 15 delegaciones/UGELs pueden crearse directamente desde la pestaña "Delegados" en el panel `/admin`.*

---

## 🧪 Pruebas Automatizadas

El proyecto cuenta con una suite completa de pruebas funcionales (PHPUnit) que valida la autenticación, seguridad de rutas, permisos por rol y lógica de cálculo:

```bash
php artisan test
```

Todas las pruebas se ejecutan y validan con éxito:
- Acceso a rutas públicas (`/`, `/clasificacion`, `/equipos`, `/campeones`, `/d/{slug}`).
- Denegación de acceso no autenticado a `/admin` y `/delegado`.
- Redirección inteligente de login unificado `/entrar`.
- Restricción de permisos cruzados (un delegado no puede editar partidos ajenos ni acceder a `/admin`).

---

## 📁 Estructura del Código

- **`app/Http/Controllers/`**
  - `HomeController.php`: Rutas públicas (inicio, disciplinas, clasificación, delegaciones, podio).
  - `AuthController.php`: Login unificado, autenticación y logout.
  - `DelegadoController.php`: Vista y acciones del portal del delegado.
  - `AdminController.php`: Vista y gestión integral del torneo y delegados.
- **`app/Http/Middleware/`**
  - `EnsureIsAdmin.php`: Protección estricta del panel administrativo.
  - `EnsureIsDelegado.php`: Protección del portal de delegados.
- **`app/Services/TournamentService.php`**: Lógica de cálculo deportivo y avance de ganadores.
- **`resources/views/`**
  - `layouts/app.blade.php`: Plantilla principal minimalista sin footer.
  - `home.blade.php`: Página de inicio con buscador y disciplinas.
  - `clasificacion.blade.php`: Tabla de posiciones oficial.
  - `equipos.blade.php`: Directorio de delegaciones participantes.
  - `campeones.blade.php`: Medallero y campeones por disciplina.
  - `disciplina.blade.php`: Brackets de eliminación directa y series.
  - `auth/login.blade.php`: Pantalla de inicio de sesión.
  - `delegado/index.blade.php`: Panel del delegado con pestañas de resultados y nómina.
  - `admin/index.blade.php`: Panel de administración en 6 secciones funcionales.

---

## 📄 Licencia

Este proyecto está bajo la licencia [MIT](LICENSE).
