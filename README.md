# Competencia Deportiva Macroregional 2026 🏆

Plataforma oficial para la gestión, seguimiento en tiempo real, fixtures interactivos, tablas de posiciones y administración de la **Competencia Deportiva Macroregional 2026**.

Diseñada bajo principios de diseño minimalista de alto contraste y tipografía nítida para cobertura deportiva oficial.

---

## ⚡ Características Principales

### 1. 🌐 Portal Público
* **Disciplinas Deportivas**: Visualización de fixture, sedes, horarios y árboles de eliminatorias (brackets) en tiempo real.
* **Tabla de Posiciones**: Puntuación automática calculada por victorias, empates y derrotas.
* **Directorio de Delegaciones**: Lista de delegaciones participantes con detalles institucionales.
* **Palmarés / Cuadro de Campeones**: Registro de ganadores oficiales por disciplina.
* **Sin enlaces visibles de administración**: Navegación limpia y orientada al público general y a deportistas.

### 2. 🔑 Portal de Delegados (`/delegado`)
* Autenticación exclusiva para delegados acreditados de cada UGEL/delegación.
* **Gestión de Nóminas**: Registro de deportistas con DNI, número de camiseta y condición (titular/suplente).
* **Marcadores de su Delegación**: Reporte de resultados exclusivamente en los encuentros donde su delegación compite.

### 3. 🛡️ Suite de Administración (`/admin`)
* Acceso protegido con credenciales de Administrador General (accesible escribiendo `/admin` en la barra del navegador).
* **Control del Torneo**: Edición de nombre, subtítulo, sedes y porcentaje de avance.
* **Gestión de Deportes**: Creación y personalización de disciplinas deportivas.
* **Gestión de Delegaciones**: Alta y edición de delegaciones oficiales.
* **Programación y Marcadores**: Creación de series, partidos, ingreso de goles/puntos y avance automático a rondas eliminatorias.
* **Emisión de Credenciales**: Generación y activación/desactivación de cuentas para delegados de UGEL.

### 4. 🚪 Acceso Unificado (`/entrar`)
* Pantalla de login inteligente accesible desde el botón **"Ingresar"** en la barra superior.
* Identifica automáticamente las credenciales ingresadas:
  * Si es cuenta de **Delegado** ➡️ Redirige a `/delegado`.
  * Si es cuenta de **Administrador** ➡️ Redirige a `/admin`.

---

## 🛠️ Stack Tecnológico

* **Framework:** Next.js 16.3 (Turbopack) & React 19
* **Estilos:** Tailwind CSS v4 con variables CSS y tema claro de alto contraste
* **Iconografía:** Lucide React
* **Persistencia:** Store atómico JSON / SQLite compatible en `data/tournament_db.json`
* **Lenguaje:** TypeScript (Estricto)

---

## 🚀 Puesta en Marcha

### Prerrequisitos
* Node.js v18+ o v20+
* npm, pnpm o yarn

### Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/DRe-hk/macroregional.git
cd macroregional

# 2. Instalar dependencias
npm install

# 3. Iniciar servidor de desarrollo
npm run dev
```

Abre [http://localhost:3000](http://localhost:3000) en tu navegador.

### Compilación para Producción

```bash
npm run build
npm run start
```

---

## 🔐 Credenciales Iniciales de Prueba

* **Administrador General:**
  * URL: `/admin` (o vía `/entrar`)
  * Usuario: `admin`
  * Contraseña: `drep2026`
* **Delegado de Muestra:**
  * URL: `/entrar` (o `/delegado`)
  * Usuario: `delegado_puno`
  * Contraseña: `puno2026`

*(Las credenciales de delegados adicionales pueden generarse desde el panel de administración).*

---

## 📄 Licencia

Desarrollado para la Comisión Organizadora de la Competencia Macroregional 2026. Todos los derechos reservados.

