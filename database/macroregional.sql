-- =====================================================================
-- SCRIPT DE BASE DE DATOS: COMPETENCIA DEPORTIVA MACROREGIONAL 2026
-- COMPATIBLE CON: MySQL 8.0+ / MariaDB / Laragon / HeidiSQL
-- ARQUITECTURA: SIN SERIES (100% DIRECTO POR DISCIPLINA DEPORTIVA)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `macroregional` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `macroregional`;

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- 1. TABLA: migrations (Registro oficial de Laravel)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_09_200131_create_torneos_table', 1),
(5, '2026_09_09_200132_create_delegaciones_table', 1),
(6, '2026_09_09_200133_create_disciplinas_table', 1),
(7, '2026_09_09_200135_create_partidos_table', 1),
(8, '2026_09_09_200136_create_nominas_table', 1),
(9, '2026_09_09_200137_add_sports_fields_to_users_table', 1),
(10, '2026_09_23_184802_add_logos_and_reference_photos_to_tables', 1);

-- ---------------------------------------------------------------------
-- 2. TABLA: torneos
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `torneos`;
CREATE TABLE `torneos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organizador` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sede_principal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `anio` int NOT NULL DEFAULT '2026',
  `avance_porcentaje` int NOT NULL DEFAULT '0',
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `portada_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `torneos` (`id`, `nombre`, `subtitulo`, `organizador`, `sede_principal`, `anio`, `avance_porcentaje`, `logo_url`, `portada_url`, `created_at`, `updated_at`) VALUES
(1, 'Competencia Deportiva Macroregional 2026', 'Torneo Deportivo Macroregional de Educación', 'Comisión Macroregional DREP', 'Puno / Juliaca', 2026, 0, NULL, '/images/portada_macroregional.jpeg', NOW(), NOW());

-- ---------------------------------------------------------------------
-- 3. TABLA: delegaciones
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `delegaciones`;
CREATE TABLE `delegaciones` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siglas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provincia` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `puntos` int NOT NULL DEFAULT '0',
  `pj` int NOT NULL DEFAULT '0',
  `pg` int NOT NULL DEFAULT '0',
  `pe` int NOT NULL DEFAULT '0',
  `pp` int NOT NULL DEFAULT '0',
  `gf` int NOT NULL DEFAULT '0',
  `gc` int NOT NULL DEFAULT '0',
  `dg` int NOT NULL DEFAULT '0',
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `delegaciones` (`id`, `nombre`, `siglas`, `provincia`, `puntos`, `pj`, `pg`, `pe`, `pp`, `gf`, `gc`, `dg`, `logo_url`, `created_at`, `updated_at`) VALUES
('drep', 'DREP Sede Central', 'DREP', 'Puno', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('puno', 'UGEL Puno', 'Puno', 'Puno', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('san-roman', 'UGEL San Román', 'San Román', 'San Román', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('azangaro', 'UGEL Azángaro', 'Azángaro', 'Azángaro', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('melgar', 'UGEL Melgar', 'Melgar', 'Melgar', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('collao', 'UGEL Collao', 'Collao', 'El Collao', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('chucuito', 'UGEL Chucuito', 'Chucuito', 'Chucuito', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('yunguyo', 'UGEL Yunguyo', 'Yunguyo', 'Yunguyo', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('huancane', 'UGEL Huancané', 'Huancané', 'Huancané', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('lampa', 'UGEL Lampa', 'Lampa', 'Lampa', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('carabaya', 'UGEL Carabaya', 'Carabaya', 'Carabaya', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('sandia', 'UGEL Sandia', 'Sandia', 'Sandia', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('putina', 'UGEL Putina', 'Putina', 'San Antonio de Putina', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('moho', 'UGEL Moho', 'Moho', 'Moho', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW()),
('crucero', 'UGEL Crucero', 'Crucero', 'Carabaya', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NOW(), NOW());

-- ---------------------------------------------------------------------
-- 4. TABLA: disciplinas (Sin Series)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `disciplinas`;
CREATE TABLE `disciplinas` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_acento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#2563eb',
  `foto_url` text COLLATE utf8mb4_unicode_ci,
  `foto_referencia_url` text COLLATE utf8mb4_unicode_ci,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `sede_principal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sede_maps_url` text COLLATE utf8mb4_unicode_ci,
  `campeon_actual` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `disciplinas_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `disciplinas` (`id`, `slug`, `nombre`, `categoria`, `color_acento`, `foto_url`, `foto_referencia_url`, `descripcion`, `sede_principal`, `sede_maps_url`, `campeon_actual`, `created_at`, `updated_at`) VALUES
('futbol-libre', 'futbol-libre', 'Fútbol Libre', 'Fútbol', '#2563eb', 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=800&auto=format&fit=crop&q=80', NULL, 'Torneo de fútbol varones categoría libre en cancha reglamentaria.', 'Estadio Enrique Torres Belón · Puno', NULL, NULL, NOW(), NOW()),
('voley-damas', 'voley-damas', 'Vóley Damas', 'Vóley', '#e11d48', 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=800&auto=format&fit=crop&q=80', NULL, 'Torneo femenino oficial de voleibol en coliseo cerrado.', 'Coliseo Eduardo Rodríguez Ponce de León', NULL, NULL, NOW(), NOW()),
('futsal-varones', 'futsal-varones', 'Futsal Varones', 'Futsal', '#059669', 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&auto=format&fit=crop&q=80', NULL, 'Fútbol de salón en losa deportiva reglamentaria.', 'Polideportivo San Román · Juliaca', NULL, NULL, NOW(), NOW()),
('futsal-damas', 'futsal-damas', 'Futsal Damas', 'Futsal', '#0d9488', 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=800&auto=format&fit=crop&q=80', NULL, 'Competencia de fútbol sala para trabajadoras de educación.', 'Complejo Deportivo Chanu Chanu · Puno', NULL, NULL, NOW(), NOW()),
('voley-mixto', 'voley-mixto', 'Vóley Mixto', 'Vóley', '#d97706', 'https://images.unsplash.com/photo-1592656094267-764a45160876?w=800&auto=format&fit=crop&q=80', NULL, 'Torneo integrador de vóley mixto.', 'Coliseo Municipal de Ilave', NULL, NULL, NOW(), NOW()),
('basquet-libre', 'basquet-libre', 'Básquetbol', 'Básquet', '#ea580c', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&auto=format&fit=crop&q=80', NULL, 'Campeonato oficial de baloncesto interinstitucional.', 'Coliseo Cerrado de Puno', NULL, NULL, NOW(), NOW());

-- ---------------------------------------------------------------------
-- 5. TABLA: partidos (Asociados directamente a la disciplina)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `partidos`;
CREATE TABLE `partidos` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disciplina_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ronda_numero` int NOT NULL DEFAULT '1',
  `ronda_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ronda 1',
  `local_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitante_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `local_goles` int DEFAULT NULL,
  `visitante_goles` int DEFAULT NULL,
  `ganador_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PROGRAMADO',
  `horario` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partidos_disciplina_id_foreign` (`disciplina_id`),
  KEY `partidos_local_id_foreign` (`local_id`),
  KEY `partidos_visitante_id_foreign` (`visitante_id`),
  KEY `partidos_ganador_id_foreign` (`ganador_id`),
  CONSTRAINT `partidos_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partidos_local_id_foreign` FOREIGN KEY (`local_id`) REFERENCES `delegaciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partidos_visitante_id_foreign` FOREIGN KEY (`visitante_id`) REFERENCES `delegaciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partidos_ganador_id_foreign` FOREIGN KEY (`ganador_id`) REFERENCES `delegaciones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 6. TABLA: nominas
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `nominas`;
CREATE TABLE `nominas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `delegacion_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disciplina_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dni` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_completo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_camiseta` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rol_equipo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Titular',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nominas_delegacion_id_foreign` (`delegacion_id`),
  KEY `nominas_disciplina_id_foreign` (`disciplina_id`),
  CONSTRAINT `nominas_delegacion_id_foreign` FOREIGN KEY (`delegacion_id`) REFERENCES `delegaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nominas_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 7. TABLA: users (Usuarios del sistema)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DELEGADO',
  `delegacion_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_delegacion_id_foreign` (`delegacion_id`),
  CONSTRAINT `users_delegacion_id_foreign` FOREIGN KEY (`delegacion_id`) REFERENCES `delegaciones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `name`, `email`, `email_verified_at`, `password`, `role`, `delegacion_id`, `activo`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrador General', 'admin@macroregional.drep.gob.pe', NULL, '$2y$12$2iEm8C30omfGfs0gZIb1AO4S6C5LtvAZDa0.mCgAqSSjKDkK.K8HW', 'ADMIN', NULL, 1, NULL, NOW(), NOW()),
(2, 'delegado_puno', 'Delegado UGEL Puno', 'puno@macroregional.drep.gob.pe', NULL, '$2y$12$B3/CE.yKByvwgVUNjZT6Zu1.nLStyxNOo.gg7CdVD90xgZs8f/j..', 'DELEGADO', 'puno', 1, NULL, NOW(), NOW()),
(3, 'delegado_sanroman', 'Delegado UGEL San Román', 'sanroman@macroregional.drep.gob.pe', NULL, '$2y$12$eq9xpWP/xcPSdA7kEGR0FOIUBoVgKve0PBHEaEVcWBEhbA5Q9F.2C', 'DELEGADO', 'san-roman', 1, NULL, NOW(), NOW());

-- ---------------------------------------------------------------------
-- 8. TABLA: password_reset_tokens
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 9. TABLA: sessions (Sesiones del sistema)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 10. TABLAS: cache y cache_locks
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 11. TABLAS: jobs, job_batches, failed_jobs (Colas de Laravel)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Limpieza de tabla series si existía previamente
DROP TABLE IF EXISTS `series`;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- FIN DEL SCRIPT
-- =====================================================================
