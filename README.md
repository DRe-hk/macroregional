# 🏆 Plataforma Deportiva Macroregional 2026

Plataforma web oficial desarrollada en **Laravel 13** para la administración, fixtures, resultados en vivo, clasificación en tiempo real y registro de nóminas de atletas de la **Competencia Deportiva Macroregional 2026**.

Diseñada bajo los estándares de máxima legibilidad, velocidad y experiencia de usuario (UI limpia *White/Slate*), **sin pie de página** (footer), con acceso administrativo protegido y optimizada para ejecutarse de forma inmediata en entornos **Laragon** (Apache / Nginx, PHP 8.2+, MySQL o SQLite).

Repositorio oficial: [https://github.com/DRe-hk/macroregional.git](https://github.com/DRe-hk/macroregional.git)

---

## 📑 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Reglas de Negocio y Seguridad](#-reglas-de-negocio-y-seguridad)
- [Credenciales de Acceso](#-credenciales-de-acceso)
- [Instalación y Puesta en Marcha en Laragon](#-instalación-y-puesta-en-marcha-en-laragon)
  - [Opción A: Uso con SQLite (Recomendado, Cero Configuración)](#opción-a-uso-con-sqlite-recomendado-cero-configuración)
  - [Opción B: Uso con MySQL de Laragon](#opción-b-uso-con-mysql-de-laragon)
- [Mapa de Rutas y Endpoints](#-mapa-de-rutas-y-endpoints)
- [Estructura de Base de Datos](#-estructura-de-base-de-datos)
- [Servicio de Clasificación Automática](#-servicio-de-clasificación-automática)
- [Comandos de Desarrollo y Pruebas](#-comandos-de-desarrollo-y-pruebas)
- [Arquitectura del Proyecto](#-arquitectura-del-proyecto)
- [Licencia](#-licencia)

---

## 🌟 Características Principales

- **Diseño Minimalista e Impecable:** Interfaz en tema claro puro (*White / Slate*), tipografía *Inter*, alto contraste, estados interactivos y sin pie de página en ninguna vista.
- **Enfoque Deportivo Integral:**
  - 6 Disciplinas oficiales: Fútbol, Futsal, Vóleibol, Baloncesto, Atletismo y Ajedrez.
  - Formatos por series (fase de grupos) y eliminación directa con árboles interactivos (*Brackets*).
  - Tabla de posiciones acumulada con estadísticas detalladas (PJ, PG, PE, PP, GF, GC, DG, PTS) y podio con medallas de Oro, Plata y Bronce.
  - Directorio de las 15 delegaciones provinciales / UGELs.
- **Doble Compatibilidad Frontend:**
  - Soporte de Vite con Tailwind CSS v4 para desarrollo avanzado.
  - Fallback automático a CDN en `@if(file_exists(public_path('build/manifest.json')))` para abrir el sitio en Laragon directamente sin necesidad de tener procesos `npm` corriendo en segundo plano.
- **Interactividad Ligera con Alpine.js:** Filtrado dinámico de disciplinas en el inicio, pestañas administrativas fluidas y modales de edición sin recarga innecesaria.

---

## 🛡️ Reglas de Negocio y Seguridad

1. **Privacidad del Panel Administrativo:**
   - La barra de navegación pública **no contiene enlaces ni botones hacia `/admin`**.
   - Solo existe un botón discreto **"Ingresar"** que apunta a `/entrar`.
   - La ruta `/admin` solo es accesible escribiendo la URL manualmente en el navegador o tras iniciar sesión como administrador.
2. **Autenticación Unificada y Redirección Inteligente:**
   - En [`/entrar`](/entrar) inician sesión tanto administradores como delegados.
   - El sistema detecta el rol del usuario autenticado:
     - `ADMIN`: Redirigido a [`/admin`](/admin).
     - `DELEGADO`: Redirigido a [`/delegado`](/delegado).
3. **Aislamiento Estricto por Delegación:**
   - Un delegado solo puede ver y cargar resultados de los partidos en los que compite su propia delegación asignada.
   - No puede modificar partidos ajenos ni acceder a las opciones de configuración global del torneo.
4. **Nómina Oficial de Atletas:**
   - Cada delegado puede dar de alta a sus deportistas ingresando DNI, Nombres y Apellidos, Número de Camiseta/Dorsal y Posición/Rol.
5. **Cero Footers:**
   - Todas las vistas están optimizadas para aprovechar al máximo la pantalla sin barras de pie de página residuales.

---

## 👥 Credenciales de Acceso

El sistema incluye cuentas de prueba precargadas mediante el seeder (`php artisan db:seed`):

| Rol | Delegación | Usuario | Contraseña | URL de Acceso | Alcance y Permisos |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Administrador** | Todas | `admin` | `drep2026` | `/admin` o `/entrar` | Control total del torneo, deportes, delegaciones, cruces, marcadores y delegados. |
| **Delegado** | UGEL Puno | `delegado_puno` | `puno2026` | `/entrar` | Carga de resultados de Puno y nómina de atletas de Puno. |
| **Delegado** | UGEL San Román | `delegado_sanroman` | `sanroman2026` | `/entrar` | Carga de resultados de San Román y nómina de atletas de San Román. |

> 💡 **Crear más delegados:** Desde el panel de administración (`/admin` $\rightarrow$ pestaña *"Delegados"*) se pueden crear usuarios para las 15 UGELs en un solo clic.

---

## 🚀 Instalación y Puesta en Marcha en Laragon

### Requisitos Previos
- **Laragon** con PHP 8.2 o PHP 8.5 (instalado en `C:\laragon`).
- **Composer 2.x** disponible en la terminal de Laragon.
- Git instalado.

---

### Opción A: Uso con SQLite (Recomendado, Cero Configuración)

Laravel viene configurado por defecto con SQLite, por lo que no necesitas iniciar ningún servicio MySQL ni crear tablas manualmente.

1. **Clonar o ubicar el repositorio:**
   ```bash
   cd C:\laragon\www
   git clone https://github.com/DRe-hk/macroregional.git
   cd macroregional
   ```

2. **Habilitar extensiones SQLite en PHP (solo si no estuvieran activas en Laragon):**
   - En Laragon, haz clic en **Menu $\rightarrow$ PHP $\rightarrow$ php.ini** (o edita tu archivo `php.ini` activo).
   - Asegúrate de que las siguientes dos líneas **no** tengan punto y coma (`;`) al inicio:
     ```ini
     extension=pdo_sqlite
     extension=sqlite3
     ```
   - Guarda el archivo.

3. **Configurar el entorno y migrar:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan migrate:fresh --seed
   ```

4. **Acceder a la aplicación:**
   - Si Laragon tiene activados los *Virtual Hosts*, abre en tu navegador:
     `http://macroregional.test`
   - O usando el servidor integrado de Laravel:
     ```bash
     php artisan serve
     ```
     Disponible en: `http://127.0.0.1:8000`

---

### Opción B: Uso con MySQL de Laragon

Si prefieres almacenar la información en MySQL:

1. Inicia MySQL desde el panel de Laragon.
2. Abre HeidiSQL o phpMyAdmin y crea la base de datos `macroregional`.
3. En tu archivo `.env`, cambia la conexión a:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=macroregional
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Ejecuta las migraciones y seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```

---

## 🗺️ Mapa de Rutas y Endpoints

### 🌐 Rutas Públicas

| Método | URL | Descripción |
| :--- | :--- | :--- |
| `GET` | `/` | Portada con hero deportivo, buscador en tiempo real y cuadrícula de disciplinas. |
| `GET` | `/clasificacion` | Tabla general de posiciones con podio (Oro, Plata, Bronce), filtros y estadísticas. |
| `GET` | `/equipos` | Catálogo de las 15 delegaciones con su ficha técnica y rendimiento. |
| `GET` | `/campeones` | Cuadro de honor y medallero oficial por disciplina. |
| `GET` | `/d/{slug}` | Vista detallada de una disciplina con llaves de eliminación (*Brackets*) y series. |

### 🔐 Autenticación

| Método | URL | Descripción |
| :--- | :--- | :--- |
| `GET` | `/entrar` | Formulario de inicio de sesión unificado. |
| `POST` | `/entrar` | Procesa credenciales y redirige por rol (`ADMIN` $\rightarrow$ `/admin`, `DELEGADO` $\rightarrow$ `/delegado`). |
| `POST` | `/salir` | Cierre de sesión seguro y eliminación de sesión activa. |

### 👤 Portal del Delegado (`/delegado`)

*Protegido por el middleware `EnsureIsDelegado`.*

| Método | URL | Acción |
| :--- | :--- | :--- |
| `GET` | `/delegado` | Panel principal: listado de partidos propios y nómina de atletas de su delegación. |
| `POST` | `/delegado/partidos/{id}` | Registro de marcadores y observaciones de partidos de su delegación. |
| `POST` | `/delegado/nominas` | Registro de un nuevo atleta (DNI, nombres, dorsal, rol). |
| `DELETE`| `/delegado/nominas/{id}` | Dar de baja a un atleta de la nómina. |

### ⚙️ Panel de Administración (`/admin`)

*Protegido por el middleware `EnsureIsAdmin`.*

| Método | URL | Acción |
| :--- | :--- | :--- |
| `GET` | `/admin` | Panel con 6 pestañas funcionales para gestión integral. |
| `POST` | `/admin/torneo` | Edición del nombre del torneo, sede central, año y progreso. |
| `POST` | `/admin/disciplinas` | Registro y configuración de deportes y escenarios. |
| `POST` | `/admin/delegaciones` | Alta y edición de delegaciones/UGELs. |
| `POST` | `/admin/partidos` | Creación y programación de partidos y cruces eliminatorios. |
| `POST` | `/admin/partidos/{id}/marcador` | Marcador oficial directo y avance de ganadores en llaves. |
| `POST` | `/admin/delegados` | Creación de cuentas de delegados para cada provincia. |
| `POST` | `/admin/delegados/{id}/toggle` | Activar o suspender acceso a un delegado. |
| `POST` | `/admin/reiniciar` | Reinicio de base de datos a los valores iniciales limpios. |

---

## 🗄️ Estructura de Base de Datos

El sistema cuenta con un modelo relacional normalizado:

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
                       │  nominas_atletas│       │     series     │
                       └─────────────────┘       └───────┬────────┘
                                                         │
                                                 ┌───────▼────────┐
                                                 │    partidos    │
                                                 └────────────────┘
```

- **`torneos`**: Nombre, edición, sede, año, organizador y porcentaje de progreso.
- **`delegaciones`**: Las 15 UGELs con su estadística deportiva acumulada: PJ, PG, PE, PP, GF, GC, DG y Puntos.
- **`disciplinas`**: Slug, nombre, categoría, color de acento, sede específica y formato.
- **`series`**: Series por disciplina (Serie A, Serie B, Llave Principal).
- **`partidos`**: Fixture con ronda, delegación local, delegación visitante, marcadores, set/penales, estado (`PROGRAMADO`, `EN_CURSO`, `FINALIZADO`, `SUSPENDIDO`), sede, fecha y hora.
- **`nominas_atletas`**: Nómina de jugadores inscritos por delegación con DNI, nombres, número y posición.
- **`users`**: Administradores y delegados con rol, delegación asignada y estado activo.

---

## ⚡ Servicio de Clasificación Automática

La clase [`app/Services/TournamentService.php`](app/Services/TournamentService.php) es el motor deportivo del sistema:

1. **Recálculo de Estadísticas:** Al finalizar o editar un partido, recalcula en una sola transacción los valores de PJ, PG, PE, PP, GF, GC, DG y PTS de ambas delegaciones.
2. **Criterios de Puntuación:**
   - Victoria: **3 puntos**
   - Empate: **1 punto**
   - Derrota: **0 puntos**
3. **Avance en Brackets:** En partidos de eliminación con ganador definido, avanza al vencedor a la siguiente ronda de la serie correspondiente.

---

## 🧪 Comandos de Desarrollo y Pruebas

```bash
# Ejecutar suite completa de pruebas unitarias y funcionales
php artisan test

# Formatear el código bajo el estándar de Laravel Pint
vendor/bin/pint --format agent

# Compilar assets para producción (opcional)
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
│   │   │   ├── AdminController.php      # Control total administrativo
│   │   │   ├── AuthController.php       # Autenticación unificada
│   │   │   ├── DelegadoController.php   # Portal de delegados
│   │   │   └── HomeController.php       # Vistas públicas del torneo
│   │   └── Middleware/
│   │       ├── EnsureIsAdmin.php        # Protección estricta de /admin
│   │       └── EnsureIsDelegado.php     # Protección estricta de /delegado
│   ├── Models/                          # Modelos Eloquent relacionados
│   └── Services/
│       └── TournamentService.php        # Lógica de cálculo deportivo
├── database/
│   ├── migrations/                      # Migraciones de esquema
│   └── seeders/                         # Seeder con datos iniciales limpios
├── resources/
│   └── views/
│       ├── layouts/app.blade.php        # Layout principal sin footer
│       ├── home.blade.php               # Portada deportiva
│       ├── clasificacion.blade.php      # Tabla de posiciones
│       ├── equipos.blade.php            # Lista de delegaciones
│       ├── campeones.blade.php          # Medallero y podio
│       ├── disciplina.blade.php         # Brackets y series
│       ├── auth/login.blade.php         # Pantalla de login
│       ├── delegado/index.blade.php     # Panel del delegado
│       └── admin/index.blade.php        # Suite administrativa en 6 pestañas
├── routes/
│   └── web.php                          # Definición de rutas protegidas y públicas
└── tests/
    └── Feature/
        └── TournamentFeatureTest.php    # Pruebas funcionales de seguridad y flujos
```

---

## 📄 Licencia

Este proyecto es software de código abierto bajo la licencia [MIT](LICENSE).

