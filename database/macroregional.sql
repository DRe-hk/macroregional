-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: macroregional
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba','i:1;',1790402265),('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1790402265;',1790402265);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delegaciones`
--

DROP TABLE IF EXISTS `delegaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `delegaciones` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siglas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provincia` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_url` text COLLATE utf8mb4_unicode_ci,
  `puntos` int NOT NULL DEFAULT '0',
  `pj` int NOT NULL DEFAULT '0',
  `pg` int NOT NULL DEFAULT '0',
  `pe` int NOT NULL DEFAULT '0',
  `pp` int NOT NULL DEFAULT '0',
  `gf` int NOT NULL DEFAULT '0',
  `gc` int NOT NULL DEFAULT '0',
  `dg` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delegaciones`
--

LOCK TABLES `delegaciones` WRITE;
/*!40000 ALTER TABLE `delegaciones` DISABLE KEYS */;
INSERT INTO `delegaciones` VALUES ('dre-arequipa','DRE Arequipa','AREQUIPA','Arequipa',NULL,5,3,1,1,1,52,56,-4,'2026-09-26 10:10:48','2026-09-26 10:34:09'),('dre-cusco','DRE Cusco','CUSCO','Cusco',NULL,1,1,0,1,0,2,2,0,'2026-09-26 10:10:48','2026-09-26 10:34:09'),('dre-madre-de-dios','DRE Madre de Dios','MDD','Tambopata',NULL,0,0,0,0,0,0,0,0,'2026-09-26 10:10:48','2026-09-26 10:34:09'),('dre-moquegua','DRE Moquegua','MOQUEGUA','Mariscal Nieto',NULL,0,1,0,0,1,0,2,-2,'2026-09-26 10:10:48','2026-09-26 10:34:09'),('dre-puno','DRE Puno (Anfitrión)','PUNO','Puno',NULL,5,2,2,0,0,5,2,3,'2026-09-26 10:10:48','2026-09-26 10:34:09'),('dre-tacna','DRE Tacna','TACNA','Tacna',NULL,3,3,1,0,2,56,53,3,'2026-09-26 10:10:48','2026-09-26 10:34:09'),('ugel-melgar','UGEL Melgar','MELGAR','Melgar',NULL,0,0,0,0,0,0,0,0,'2026-09-26 10:10:48','2026-09-26 10:34:09'),('ugel-san-roman','UGEL San Román','JULIACA','San Román',NULL,0,0,0,0,0,0,0,0,'2026-09-26 10:10:48','2026-09-26 10:34:09');
/*!40000 ALTER TABLE `delegaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delegado_disciplinas`
--

DROP TABLE IF EXISTS `delegado_disciplinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `delegado_disciplinas` (
  `user_id` bigint unsigned NOT NULL,
  `disciplina_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`,`disciplina_id`),
  KEY `delegado_disciplinas_disciplina_id_foreign` (`disciplina_id`),
  CONSTRAINT `delegado_disciplinas_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `delegado_disciplinas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delegado_disciplinas`
--

LOCK TABLES `delegado_disciplinas` WRITE;
/*!40000 ALTER TABLE `delegado_disciplinas` DISABLE KEYS */;
INSERT INTO `delegado_disciplinas` VALUES (10,'ajedrez'),(7,'atletismo'),(4,'basquet'),(4,'basquet-b-damas'),(4,'basquet-b-varones'),(4,'basquet-c-damas'),(4,'basquet-c-varones'),(2,'futbol'),(2,'futbol-b-damas'),(2,'futbol-b-varones'),(2,'futbol-c-damas'),(2,'futbol-c-varones'),(3,'futsal'),(3,'futsal-b-damas'),(3,'futsal-b-varones'),(6,'handball'),(6,'handball-b-damas'),(6,'handball-b-varones'),(8,'natacion'),(9,'tenis-mesa'),(5,'voleibol'),(5,'voleibol-b-damas'),(5,'voleibol-b-varones'),(5,'voleibol-c-damas'),(5,'voleibol-c-varones'),(5,'voley-playa'),(5,'voley-playa-b-damas'),(5,'voley-playa-b-varones');
/*!40000 ALTER TABLE `delegado_disciplinas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delegado_partidos`
--

DROP TABLE IF EXISTS `delegado_partidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `delegado_partidos` (
  `user_id` bigint unsigned NOT NULL,
  `partido_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`,`partido_id`),
  KEY `delegado_partidos_partido_id_foreign` (`partido_id`),
  CONSTRAINT `delegado_partidos_partido_id_foreign` FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `delegado_partidos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delegado_partidos`
--

LOCK TABLES `delegado_partidos` WRITE;
/*!40000 ALTER TABLE `delegado_partidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `delegado_partidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disciplinas`
--

DROP TABLE IF EXISTS `disciplinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disciplinas` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COLECTIVO',
  `sistema_puntuacion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FUTBOL',
  `color_acento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#2563eb',
  `foto_url` text COLLATE utf8mb4_unicode_ci,
  `foto_referencia_url` text COLLATE utf8mb4_unicode_ci,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `sede_principal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sede_maps_url` text COLLATE utf8mb4_unicode_ci,
  `fechas_cronograma` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `horario_cronograma` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campeon_actual` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `podio` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `disciplinas_slug_unique` (`slug`),
  KEY `disciplinas_parent_id_foreign` (`parent_id`),
  CONSTRAINT `disciplinas_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disciplinas`
--

LOCK TABLES `disciplinas` WRITE;
/*!40000 ALTER TABLE `disciplinas` DISABLE KEYS */;
INSERT INTO `disciplinas` VALUES ('ajedrez',NULL,'ajedrez','Ajedrez','Cat. A y B','Damas y Varones','COLECTIVO','FUTBOL','#475569','https://images.unsplash.com/photo-1529699211952-734e80c4d42b?w=800&auto=format&fit=crop&q=80',NULL,'Sistema suizo a 5 rondas bajo reglamento FIDE escolar.','IE GUE San Carlos',NULL,'28-Set (9:30 AM) y 29-Set (9:00 AM)','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('atletismo',NULL,'atletismo','Atletismo - Para atletismo','Cat. A, B y C - D y E','Damas y Varones','INDIVIDUAL','INDIVIDUAL','#f59e0b','https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=80',NULL,'Pruebas de pista y campo: velocidad, fondo, salto y lanzamiento en pista sintética.','Estadio Enrique Torres Belón · Puno',NULL,'28-Set (9:30 AM) y 29-Set (8:00 AM)','08:00 AM - 01:00 PM','DRE Puno (Anfitrión)','{\"oro\": {\"marca\": \"100m - 10.85s\", \"atleta\": \"Diego Quispe C.\", \"delegacion\": \"DRE Puno (Anfitrión)\"}, \"plata\": {\"marca\": \"100m - 11.02s\", \"atleta\": \"Mateo Gómez R.\", \"delegacion\": \"DRE Arequipa\"}, \"bronce\": {\"marca\": \"100m - 11.20s\", \"atleta\": \"Lucas Mamani T.\", \"delegacion\": \"DRE Cusco\"}}','2026-09-26 10:10:48','2026-09-26 10:34:07'),('basquet',NULL,'basquet','Básquet','Cat. B y C','Damas y Varones','COLECTIVO','BASQUET','#ea580c','https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&auto=format&fit=crop&q=80',NULL,'4 periodos de 10 minutos. Aro a 3.05 m de altura.','Coliseo cubierto Eduardo Rodríguez Ponce de León',NULL,'30-Set, 01-Oct y 02-Oct (9:00 AM)','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('basquet-b-damas','basquet','basquet-b-damas','Básquet Cat. B Damas','Cat. B','Damas','COLECTIVO','BASQUET','#c2410c',NULL,NULL,NULL,'Coliseo cubierto ERPL',NULL,'30-Set, 01-Oct, 02-Oct','10:30 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('basquet-b-varones','basquet','basquet-b-varones','Básquet Cat. B Varones','Cat. B','Varones','COLECTIVO','BASQUET','#ea580c',NULL,NULL,NULL,'Coliseo cubierto ERPL',NULL,'30-Set, 01-Oct, 02-Oct','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('basquet-c-damas','basquet','basquet-c-damas','Básquet Cat. C Damas','Cat. C','Damas','COLECTIVO','BASQUET','#7c2d12',NULL,NULL,NULL,'Coliseo cubierto ERPL',NULL,'30-Set, 01-Oct, 02-Oct','02:30 PM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('basquet-c-varones','basquet','basquet-c-varones','Básquet Cat. C Varones','Cat. C','Varones','COLECTIVO','BASQUET','#9a3412',NULL,NULL,NULL,'Coliseo cubierto ERPL',NULL,'30-Set, 01-Oct, 02-Oct','01:00 PM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('futbol',NULL,'futbol','Fútbol','Cat. B y C','Damas y Varones','COLECTIVO','FUTBOL','#16a34a','https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=800&auto=format&fit=crop&q=80',NULL,'Fútbol 11 reglamentario en cancha sintética. 2 tiempos de 30 minutos.','Cancha sintética de Fútbol - UNA Puno',NULL,'30-Set, 01-Oct y 02-Oct (9:00 AM)','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('futbol-b-damas','futbol','futbol-b-damas','Fútbol Cat. B Damas','Cat. B','Damas','COLECTIVO','FUTBOL','#15803d',NULL,NULL,NULL,'Cancha sintética de Fútbol - UNA Puno',NULL,'30-Set, 01-Oct, 02-Oct','10:30 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('futbol-b-varones','futbol','futbol-b-varones','Fútbol Cat. B Varones','Cat. B','Varones','COLECTIVO','FUTBOL','#16a34a',NULL,NULL,NULL,'Cancha sintética de Fútbol - UNA Puno',NULL,'30-Set, 01-Oct, 02-Oct','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('futbol-c-damas','futbol','futbol-c-damas','Fútbol Cat. C Damas','Cat. C','Damas','COLECTIVO','FUTBOL','#14532d',NULL,NULL,NULL,'Cancha sintética de Fútbol - UNA Puno',NULL,'30-Set, 01-Oct, 02-Oct','02:30 PM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('futbol-c-varones','futbol','futbol-c-varones','Fútbol Cat. C Varones','Cat. C','Varones','COLECTIVO','FUTBOL','#166534',NULL,NULL,NULL,'Cancha sintética de Fútbol - UNA Puno',NULL,'30-Set, 01-Oct, 02-Oct','01:00 PM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('futsal',NULL,'futsal','Futsal','Cat. B','Varones y Damas','COLECTIVO','FUTSAL','#059669','https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&auto=format&fit=crop&q=80',NULL,'Fútbol de salón en coliseo techado. 2 tiempos de 20 minutos corridos.','Coliseo UNA - Puno',NULL,'30-Set, 01-Oct y 02-Oct (9:00 AM)','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('futsal-b-damas','futsal','futsal-b-damas','Futsal Cat. B Damas','Cat. B','Damas','COLECTIVO','FUTSAL','#047857',NULL,NULL,NULL,'Coliseo UNA - Puno',NULL,'30-Set, 01-Oct, 02-Oct','10:15 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('futsal-b-varones','futsal','futsal-b-varones','Futsal Cat. B Varones','Cat. B','Varones','COLECTIVO','FUTSAL','#059669',NULL,NULL,NULL,'Coliseo UNA - Puno',NULL,'30-Set, 01-Oct, 02-Oct','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('gimnasia',NULL,'gimnasia','Gimnasia Rítmica y Artística','Cat. A','Damas y Varones','COLECTIVO','FUTBOL','#ec4899','https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=800&auto=format&fit=crop&q=80',NULL,'Rutinas de suelo, aparatos y rítmica escolar.','Coliseo IE GUE San Carlos',NULL,'29-Set (9:30 AM)','09:30 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('handball',NULL,'handball','Handball','Cat. B','Damas y Varones','COLECTIVO','HANDBALL','#7c3aed','https://images.unsplash.com/photo-1587280501635-68a0e82cd5ff?w=800&auto=format&fit=crop&q=80',NULL,'Balonmano escolar: 2 tiempos de 20 minutos con 10 min de descanso.','IE GUE San Carlos',NULL,'30-Set, 01-Oct y 02-Oct (9:00 AM)','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('handball-b-damas','handball','handball-b-damas','Handball Cat. B Damas','Cat. B','Damas','COLECTIVO','HANDBALL','#6d28d9',NULL,NULL,NULL,'IE GUE San Carlos',NULL,'30-Set, 01-Oct, 02-Oct','10:30 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('handball-b-varones','handball','handball-b-varones','Handball Cat. B Varones','Cat. B','Varones','COLECTIVO','HANDBALL','#7c3aed',NULL,NULL,NULL,'IE GUE San Carlos',NULL,'30-Set, 01-Oct, 02-Oct','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('natacion',NULL,'natacion','Natación','Cat. A, B y C','Damas y Varones','INDIVIDUAL','INDIVIDUAL','#06b6d4','https://images.unsplash.com/photo-1530549387789-4c1017266635?w=800&auto=format&fit=crop&q=80',NULL,'Pruebas individuales de estilo libre, espalda, pecho y mariposa.','Piscina Municipal de Puno',NULL,'28-Set (10:00 AM) y 29-Set (10:00 AM)','10:00 AM - 02:00 PM','DRE Arequipa','{\"oro\": {\"marca\": \"50m Libre - 27.42s\", \"atleta\": \"Sofía Ramos P.\", \"delegacion\": \"DRE Arequipa\"}, \"plata\": {\"marca\": \"50m Libre - 28.10s\", \"atleta\": \"Camila Flores M.\", \"delegacion\": \"DRE Puno (Anfitrión)\"}, \"bronce\": {\"marca\": \"50m Libre - 28.95s\", \"atleta\": \"Valeria Cárdenas\", \"delegacion\": \"DRE Tacna\"}}','2026-09-26 10:10:48','2026-09-26 10:34:07'),('paleta-fronton',NULL,'paleta-fronton','Paleta Frontón','Cat. C','Damas y Varones','COLECTIVO','VOLEIBOL','#0284c7','https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=800&auto=format&fit=crop&q=80',NULL,'Canchas reglamentarias en el Complejo deportivo Chanu Chanu.','Complejo deportivo Chanu Chanu',NULL,'28-Set (10:00 AM)','10:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('taekwondo',NULL,'taekwondo','Taekwondo','Cat. A','Damas y Varones','COLECTIVO','FUTBOL','#dc2626','https://images.unsplash.com/photo-1555597673-b21d5c935865?w=800&auto=format&fit=crop&q=80',NULL,'Modalidades Kyorugi (combate) y Poomsae (formas) oficiales.','Auditorio Consejo Regional del Deporte - IPD',NULL,'29-Set (9:30 AM)','09:30 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('tenis-campo',NULL,'tenis-campo','Tenis de Campo','Cat. A','Damas y Varones','COLECTIVO','VOLEIBOL','#84cc16','https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?w=800&auto=format&fit=crop&q=80',NULL,'Torneo individual y dobles en superficie de arcilla.','Club de Tiro - Manuel Pino N° 18',NULL,'28-Set (9:30 AM)','09:30 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('tenis-mesa',NULL,'tenis-mesa','Tenis de Mesa','Cat. B y C','Damas y Varones','COLECTIVO','VOLEIBOL','#2563eb','https://images.unsplash.com/photo-1534158914592-062992fbe900?w=800&auto=format&fit=crop&q=80',NULL,'Mesas profesionales en el Coliseo Estadio UNA Puno.','Estadio - UNA Puno Puerta tribuna norte',NULL,'28-Set (10:00 AM) y 29-Set (9:00 AM)','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('voleibol',NULL,'voleibol','Voleibol','Cat. B y C','Damas y Varones','COLECTIVO','VOLEIBOL','#e11d48','https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=800&auto=format&fit=crop&q=80',NULL,'Al mejor de 2 de 3 sets (a 25 pts; 3er set decisivo a 15 pts).','Coliseo IE Glorioso San Carlos',NULL,'30-Set, 01-Oct y 02-Oct (9:00 AM)','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('voleibol-b-damas','voleibol','voleibol-b-damas','Voleibol Cat. B Damas','Cat. B','Damas','COLECTIVO','VOLEIBOL','#e11d48',NULL,NULL,NULL,'Coliseo IE Glorioso San Carlos',NULL,'30-Set, 01-Oct, 02-Oct','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('voleibol-b-varones','voleibol','voleibol-b-varones','Voleibol Cat. B Varones','Cat. B','Varones','COLECTIVO','VOLEIBOL','#be123c',NULL,NULL,NULL,'Coliseo IE Glorioso San Carlos',NULL,'30-Set, 01-Oct, 02-Oct','10:30 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('voleibol-c-damas','voleibol','voleibol-c-damas','Voleibol Cat. C Damas','Cat. C','Damas','COLECTIVO','VOLEIBOL','#9f1239',NULL,NULL,NULL,'Coliseo IE Glorioso San Carlos',NULL,'30-Set, 01-Oct, 02-Oct','01:00 PM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('voleibol-c-varones','voleibol','voleibol-c-varones','Voleibol Cat. C Varones','Cat. C','Varones','COLECTIVO','VOLEIBOL','#881337',NULL,NULL,NULL,'Coliseo IE Glorioso San Carlos',NULL,'30-Set, 01-Oct, 02-Oct','02:30 PM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('voley-playa',NULL,'voley-playa','Vóley Playa','Cat. B','Damas y Varones','COLECTIVO','VOLEY_PLAYA','#d97706','https://images.unsplash.com/photo-1592656094267-764a45160876?w=800&auto=format&fit=crop&q=80',NULL,'Modalidad en arena por parejas al mejor de 2 de 3 sets.','Cancha Vóley Playa - UNA Puno',NULL,'30-Set y 01-Oct (9:00 AM)','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('voley-playa-b-damas','voley-playa','voley-playa-b-damas','Vóley Playa Cat. B Damas','Cat. B','Damas','COLECTIVO','VOLEY_PLAYA','#d97706',NULL,NULL,NULL,'Cancha Vóley Playa - UNA Puno',NULL,'30-Set y 01-Oct','09:00 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48'),('voley-playa-b-varones','voley-playa','voley-playa-b-varones','Vóley Playa Cat. B Varones','Cat. B','Varones','COLECTIVO','VOLEY_PLAYA','#b45309',NULL,NULL,NULL,'Cancha Vóley Playa - UNA Puno',NULL,'30-Set y 01-Oct','10:30 AM',NULL,NULL,'2026-09-26 10:10:48','2026-09-26 10:10:48');
/*!40000 ALTER TABLE `disciplinas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_09_09_200131_create_torneos_table',1),(5,'2026_09_09_200132_create_delegaciones_table',1),(6,'2026_09_09_200133_create_disciplinas_table',1),(7,'2026_09_09_200135_create_partidos_table',1),(8,'2026_09_09_200136_create_nominas_table',1),(9,'2026_09_09_200137_add_sports_fields_to_users_table',1),(10,'2026_09_23_184802_add_logos_and_reference_photos_to_tables',1),(11,'2026_09_26_000001_create_delegado_permissions_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nominas`
--

DROP TABLE IF EXISTS `nominas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nominas`
--

LOCK TABLES `nominas` WRITE;
/*!40000 ALTER TABLE `nominas` DISABLE KEYS */;
/*!40000 ALTER TABLE `nominas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partidos`
--

DROP TABLE IF EXISTS `partidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `fecha` date DEFAULT NULL,
  `horario` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `es_wo` tinyint(1) NOT NULL DEFAULT '0',
  `foto_evidencia` text COLLATE utf8mb4_unicode_ci,
  `sets_detalle` json DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partidos_disciplina_id_foreign` (`disciplina_id`),
  KEY `partidos_local_id_foreign` (`local_id`),
  KEY `partidos_visitante_id_foreign` (`visitante_id`),
  KEY `partidos_ganador_id_foreign` (`ganador_id`),
  CONSTRAINT `partidos_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partidos_ganador_id_foreign` FOREIGN KEY (`ganador_id`) REFERENCES `delegaciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partidos_local_id_foreign` FOREIGN KEY (`local_id`) REFERENCES `delegaciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partidos_visitante_id_foreign` FOREIGN KEY (`visitante_id`) REFERENCES `delegaciones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partidos`
--

LOCK TABLES `partidos` WRITE;
/*!40000 ALTER TABLE `partidos` DISABLE KEYS */;
INSERT INTO `partidos` VALUES ('part-bas-01','basquet-b-varones',1,'Fecha 1','dre-tacna','dre-arequipa',54,48,'dre-tacna','FINALIZADO','2026-09-30','09:00 AM','Coliseo cubierto ERPL',0,NULL,NULL,'Básquet oficial: Ganador recibe 2 pts, perdedor recibe 1 pt.','2026-09-26 10:10:50','2026-09-26 10:10:50'),('part-bas-02','basquet-b-varones',1,'Fecha 1','dre-puno','dre-cusco',NULL,NULL,NULL,'PROGRAMADO','2026-09-30','10:30 AM','Coliseo cubierto ERPL',0,NULL,NULL,NULL,'2026-09-26 10:10:50','2026-09-26 10:10:50'),('part-fut-01','futbol-b-varones',1,'Fecha 1','dre-puno','dre-tacna',3,1,'dre-puno','FINALIZADO','2026-09-30','09:00 AM','Cancha sintética UNA - Campo 1',0,NULL,NULL,'Partido inaugural de Fútbol Cat. B Varones.','2026-09-26 10:10:50','2026-09-26 10:10:50'),('part-fut-02','futbol-b-varones',1,'Fecha 1','dre-arequipa','dre-cusco',2,2,NULL,'FINALIZADO','2026-09-30','10:30 AM','Cancha sintética UNA - Campo 1',0,NULL,NULL,'Empate reñido en los últimos minutos.','2026-09-26 10:10:50','2026-09-26 10:10:50'),('part-fut-03','futbol-b-varones',2,'Fecha 2','dre-puno','dre-arequipa',NULL,NULL,NULL,'PROGRAMADO','2026-10-01','09:00 AM','Cancha sintética UNA - Campo 1',0,NULL,NULL,'Clásico regional de la Macro Región Sur.','2026-09-26 10:10:50','2026-09-26 10:10:50'),('part-fut-04','futbol-b-varones',2,'Fecha 2','dre-tacna','dre-cusco',NULL,NULL,NULL,'PROGRAMADO','2026-10-01','10:30 AM','Cancha sintética UNA - Campo 1',0,NULL,NULL,NULL,'2026-09-26 10:10:50','2026-09-26 10:10:50'),('part-fut-05','futbol-b-varones',3,'Gran Final','dre-puno','dre-cusco',NULL,NULL,NULL,'PROGRAMADO','2026-10-02','11:00 AM','Cancha sintética UNA - Campo 1',0,NULL,NULL,'Partido definitorio por la medalla de Oro.','2026-09-26 10:10:50','2026-09-26 10:10:50'),('part-vol-01','voleibol-b-damas',1,'Fecha 1','dre-arequipa','dre-moquegua',2,0,'dre-arequipa','FINALIZADO','2026-09-30','09:00 AM','Coliseo IE Glorioso San Carlos',0,NULL,NULL,'Sets: 25-18, 25-20 (Victoria 2-0 = 3 pts).','2026-09-26 10:10:50','2026-09-26 10:10:50'),('part-vol-02','voleibol-b-damas',1,'Fecha 1','dre-puno','dre-tacna',2,1,'dre-puno','FINALIZADO','2026-09-30','10:30 AM','Coliseo IE Glorioso San Carlos',0,NULL,NULL,'Sets: 25-22, 21-25, 15-11 (Victoria 2-1 = 2 pts para Puno, 1 pt para Tacna).','2026-09-26 10:10:50','2026-09-26 10:10:50'),('part-vol-03','voleibol-b-damas',2,'Fecha 2','dre-puno','dre-arequipa',NULL,NULL,NULL,'PROGRAMADO','2026-10-01','10:00 AM','Coliseo IE Glorioso San Carlos',0,NULL,NULL,NULL,'2026-09-26 10:10:50','2026-09-26 10:10:50');
/*!40000 ALTER TABLE `partidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('dB33HGze4nuziCBq7rKXRZzFoALIwtrqk5TJ40HJ',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJxSDBYT0ZKYnR0N2hrWnVGOUNDQWhaMElvdjRTMUhEaTdwUnlsd0NlIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2RlcG9ydGVzLnRlc3RcL2NhbXBlb25lcyIsInJvdXRlIjoiY2FtcGVvbmVzIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9',1790402590),('Di3SNWKYJrQoT8P4MjwHcmu13tUi344jApR0BuLK',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26300; es-PE) PowerShell/7.6.6','eyJfdG9rZW4iOiJ4MmNXclpLZ0ZsMks0aU9kQklPVEI1S0J5MVBJNWNYeWJQb2pTV0R4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2RlcG9ydGVzLnRlc3QiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790401956),('HojYpDvlFSQ3sioqhywh1I4uAOOmSPFRLoIxmAZQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26300; es-PE) PowerShell/7.6.6','eyJfdG9rZW4iOiJEY0x1cE9TWFQzWVdhNTZBNHY2ZGd4Qk5uUktSTlUxQUhOU2FPYUtlIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2RlcG9ydGVzLnRlc3QiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790401936),('MxaGLyJnu8dbNwWdgFin9OXNV1FJy48xD7MxZweJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26300; es-PE) PowerShell/7.6.6','eyJfdG9rZW4iOiJoVEtaN2MzTEpCNWwxMXdBRWdNRnBuSmxyUldJbFI2VTVMTlJKS1lNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2RlcG9ydGVzLnRlc3QiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790401968),('p9Brwv1zIm6kUbBaJ4emRNQiSjj8aT72fhMFskUE',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26300; es-PE) PowerShell/7.6.6','eyJfdG9rZW4iOiJ2UXIwQjg1R3kxNWFNdkhNbFRLNjJISjNmb1VvUVZrY3RJQXFBYWpYIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2RlcG9ydGVzLnRlc3RcL2VudHJhciIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6WyJzdWNjZXNzIl0sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwic3VjY2VzcyI6Ilx1MDBhMUJpZW52ZW5pZG8gQWRtaW5pc3RyYWRvciBHZW5lcmFsISJ9',1790402135),('qUlz6KpfpdUatE4xj57b79aoDb9ldXhOc3cppLMm',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26300; es-PE) PowerShell/7.6.6','eyJfdG9rZW4iOiJpMGVhUnlQUUNYbWE0S0dyd2NPWkZzYUNYZEFtSk1paUkxWFMyUmVQIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2RlcG9ydGVzLnRlc3QiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790401948),('u1op69xE6IUm9Ao2lxjNxwswUrKsZXNsXjNraSZD',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26300; es-PE) PowerShell/7.6.6','eyJfdG9rZW4iOiI5WnB1M1h0eGNhQjJ5TnR3RjlMQWtGSUhNTXZlOXFEZ25pS0s4a2hUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2RlcG9ydGVzLnRlc3RcL2VudHJhciIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6WyJzdWNjZXNzIl0sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwic3VjY2VzcyI6Ilx1MDBhMUJpZW52ZW5pZG8gQWRtaW5pc3RyYWRvciBHZW5lcmFsISJ9',1790402125);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `torneos`
--

DROP TABLE IF EXISTS `torneos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torneos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organizador` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sede_principal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_url` text COLLATE utf8mb4_unicode_ci,
  `portada_url` text COLLATE utf8mb4_unicode_ci,
  `logo_texto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'JEDPA 2026',
  `logo_subtexto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Macroregional Sede Puno',
  `footer_texto` text COLLATE utf8mb4_unicode_ci,
  `carrusel_slides` json DEFAULT NULL,
  `anio` int NOT NULL DEFAULT '2026',
  `avance_porcentaje` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `torneos`
--

LOCK TABLES `torneos` WRITE;
/*!40000 ALTER TABLE `torneos` DISABLE KEYS */;
INSERT INTO `torneos` VALUES (1,'Juegos Escolares Deportivos y Paradeportivos 2026','Etapa Macrorregional N° 07 · Sede Puno','Dirección Regional de Educación Puno - MINEDU','Puno',NULL,'/images/portada_macroregional.jpeg','JEDPA 2026','Macroregional Sede Puno','Dirección Regional de Educación Puno - Oficina de Informática. Todos los derechos reservados.','[{\"imagen\": \"/images/portada_macroregional.jpeg\", \"titulo\": \"JEDPA Macrorregional 2026\", \"subtitulo\": \"Sede Puno · Competencia deportiva oficial de la Macro Región Sur\"}, {\"imagen\": \"https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=1200&auto=format&fit=crop&q=80\", \"titulo\": \"Estadio Enrique Torres Belón\", \"subtitulo\": \"Fútbol, Atletismo y Ceremonias de Apertura\"}, {\"imagen\": \"https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=1200&auto=format&fit=crop&q=80\", \"titulo\": \"Coliseos Eduardo Rodríguez Ponce de León y UNA\", \"subtitulo\": \"Básquet, Voleibol, Futsal y Handball de alto nivel escolar\"}]',2026,35,'2026-09-26 10:10:48','2026-09-26 10:10:48');
/*!40000 ALTER TABLE `torneos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_delegacion_id_foreign` (`delegacion_id`),
  CONSTRAINT `users_delegacion_id_foreign` FOREIGN KEY (`delegacion_id`) REFERENCES `delegaciones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','Administrador General DREP','admin@drepuno.gob.pe',NULL,'$2y$12$YByl7EN7JrpzqV3P4s3bQ.2He8Nbss1yJMDLm/VfCUq1pFZNlFGeu','ADMIN','dre-puno',1,'rN6e4CMs3hlFcbKAnvyW2pFUmhNWIyCGgYwAomEZ4TsfRkYUO3lCxqJArXxG','2026-09-26 10:10:48','2026-09-26 10:34:07'),(2,'delegado.futbol','Delegado Oficial Fútbol','futbol@drepuno.gob.pe',NULL,'$2y$12$Gz4B1Rwg0Y1AKUnXeQM/Qel0IzqVw851X0kdWC2Mbgk8TeEo7CQLm','DELEGADO','dre-puno',1,NULL,'2026-09-26 10:10:48','2026-09-26 10:34:07'),(3,'delegado.futsal','Delegado Oficial Futsal','futsal@drepuno.gob.pe',NULL,'$2y$12$3xmgqABeSTEPAbX1ee25m.7Or/i.FCyAFtamn2sTMJVk0vWpuwvpa','DELEGADO','dre-puno',1,NULL,'2026-09-26 10:10:48','2026-09-26 10:34:08'),(4,'delegado.basquet','Delegado Oficial Básquet','basquet@drepuno.gob.pe',NULL,'$2y$12$FcYnKy2YJTUQrKCTi112hu/L0dHmHjrNJ79z1256Y35U3oIt13Fqi','DELEGADO','dre-puno',1,NULL,'2026-09-26 10:10:49','2026-09-26 10:34:08'),(5,'delegado.voley','Delegado Oficial Voleibol','voley@drepuno.gob.pe',NULL,'$2y$12$1G1j4O8zIs1rbcMZ91hMBOmwIEc4Cv2wWoU6HBZXKJ8vN1S/wUGiu','DELEGADO','dre-puno',1,NULL,'2026-09-26 10:10:49','2026-09-26 10:34:08'),(6,'delegado.handball','Delegado Oficial Handball','handball@drepuno.gob.pe',NULL,'$2y$12$OmLhhaTmo4vWAd8yNdp4tuHCk1x0hsSuqj7vt3ffVOz0n4zVGTpgK','DELEGADO','dre-puno',1,NULL,'2026-09-26 10:10:49','2026-09-26 10:34:08'),(7,'delegado.atletismo','Delegado Oficial Atletismo','atletismo@drepuno.gob.pe',NULL,'$2y$12$XWWsskNg2e9edKHm8yS1LO70nX5k6p77GASp5FJ0svIulpj1STlqS','DELEGADO','dre-puno',1,NULL,'2026-09-26 10:10:49','2026-09-26 10:34:08'),(8,'delegado.natacion','Delegado Oficial Natación','natacion@drepuno.gob.pe',NULL,'$2y$12$IR.Eur8wNJfnBlNGgFTOtee72zWliu0RVU1jZxigcJscQiVJ2E1GW','DELEGADO','dre-puno',1,NULL,'2026-09-26 10:10:49','2026-09-26 10:34:09'),(9,'delegado.tenismesa','Delegado Oficial Tenis de Mesa','tenismesa@drepuno.gob.pe',NULL,'$2y$12$z1DAg2ODLyQSvgfXCOYiiO/EO4C1L95ZPl.JPnru/tCqJl5LlLTPq','DELEGADO','dre-puno',1,NULL,'2026-09-26 10:10:50','2026-09-26 10:34:09'),(10,'delegado.ajedrez','Delegado Oficial Ajedrez','ajedrez@drepuno.gob.pe',NULL,'$2y$12$tardkXhKlN4HKrAaBMCaYeS.JQ3hJaJbd5AQfLe.CjaRJgD0XqAuO','DELEGADO','dre-puno',1,NULL,'2026-09-26 10:10:50','2026-09-26 10:34:09'),(11,'delegado_puno','Delegado General DRE Puno','delegado.puno@drepuno.gob.pe',NULL,'$2y$12$mynIiFlEoE5NU7d8IytcHOSREPFZGe3DozDN6lICYUz1HxAANvHdi','DELEGADO','dre-puno',1,NULL,'2026-09-26 10:53:31','2026-09-26 10:53:31');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-26  2:49:30
