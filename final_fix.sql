-- MySQL dump 10.13  Distrib 8.2.0, for macos14.0 (arm64)
--
-- Host: localhost    Database: kifah_app
-- ------------------------------------------------------
-- Server version	8.2.0

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
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint unsigned NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audit_logs_user_id_index` (`user_id`),
  KEY `audit_logs_action_index` (`action`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blacklists`
--

DROP TABLE IF EXISTS `blacklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blacklists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blocked_by` bigint unsigned NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blacklists_type_value_unique` (`type`,`value`),
  KEY `blacklists_blocked_by_foreign` (`blocked_by`),
  CONSTRAINT `blacklists_blocked_by_foreign` FOREIGN KEY (`blocked_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklists`
--

LOCK TABLES `blacklists` WRITE;
/*!40000 ALTER TABLE `blacklists` DISABLE KEYS */;
/*!40000 ALTER TABLE `blacklists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
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
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
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
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_02_000001_create_services_tables',1),(5,'2024_01_02_000002_create_technician_tables',1),(6,'2024_01_02_000003_create_orders_tables',1),(7,'2024_01_02_000004_create_system_tables',1),(8,'2026_03_31_210200_create_personal_access_tokens_table',2),(9,'2026_04_01_154451_create_service_parts_table',3),(10,'2026_04_03_223446_add_description_to_service_options',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('39497f07-63f2-4a00-bcb4-c76d4f2d8e49','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"order_id\":2,\"order_number\":\"KIF-2026-00001\",\"client_name\":\"Guest_5300\",\"message\":\"New order #KIF-2026-00001 from Guest_5300\",\"url\":\"http:\\/\\/192.168.8.49:8000\\/admin\\/orders\\/2\"}',NULL,'2026-04-03 22:18:08','2026-04-03 22:18:08'),('43a651f1-1b93-4465-ac1d-817f3d102fc5','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"order_id\":10,\"order_number\":\"KIF-2026-00010\",\"client_name\":\"Sara Abdullah\",\"message\":\"New order #KIF-2026-00010 from Sara Abdullah\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/orders\\/10\"}',NULL,'2026-04-04 08:59:57','2026-04-04 08:59:57'),('4a4ea780-fbdf-434e-ba82-4c70141bf50f','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"order_id\":11,\"order_number\":\"KIF-2026-00011\",\"client_name\":\"Hamdizan\",\"message\":\"\\u0637\\u0644\\u0628 \\u062c\\u062f\\u064a\\u062f \\u0631\\u0642\\u0645 #KIF-2026-00011 \\u0645\\u0646 Hamdizan\",\"url\":\"http:\\/\\/192.168.8.68:8080\\/admin\\/orders\\/11\"}','2026-04-05 16:06:54','2026-04-05 16:04:32','2026-04-05 16:06:54'),('584bd3b1-34b5-4679-bb63-c4c952a66b59','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"order_id\":12,\"order_number\":\"KIF-2026-00012\",\"client_name\":\"Hamdizan\",\"message\":\"\\u0637\\u0644\\u0628 \\u062c\\u062f\\u064a\\u062f \\u0631\\u0642\\u0645 #KIF-2026-00012 \\u0645\\u0646 Hamdizan\",\"url\":\"http:\\/\\/192.168.8.68:8080\\/admin\\/orders\\/12\"}',NULL,'2026-04-05 16:06:19','2026-04-05 16:06:19'),('5e0ab96a-b067-44a2-ae24-dc08d00cbf3e','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"order_id\":2,\"order_number\":\"KIF-2026-00001\",\"client_name\":\"Guest_5300\",\"message\":\"\\u0637\\u0644\\u0628 \\u062c\\u062f\\u064a\\u062f \\u0631\\u0642\\u0645 #KIF-2026-00001 \\u0645\\u0646 Guest_5300\",\"url\":\"http:\\/\\/192.168.8.49:8000\\/admin\\/orders\\/2\"}','2026-04-03 22:18:44','2026-04-03 22:18:08','2026-04-03 22:18:44'),('7b6e43de-d2ba-4752-a58f-9dd05d7827ab','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"order_id\":10,\"order_number\":\"KIF-2026-00010\",\"client_name\":\"Sara Abdullah\",\"message\":\"\\u0637\\u0644\\u0628 \\u062c\\u062f\\u064a\\u062f \\u0631\\u0642\\u0645 #KIF-2026-00010 \\u0645\\u0646 Sara Abdullah\",\"url\":\"http:\\/\\/localhost:8000\\/admin\\/orders\\/10\"}','2026-04-05 16:06:54','2026-04-04 08:59:57','2026-04-05 16:06:54'),('7d66c2bd-3425-44fb-8e5e-8215e10b5001','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"order_id\":11,\"order_number\":\"KIF-2026-00011\",\"client_name\":\"Hamdizan\",\"message\":\"\\u0637\\u0644\\u0628 \\u062c\\u062f\\u064a\\u062f \\u0631\\u0642\\u0645 #KIF-2026-00011 \\u0645\\u0646 Hamdizan\",\"url\":\"http:\\/\\/192.168.8.68:8080\\/admin\\/orders\\/11\"}',NULL,'2026-04-05 16:04:32','2026-04-05 16:04:32'),('8ba4056f-0971-474c-991a-f219def5aa54','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"order_id\":2,\"order_number\":\"KIF-2026-00001\",\"client_name\":\"Guest_5300\",\"message\":\"\\u0637\\u0644\\u0628 \\u062c\\u062f\\u064a\\u062f \\u0631\\u0642\\u0645 #KIF-2026-00001 \\u0645\\u0646 Guest_5300\",\"url\":\"http:\\/\\/192.168.8.49:8000\\/admin\\/orders\\/2\"}','2026-04-03 22:17:40','2026-04-03 22:17:10','2026-04-03 22:17:40'),('b87b0ca6-2286-42cb-8433-9f902c2210f1','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"order_id\":12,\"order_number\":\"KIF-2026-00012\",\"client_name\":\"Hamdizan\",\"message\":\"\\u0637\\u0644\\u0628 \\u062c\\u062f\\u064a\\u062f \\u0631\\u0642\\u0645 #KIF-2026-00012 \\u0645\\u0646 Hamdizan\",\"url\":\"http:\\/\\/192.168.8.68:8080\\/admin\\/orders\\/12\"}','2026-04-05 16:06:54','2026-04-05 16:06:19','2026-04-05 16:06:54');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `service_option_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_service_option_id_foreign` (`service_option_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_service_option_id_foreign` FOREIGN KEY (`service_option_id`) REFERENCES `service_options` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (6,6,4,1,180.00,180.00,'2026-04-03 12:49:18','2026-04-03 12:49:18'),(8,8,50,1,2500.00,2500.00,'2026-04-03 22:01:35','2026-04-03 22:01:35'),(9,9,59,1,200.00,200.00,'2026-04-03 22:12:43','2026-04-03 22:12:43'),(10,9,42,1,120.00,120.00,'2026-04-03 22:12:43','2026-04-03 22:12:43'),(11,9,46,1,750.00,750.00,'2026-04-03 22:12:43','2026-04-03 22:12:43'),(12,9,45,1,450.00,450.00,'2026-04-03 22:12:43','2026-04-03 22:12:43'),(13,10,59,1,200.00,200.00,'2026-04-04 08:59:57','2026-04-04 08:59:57'),(14,11,130,1,7000.00,7000.00,'2026-04-05 16:04:32','2026-04-05 16:04:32'),(15,12,100,1,2250.00,2250.00,'2026-04-05 16:06:19','2026-04-05 16:06:19');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `technician_id` bigint unsigned DEFAULT NULL,
  `assigned_by` bigint unsigned DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `urgency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `client_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_assigned_by_foreign` (`assigned_by`),
  KEY `orders_status_urgency_index` (`status`,`urgency`),
  KEY `orders_client_id_index` (`client_id`),
  KEY `orders_technician_id_index` (`technician_id`),
  CONSTRAINT `orders_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_technician_id_foreign` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (2,'KIF-2026-00001',9,NULL,NULL,'pending','scheduled',NULL,0.00,0.00,1520.00,NULL,'pending','Pending Location',0.00000000,0.00000000,NULL,'أحتاج إلى تركيب نظام كاميرات مراقبة للمنزل. يرجى تقديم عرض سعر للتركيب والأجهزة المطلوبة.',NULL,NULL,NULL,NULL,'2026-04-03 12:11:18','2026-04-03 12:11:18'),(3,'KIF-2026-00003',10,NULL,NULL,'pending','scheduled',NULL,0.00,0.00,1520.00,NULL,'pending','Pending Location',0.00000000,0.00000000,NULL,'العميل يطلب تركيب نظام كاميرات مراقبة للمنزل. يرجى تحديد عدد الكاميرات المطلوبة ونوعها.',NULL,NULL,NULL,NULL,'2026-04-03 12:15:40','2026-04-03 12:15:40'),(4,'KIF-2026-00004',11,6,NULL,'assigned','scheduled',NULL,0.00,0.00,7680.00,NULL,'pending','Location detected (24.8846, 46.8317)',24.88458976,46.83167654,NULL,'بناءً على طلب العميل، يلزم تركيب نظام كاميرات مراقبة متكامل للمنزل. يشمل ذلك توفير وتركيب الكاميرات، جهاز التسجيل (DVR/NVR)، القرص الصلب للتخزين، وكافة الكابلات والملحقات اللازمة للتشغيل.',NULL,NULL,NULL,NULL,'2026-04-03 12:28:59','2026-04-03 12:28:59'),(5,'KIF-2026-00005',11,NULL,NULL,'pending','scheduled',NULL,0.00,0.00,4550.00,NULL,'pending','Pending Location',NULL,NULL,NULL,'العميل يرغب في تركيب نظام كاميرات مراقبة جديد للمنزل. يرجى معاينة الموقع لتحديد العدد والأنواع المناسبة.',NULL,NULL,NULL,NULL,'2026-04-03 12:41:28','2026-04-03 12:41:28'),(6,'KIF-2026-00006',11,3,NULL,'assigned','urgent',NULL,0.00,0.00,180.00,NULL,'pending','Pending Location',NULL,NULL,NULL,'يوجد أنبوب صرف صحي من مادة PVC مكسور ومتضرر بشكل كبير في السقف، مما يتطلب استبدال الجزء المتضرر لمنع التسرب.',NULL,NULL,NULL,NULL,'2026-04-03 12:49:18','2026-04-03 12:49:18'),(7,'KIF-2026-00007',5,3,NULL,'assigned','scheduled',NULL,0.00,0.00,8000.00,NULL,'pending','Pending Location',NULL,NULL,NULL,'العميل يرغب في تطوير موقع إلكتروني جديد. يرجى التواصل معه لتحديد المتطلبات التفصيلية والميزات المطلوبة لتقديم عرض سعر دقيق.',NULL,NULL,NULL,NULL,'2026-04-03 19:05:13','2026-04-03 19:05:13'),(8,'KIF-2026-00008',12,3,NULL,'assigned','scheduled',NULL,0.00,0.00,2500.00,NULL,'pending','Pending Location',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'2026-04-03 22:01:35','2026-04-03 22:01:35'),(9,'KIF-2026-00009',11,3,NULL,'assigned','scheduled',NULL,0.00,0.00,1520.00,NULL,'pending','Pending Location',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'2026-04-03 22:12:43','2026-04-03 22:12:43'),(10,'KIF-2026-00010',5,6,NULL,'assigned','scheduled',NULL,0.00,0.00,1620.00,NULL,'pending','تم تحديد الموقع (GPS) (24.8845, 46.8318)',24.88454712,46.83178344,NULL,'أحتاج لتركيب نظام كاميرات مراقبة للمنزل. يرجى تقديم عرض سعر لتركيب كاميرات خارجية وداخلية مع جهاز تسجيل.',NULL,NULL,NULL,NULL,'2026-04-04 08:59:57','2026-04-04 08:59:57'),(11,'KIF-2026-00011',13,6,NULL,'assigned','scheduled',NULL,7000.00,0.00,7000.00,NULL,'pending','Riyadh (Auto-detected)',24.68690000,46.72240000,NULL,'',NULL,NULL,NULL,NULL,'2026-04-05 16:04:32','2026-04-05 16:04:32'),(12,'KIF-2026-00012',13,6,NULL,'assigned','scheduled',NULL,2250.00,0.00,2250.00,NULL,'pending','Riyadh (تحديد تلقائي)',24.68690000,46.72240000,NULL,'',NULL,NULL,NULL,NULL,'2026-04-05 16:06:19','2026-04-05 16:06:19');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `gateway_ref` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_foreign` (`order_id`),
  KEY `payments_verified_by_foreign` (`verified_by`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_options`
--

DROP TABLE IF EXISTS `service_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sub_service_id` bigint unsigned NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `description_ar` text COLLATE utf8mb4_unicode_ci,
  `unit_label_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unit',
  `unit_label_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'وحدة',
  `base_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `urgent_multiplier` decimal(4,2) NOT NULL DEFAULT '1.50',
  `min_quantity` int NOT NULL DEFAULT '1',
  `max_quantity` int NOT NULL DEFAULT '10',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_options_sub_service_id_foreign` (`sub_service_id`),
  CONSTRAINT `service_options_sub_service_id_foreign` FOREIGN KEY (`sub_service_id`) REFERENCES `sub_services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_options`
--

LOCK TABLES `service_options` WRITE;
/*!40000 ALTER TABLE `service_options` DISABLE KEYS */;
INSERT INTO `service_options` VALUES (1,3,'AC Unit Repair','إصلاح وحدة التكييف',NULL,NULL,'units','وحدات',150.00,1.50,1,10,1,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(2,3,'AC Deep Cleaning','تنظيف عميق للمكيف',NULL,NULL,'units','وحدات',100.00,1.50,1,15,2,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(3,3,'AC Installation','تركيب مكيف',NULL,NULL,'units','وحدات',250.00,1.75,1,5,3,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(4,4,'Pipe Repair','إصلاح الأنابيب',NULL,NULL,'points','نقاط',120.00,1.50,1,5,1,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(5,4,'Drain Cleaning','تنظيف المجاري',NULL,NULL,'drains','مجارٍ',80.00,1.50,1,5,2,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(6,5,'Wiring & Outlets','الأسلاك والمنافذ',NULL,NULL,'points','نقاط',90.00,1.50,1,10,1,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(7,6,'Room Painting','دهان الغرف',NULL,NULL,'rooms','غرف',300.00,1.25,1,10,1,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(9,8,'Alarm System Setup','إعداد نظام الإنذار',NULL,NULL,'zones','مناطق',350.00,1.50,1,8,1,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(11,31,'Mobile Application','تطبيق جوال',NULL,NULL,'platforms','منصات',5000.00,1.25,1,3,1,1,'2026-03-31 15:39:53','2026-04-03 21:28:40'),(12,1,'Site Assessment','تقييم الموقع',NULL,NULL,'visits','زيارات',500.00,1.50,1,3,1,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(13,2,'Room Renovation','تجديد الغرف',NULL,NULL,'rooms','غرف',3000.00,1.25,1,8,1,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(14,11,'Apartment Spraying','رش شقة',NULL,NULL,'apt','شقة',150.00,1.50,1,3,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(15,11,'Villa Deep Treatment','معالجة فيلا',NULL,NULL,'villa','فيلا',350.00,1.50,1,1,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(16,12,'Furniture Assembly (IKEA)','تركيب أثاث',NULL,NULL,'pieces','قطع',100.00,1.25,1,10,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(17,12,'Door Repair/Lock Change','إصلاح باب أو تغيير قفل',NULL,NULL,'doors','أبواب',80.00,1.75,1,5,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(18,13,'Washing Machine Repair','إصلاح غسالة',NULL,NULL,'units','وحدة',150.00,1.50,1,2,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(19,13,'Refrigerator Gas Refill','تعبئة فريون ثلاجة',NULL,NULL,'units','وحدة',200.00,1.50,1,2,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(20,14,'Video Intercom Install','تركيب إنتركم مرئي',NULL,NULL,'units','جهاز',300.00,1.25,1,5,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(21,14,'Fingerprint Door Lock','قفل باب بالبصمة',NULL,NULL,'locks','أقفال',250.00,1.25,1,5,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(22,15,'Smart Lighting Setup','برمجة الإضاءة الذكية',NULL,NULL,'switches','مفتاح',50.00,1.25,5,50,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(25,18,'Gypsum Board Install','تركيب جبس بورد',NULL,NULL,'sqm','متر مكعب',60.00,1.25,10,200,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(26,19,'Roof Waterproofing','عزل أسطح مائي',NULL,NULL,'sqm','متر مكعب',45.00,1.25,50,500,0,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(42,27,'Analog HD Camera (5MP)','كاميرا أنالوج 5 ميجا','High-definition analog camera with 5MP resolution, perfect for general monitoring with clear day/night vision.','كاميرا أنالوج عالية الدقة 5 ميجا بكسل، مثالية للمراقبة العامة مع رؤية ليلية ونهارية واضحة.','units','كاميرا',120.00,1.50,1,32,1,1,'2026-04-03 19:08:33','2026-04-03 21:35:29'),(43,27,'IP POE Camera (4MP)','كاميرا شبكية IP مع POE','Advanced network camera with Power over Ethernet (POE). Provides superior 4K-ready clarity and remote access features.','كاميرا شبكية متطورة تدعم تقنية POE، توفر وضوحاً فائقاً ومميزات الوصول عن بعد.','units','كاميرا',250.00,1.50,1,32,2,1,'2026-04-03 19:08:33','2026-04-03 21:35:29'),(44,27,'PTZ Moving Camera','كاميرا متحركة PTZ','Pan-Tilt-Zoom camera that can be controlled remotely to rotate 360 degrees and zoom into specific areas.','كاميرا متحركة (PTZ) يمكن التحكم بها عن بعد للدوران 360 درجة والتقريب لمناطق محددة.','units','كاميرا',850.00,1.50,1,4,3,1,'2026-04-03 19:08:33','2026-04-03 21:35:29'),(45,28,'4-Channel DVR/NVR unit','جهاز تسجيل 4 قنوات','Basic recording unit that supports up to 4 cameras. Includes remote viewing capabilities on mobile apps.','جهاز تسجيل أساسي يدعم حتى 4 كاميرات، مع خاصية المشاهدة عن بعد عبر تطبيق الجوال.','units','جهاز',450.00,1.25,1,1,4,1,'2026-04-03 19:08:33','2026-04-03 21:35:29'),(46,28,'8-Channel DVR/NVR unit','جهاز تسجيل 8 قنوات','Professional recording unit for up to 8 cameras. Ideal for medium-sized villas and businesses.','جهاز تسجيل احترافي يدعم حتى 8 كاميرات، مثالي للفلل والمحلات المتوسطة.','units','جهاز',750.00,1.25,1,1,5,1,'2026-04-03 19:08:33','2026-04-03 21:35:29'),(47,28,'16-Channel DVR/NVR unit','جهاز تسجيل 16 قناة','High-capacity recorder for up to 16 cameras. Best for warehouses and large commercial complexes.','جهاز تسجيل عالي السعة يدعم حتى 16 كاميرا، مناسب للمستودعات والمجمعات التجارية الكبيرة.','units','جهاز',1350.00,1.25,1,1,6,1,'2026-04-03 19:08:33','2026-04-03 21:35:29'),(48,29,'Hard Drive (2TB)','هاردسك 2 تيرابايت للقرص',NULL,NULL,'units','حبة',350.00,1.00,1,4,7,1,'2026-04-03 19:08:33','2026-04-03 21:28:40'),(49,29,'Cabling & Installation Package','باقة التمديدات والتركيب الكاملة',NULL,NULL,'pts','نقطة',200.00,1.50,1,10,8,1,'2026-04-03 19:08:33','2026-04-03 21:28:40'),(50,30,'Corporate Website (5-10 pages)','موقع شركة (5-10 صفحات)','A professional business website including About Us, Services, Portfolio, and Contact forms. Responsive design for all devices.','موقع احترافي للشركات يشمل التعريف، الخدمات، معرض الأعمال، ونماذج التواصل. متوافق مع كافة الأجهزة.','websites','مواقع',3500.00,1.25,1,3,2,1,'2026-04-03 21:23:22','2026-04-04 22:00:37'),(52,30,'Custom Web Application/SaaS','تطبيق ويب مخصص (SaaS)','Tailor-made software built for the web. Includes management dashboards, complex databases, and unique business logic.','برمجيات خاصة مبنية للويب، تشمل لوحات تحكم إدارية، قواعد بيانات معقدة، وبرمجة مخصصة لأعمالك.','project','مشروع',8500.00,1.25,1,1,3,1,'2026-04-03 21:23:22','2026-04-03 21:35:29'),(53,31,'iOS & Android (Cross-platform)','تطبيق iOS وأندرويد (هجين)','Single codebase app (Flutter/React Native) that works perfectly on both iPhone and Android, saving time and cost.','تطبيق واحد يعمل على آيفون وأندرويد معاً، يوفر الوقت والتكلفة مع أداء ممتاز.','app','تطبيق',6500.00,1.50,1,1,1,1,'2026-04-03 21:23:22','2026-04-03 21:35:29'),(54,31,'Native Mobile App (Performance)','تطبيق موبايل أصيل (Native)','Premium app built specifically for iOS or Android using official languages. Highest speed, security, and hardware access.','تطبيق مخصص مبني بلغات آبل أو جوجل الرسمية. يوفر أعلى سرعة، أمان، ووصول كامل لخصائص الجوال.','app','تطبيق',12000.00,1.25,1,1,2,1,'2026-04-03 21:23:22','2026-04-03 21:35:29'),(57,32,'SEO Optimization (Rank Boost)','تحسين محركات البحث SEO','Technical website optimization to improve your ranking on Google Search results and increase organic traffic.','تحسين تقني للموقع لرفع ترتيبه في نتائج بحث جوجل وزيادة الزوار بشكل طبيعي.','months','شهر',2000.00,1.00,1,6,2,1,'2026-04-03 21:23:22','2026-04-03 21:35:29'),(58,32,'Google & Meta Ads Campaign','حملات إعلانية جوجل وميتا','Targeted advertising campaigns on Google Search, Instagram, and Facebook to reach new customers instantly.','حملات إعلانية مستهدفة على محرك بحث جوجل، إنستقرام، وفيسبوك للوصول لعملاء جدد فوراً.','campaigns','حملة',500.00,1.50,1,5,3,1,'2026-04-03 21:23:22','2026-04-03 21:35:29'),(59,27,'Wireless IP Camera (WiFi)','كاميرا لاسلكية WiFi',NULL,NULL,'unit','جهاز',200.00,1.50,1,10,0,1,'2026-04-03 21:28:40','2026-04-03 21:28:40'),(60,30,'E-Commerce Store (Full Setup)','متجر إلكتروني متكامل','Complete online store with product management, shopping cart, and secure payment gateway integration.','متجر إلكتروني متكامل مع إدارة المنتجات، سلة المشتريات، وربط بوابات الدفع الإلكتروني.','project','مشروع',4500.00,1.50,1,1,2,1,'2026-04-03 21:30:52','2026-04-03 21:35:29'),(61,30,'Landing Page Development','تطوير صفحة هبوط',NULL,NULL,'pages','صفحات',1200.00,1.30,1,10,1,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(62,30,'Web Portal / Dashboard','بوابة ويب / لوحة تحكم',NULL,NULL,'portals','بوابات',6500.00,1.20,1,2,3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(63,35,'Cross-platform App (Flutter/React Native)','تطبيق متعدد المنصات',NULL,NULL,'apps','تطبيقات',7000.00,1.20,1,2,1,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(64,35,'Native Android App','تطبيق أندرويد أصلي',NULL,NULL,'apps','تطبيقات',8000.00,1.20,1,2,2,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(65,35,'Native iOS App','تطبيق iOS أصلي',NULL,NULL,'apps','تطبيقات',9000.00,1.20,1,2,3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(66,35,'App Maintenance (Monthly)','صيانة التطبيق (شهري)',NULL,NULL,'months','شهور',1200.00,1.15,1,12,4,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(67,36,'POS Software Install','تثبيت نظام كاشير',NULL,NULL,'registers','أجهزة',500.00,1.50,1,5,1,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(68,36,'POS Integration with Inventory','ربط نقاط البيع بالمخزون',NULL,NULL,'branches','فروع',1300.00,1.30,1,3,2,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(69,36,'POS Staff Training Session','تدريب فريق العمل على النظام',NULL,NULL,'sessions','جلسات',450.00,1.20,1,6,3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(70,37,'Office Network Setup','تجهيز شبكة مكتب',NULL,NULL,'points','نقاط',800.00,1.50,1,8,1,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(71,37,'Server / NAS Configuration','إعداد السيرفر أو NAS',NULL,NULL,'servers','أجهزة',1500.00,1.35,1,4,2,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(72,37,'Remote IT Support (Monthly)','دعم فني عن بعد (شهري)',NULL,NULL,'months','شهور',600.00,1.20,1,12,3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(73,32,'Social Media Management (Monthly)','إدارة حسابات التواصل (شهري)',NULL,NULL,'months','شهور',1800.00,1.15,1,12,1,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(74,32,'Paid Ads Management (Google/Meta)','إدارة الإعلانات المدفوعة',NULL,NULL,'months','شهور',2000.00,1.15,1,12,2,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(75,32,'Content Plan + 20 Creatives','خطة محتوى + 20 تصميم',NULL,NULL,'packages','باقات',1600.00,1.20,1,6,3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(76,38,'SEO Audit','تدقيق SEO',NULL,NULL,'audits','تقارير',900.00,1.20,1,4,1,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(77,38,'On-Page SEO Optimization','تحسين صفحات الموقع',NULL,NULL,'packages','باقات',1400.00,1.20,1,6,2,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(78,38,'Technical SEO Fixes','حل مشاكل SEO التقنية',NULL,NULL,'packages','باقات',1700.00,1.20,1,4,3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(79,38,'Local SEO (Google Business)','تحسين الظهور المحلي',NULL,NULL,'locations','مواقع',850.00,1.15,1,6,4,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(80,39,'Shopify Store Setup','إعداد متجر Shopify',NULL,NULL,'stores','متاجر',2600.00,1.20,1,3,1,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(81,39,'WooCommerce Store Setup','إعداد متجر WooCommerce',NULL,NULL,'stores','متاجر',2200.00,1.20,1,3,2,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(82,39,'Products Upload & Catalog Build','رفع المنتجات وبناء الكتالوج',NULL,NULL,'batches','دفعات',700.00,1.15,1,20,3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(83,39,'Payment & Shipping Integration','ربط الدفع والشحن',NULL,NULL,'integrations','عمليات ربط',1100.00,1.20,1,6,4,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(84,40,'Brand Identity Kit','باقة الهوية البصرية',NULL,NULL,'kits','باقات',2500.00,1.20,1,3,1,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(85,40,'Logo Design (3 concepts)','تصميم شعار (3 نماذج)',NULL,NULL,'logos','شعارات',900.00,1.20,1,5,2,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(86,40,'Monthly Content Production','إنتاج محتوى شهري',NULL,NULL,'months','شهور',1400.00,1.15,1,12,3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(87,41,'CRM Setup & Pipeline Design','إعداد CRM وتصميم المبيعات',NULL,NULL,'systems','أنظمة',2100.00,1.20,1,4,1,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(88,41,'ERP Workflow Automation','أتمتة تدفقات ERP',NULL,NULL,'modules','وحدات',3200.00,1.20,1,4,2,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(89,41,'WhatsApp/API Integration','تكامل واتساب وواجهات API',NULL,NULL,'integrations','عمليات ربط',1700.00,1.20,1,8,3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(90,42,'UX Audit + Improvements Plan','تدقيق تجربة المستخدم وخطة تحسين',NULL,NULL,'audits','تقارير',1200.00,1.15,1,6,1,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(91,42,'Wireframes for New Product','تصميم Wireframes لمنتج جديد',NULL,NULL,'projects','مشاريع',1800.00,1.20,1,4,2,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(92,42,'High-Fidelity UI Kit','تصميم واجهات عالية الدقة',NULL,NULL,'kits','باقات',2400.00,1.20,1,4,3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(93,43,'2-Camera 8MP Bundle + DVR','باقة 2 كاميرا 8MP + DVR','60m night vision, outdoor HD cameras, DVR included. Original price 1,150 SAR.','رؤية ليلية 60م، كاميرات خارجية عالية الدقة، جهاز تسجيل مشمول. السعر الأصلي 1,150 ريال.','package','باقة',999.00,1.30,1,1,1,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(94,43,'4-Camera 8MP Bundle + DVR','باقة 4 كاميرا 8MP + DVR','Indoor/outdoor 8MP cameras with DVR. Original price 1,680 SAR.','كاميرات داخلية وخارجية عالية الدقة 8 ميجابكسل مع DVR. السعر الأصلي 1,680 ريال.','package','باقة',1300.00,1.30,1,1,2,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(95,43,'6-Camera 8MP Bundle + DVR','باقة 6 كاميرا 8MP + DVR','8-Channel DVR, 6 cameras at 8MP. Original price 1,990 SAR.','DVR 8 قنوات، 6 كاميرات 8 ميجابكسل. السعر الأصلي 1,990 ريال.','package','باقة',1799.00,1.30,1,1,3,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(96,43,'8-Camera 8MP Bundle + DVR','باقة 8 كاميرا 8MP + DVR','8-Channel DVR, 8 cameras at 8MP. Original price 2,250 SAR.','DVR 8 قنوات، 8 كاميرات 8 ميجابكسل. السعر الأصلي 2,250 ريال.','package','باقة',1899.00,1.30,1,1,4,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(97,43,'3-Camera 5MP Bundle + DVR (Includes Installation)','باقة 3 كاميرا 5MP + DVR (شاملة التركيب)','Includes installation in Riyadh. Original price 1,600 SAR.','شاملة التركيب بالرياض. السعر الأصلي 1,600 ريال.','package','باقة',1400.00,1.30,1,1,5,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(98,43,'4-Camera 5MP Bundle + DVR (Includes Installation)','باقة 4 كاميرا 5MP + DVR (شاملة التركيب)','Includes installation in Riyadh. Original price 1,600 SAR.','شاملة التركيب بالرياض. السعر الأصلي 1,600 ريال.','package','باقة',1500.00,1.30,1,1,6,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(99,43,'5-Camera 5MP Bundle + DVR (Includes Installation)','باقة 5 كاميرا 5MP + DVR (شاملة التركيب)','Includes installation in Riyadh. Original price 2,300 SAR.','شاملة التركيب بالرياض. السعر الأصلي 2,300 ريال.','package','باقة',1950.00,1.30,1,1,7,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(100,43,'6-Camera 5MP Bundle + DVR (Includes Installation)','باقة 6 كاميرا 5MP + DVR (شاملة التركيب)','Includes installation in Riyadh. Original price 2,400 SAR.','شاملة التركيب بالرياض. السعر الأصلي 2,400 ريال.','package','باقة',2250.00,1.30,1,1,8,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(101,44,'Camera Inspection & Technical Report','فحص الكاميرات وتقرير فني','Full security system inspection with detailed technical report.','فحص شامل للمنظومة الأمنية وإصدار تقرير تقني مفصل.','report','تقرير',500.00,1.50,1,10,1,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(102,44,'Camera Installation (Includes Cable & Wiring)','تركيب كاميرا (شامل كابل وتوصيل)','Includes cables, connectors, and professional mounting.','يشمل الكابلات والموصلات والتركيب الاحترافي.','camera','كاميرا',400.00,1.50,1,32,2,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(103,44,'Camera Installation (Labor Only)','تركيب كاميرا (عمالة فقط)','Installation only, excluding cables and wiring.','تركيب فقط بدون تمديدات أو كابلات.','camera','كاميرا',180.00,1.50,1,32,3,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(104,44,'WiFi Camera Installation & Programming','تركيب وبرمجة كاميرا واي فاي','WiFi camera installation, setup, and network connection.','تركيب وإعداد الكاميرا اللاسلكية والاتصال بالشبكة.','camera','كاميرا',200.00,1.50,1,20,4,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(105,44,'WiFi Booster/Extender Installation','تركيب موزع واي فاي / معزز إشارة','WiFi extender or access point installation and setup.','تركيب وإعداد مقوي أو موزع الشبكة اللاسلكية.','device','جهاز',200.00,1.50,1,10,5,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(106,44,'Installation Site Survey','معاينة موقع التركيب','Site visit to identify the best installation design and plan.','زيارة الموقع لتحديد أفضل تصميم وخطة للتركيب.','visit','زيارة',200.00,1.50,1,10,6,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(107,44,'Video Intercom Installation & Programming','تركيب وبرمجة انتركم فيديو','Full video intercom installation, setup, and programming.','تركيب وإعداد وبرمجة نظام الإنتركم المرئي الكامل.','system','نظام',450.00,1.50,1,10,7,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(108,45,'Access Control Lock TF1700','قفل تحكم بالوصول TF1700','ZKTeco smart lock for full access control.','قفل ذكي من ZKTeco لتحكم متكامل بالوصول.','unit','وحدة',1170.00,1.20,1,20,1,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(109,45,'Face Recognition UFACE800-ID','جهاز التعرف على الوجه UFACE800-ID','ZKTeco face recognition attendance device.','جهاز حضور وانصراف بتقنية التعرف على الوجه من ZKTeco.','unit','وحدة',1450.00,1.20,1,20,2,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(110,45,'Fingerprint Attendance F18','جهاز بصمة الحضور F18','ZKTeco F18 professional fingerprint attendance.','جهاز بصمة حضور ZKTeco F18 احترافي للشركات.','unit','وحدة',850.00,1.20,1,20,3,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(111,45,'Fingerprint/Face MB1000','بصمة/وجه MB1000','ZKTeco MB1000 fingerprint and face attendance.','جهاز حضور ZKTeco MB1000 بصمة ووجه.','unit','وحدة',850.00,1.20,1,20,4,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(112,45,'Ultra-Thin Attendance F22','جهاز حضور رفيع F22','ZKTeco F22 ultra-thin attendance device.','جهاز حضور ZKTeco F22 رفيع التصميم.','unit','وحدة',750.00,1.20,1,20,5,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(113,45,'Fingerprint Attendance MB20','جهاز بصمة حضور MB20','ZKTeco MB20 economical attendance device.','جهاز حضور ZKTeco MB20 اقتصادي وفعّال.','unit','وحدة',620.00,1.20,1,20,6,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(114,45,'Fingerprint Lock TL300B','قفل بصمة TL300B','ZKTeco TL300B smart fingerprint door lock.','قفل باب ذكي بالبصمة من ZKTeco TL300B.','unit','وحدة',950.00,1.20,1,20,7,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(115,45,'Magnetic Lock 500kg','قفل مغناطيسي 500 كجم','500kg magnetic lock for heavy-duty doors.','قفل مغناطيسي قوة 500 كيلوجرام للأبواب الثقيلة.','unit','وحدة',380.00,1.20,1,20,8,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(116,45,'Metal Exit Button','زر خروج معدني','Durable professional metal exit button.','زر خروج احترافي معدني متين.','unit','وحدة',110.00,1.20,1,20,9,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(117,45,'Plastic Exit Button','زر خروج بلاستيكي','Economical plastic exit button.','زر خروج اقتصادي بلاستيكي.','unit','وحدة',70.00,1.20,1,20,10,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(118,45,'Touchless Exit Button K2S','زر خروج لاتلامسي K2S','Motion-sensing touchless exit button.','زر خروج لاتلامسي بالاستشعار بالحركة.','unit','وحدة',170.00,1.20,1,20,11,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(119,46,'GPS Tracking Device (+ 6 Months Subscription)','جهاز GPS للتتبع (+ 6 أشهر اشتراك)','GPS tracking device with 6-month free data subscription.','جهاز تتبع GPS مع اشتراك 6 أشهر مجاني في خدمة البيانات.','device','جهاز',670.00,1.20,1,10,1,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(120,46,'Dashcam AE-DC5113-F6S','داش كام AE-DC5113-F6S','Professional dashcam AE-DC5113-F6S.','كاميرا سيارة داش كام AE-DC5113-F6S احترافية.','device','جهاز',580.00,1.20,1,10,2,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(121,46,'Dashcam AE-DC4928-N6pro','داش كام AE-DC4928-N6pro','Advanced spec dashcam N6pro.','كاميرا سيارة داش كام N6pro بمواصفات متقدمة.','device','جهاز',565.00,1.20,1,10,3,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(122,46,'Hikvision Dashcam AE-DC4328-K5','داش كام Hikvision AE-DC4328-K5','Hikvision K5 dashcam with high-quality recording.','داش كام هيكفيجن K5 جودة تسجيل عالية.','device','جهاز',490.00,1.20,1,10,4,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(123,46,'Hikvision Dashcam AE-DC2018-K2','داش كام Hikvision AE-DC2018-K2','Economical and reliable Hikvision K2 dashcam.','داش كام هيكفيجن K2 اقتصادي وموثوق.','device','جهاز',310.00,1.20,1,10,5,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(124,30,'Personal Portfolio (3 pages)','بورتفوليو شخصي (3 صفحات)',NULL,NULL,'sites','موقع',1000.00,1.20,1,2,1,1,'2026-04-05 12:52:38','2026-04-05 13:30:14'),(125,30,'Corporate Website (5 pages)','موقع تعريفي لشركة (5 صفحات)',NULL,NULL,'sites','موقع',1500.00,1.20,1,2,2,1,'2026-04-05 12:52:38','2026-04-05 13:30:14'),(126,30,'Complete Web Platform (+Store & Dashboard)','موقع ويب شركة متكامل (متجر + لوحة تحكم)',NULL,NULL,'project','مشروع',5000.00,1.20,1,1,3,1,'2026-04-05 12:52:38','2026-04-05 12:52:38'),(127,30,'Store with Dashboard','متجر مع لوحة تحكم',NULL,NULL,'stores','متجر',2000.00,1.20,1,2,4,1,'2026-04-05 12:52:38','2026-04-05 13:30:14'),(128,30,'Educational Platform','منصة تعليمية',NULL,NULL,'platforms','منصة',10000.00,1.20,1,1,5,1,'2026-04-05 12:52:38','2026-04-05 13:30:14'),(129,30,'Streaming / Services Platform','منصة مشاهدة / خدمات',NULL,NULL,'project','مشروع',12000.00,1.20,1,1,6,1,'2026-04-05 12:52:38','2026-04-05 12:52:38'),(130,30,'Social Media Platform','موقع تواصل اجتماعي',NULL,NULL,'project','مشروع',7000.00,1.20,1,1,7,1,'2026-04-05 12:52:38','2026-04-05 12:52:38'),(131,35,'Mobile App (One Platform)','تطبيق موبايل',NULL,NULL,'project','مشروع',5000.00,1.20,1,1,1,1,'2026-04-05 12:52:38','2026-04-05 12:52:38'),(132,35,'Mobile App (Android + iOS)','تطبيق موبايل (Android + iOS)',NULL,NULL,'project','مشروع',7000.00,1.20,1,1,2,1,'2026-04-05 12:52:38','2026-04-05 12:52:38'),(133,35,'Professional App (All Features)','تطبيق احترافي (يلبي كافة الاحتياجات)',NULL,NULL,'project','مشروع',12000.00,1.20,1,1,3,1,'2026-04-05 12:52:38','2026-04-05 12:52:38');
/*!40000 ALTER TABLE `service_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_parts`
--

DROP TABLE IF EXISTS `service_parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_parts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_sar` decimal(10,2) NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_parts_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_parts`
--

LOCK TABLES `service_parts` WRITE;
/*!40000 ALTER TABLE `service_parts` DISABLE KEYS */;
INSERT INTO `service_parts` VALUES (1,'كاميرا مراقبة على الطاقة الشمسية 4MP (DS-2DE2C400IWG-K)','Hikvision 4MP Solar Camera (DS-2DE2C400IWG-K)','Hikvision',910.00,'DS-2DE2C400IWG-K','Camera',1,'2026-04-01 16:24:59','2026-04-01 16:24:59'),(2,'كاميرا مراقبة خارجية 8 ميجا - رؤية ليلية 60 متر','Hikvision 8MP Outdoor Camera 60m Night Vision','Hikvision',340.00,'HIK-8MP-OUT-60M','Camera',1,'2026-04-01 16:24:59','2026-04-01 16:24:59'),(3,'انتركوم هيكفجن موديل DS-KIS213','Hikvision Intercom System DS-KIS213','Hikvision',500.00,'DS-KIS213','Intercom',1,'2026-04-01 16:24:59','2026-04-01 16:24:59'),(4,'جهاز تسجيل شبكي 8 قنوات POE يدعم دقة 8 ميجا','Hikvision 8-Port POE NVR 4K (DS-7608NXI-I2/8P/S)','Hikvision',1250.00,'DS-7608NXI-I2/8P/S','DVR',1,'2026-04-01 16:24:59','2026-04-01 16:24:59'),(5,'كاميرا طاقة شمسية EZVIZ EB5 (Solar)','EZVIZ EB5 Solar Powered Smart Camera','EZVIZ',580.00,'EZVIZ-EB5','Camera',1,'2026-04-01 16:24:59','2026-04-01 16:24:59'),(6,'كاميرا خارجية بالطاقة الشمسية EZVIZ EB8 2K','EZVIZ EB8 2K Battery/Solar Outdoor PTZ','EZVIZ',780.00,'EZVIZ-EB8','Camera',1,'2026-04-01 16:24:59','2026-04-01 16:24:59'),(7,'كاميرا واي فاي 5G ذكية دقة 5 ميجا بكسل Ezviz H6','EZVIZ H6 5MP Smart WiFi Camera (5G)','EZVIZ',320.00,'EZVIZ-H6','Camera',1,'2026-04-01 16:24:59','2026-04-01 16:24:59'),(8,'كامير واي فاي داخلية EZVIZ H6C 2K (4MP)','EZVIZ H6C 2K Interior WiFi Camera','EZVIZ',290.00,'EZVIZ-H6C','Camera',1,'2026-04-01 16:24:59','2026-04-01 16:24:59'),(9,'كاميرا مراقبة اطفال ذكية H6C (C6N)','EZVIZ C6N Smart Baby Monitor','EZVIZ',220.00,'EZVIZ-C6N','Camera',1,'2026-04-01 16:24:59','2026-04-01 16:24:59'),(10,'كاميرا مراقبة خارجية ذكية H3 3K (5MP)','EZVIZ H3 3K 5MP Outdoor Smart Camera','EZVIZ',320.00,'EZVIZ-H3','Camera',1,'2026-04-01 16:24:59','2026-04-01 16:24:59');
/*!40000 ALTER TABLE `service_parts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#2B6CB0',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Construction & Contracting','البناء والمقاولات','construction-contracting','Professional construction and contracting services for commercial and residential projects.','خدمات البناء والمقاولات الاحترافية للمشاريع التجارية والسكنية.','🏗️','#2B6CB0',1,1,'2026-03-31 15:39:53','2026-03-31 16:12:14'),(2,'Home Maintenance','صيانة المنازل','home-maintenance','Complete home repair and maintenance services at your doorstep.','خدمات إصلاح وصيانة المنازل الشاملة عند باب منزلك.','🏠','#38A169',3,1,'2026-03-31 15:39:53','2026-04-03 14:08:24'),(3,'Camera System and Security','أنظمة الكاميرات والأمان','camera-system-and-security','Advanced security and surveillance solutions for your property.','حلول أمنية ومراقبة متقدمة لممتلكاتك.','📹','#E53E3E',2,1,'2026-03-31 15:39:53','2026-04-03 14:13:17'),(4,'Software Development and Marketing','تطوير البرمجيات والتسويق','software-dev-marketing','Custom software, web development, and digital marketing solutions for your business.','حلول تطوير البرمجيات والمواقع والتسويق الرقمي المخصصة لعملك.','💻','#805AD5',4,1,'2026-03-31 15:39:53','2026-04-03 16:52:34');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` VALUES ('1RGnd2CBUV2pIsKZnnAwfDyidmbamDs7DYxOhs99',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJMc0FVeGM5T0g1Qmx0cjdsOUtDTExVc05WZDNGQ1VUT05ZbldOMGNNIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484722),('AHk6kMm0dNZ9mnRhZg4wk5B0Luyo6CPXZtM9oeDV',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJaWmpxa0VUQUJWMUJHRDJpOVUxMWJHY3hDOWJZcjNrVkFjamtHOHg5IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484765),('AjPNo6WSPemYrK1KS3g1Ym6UHed8MaousW5I10EZ',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJLOWFrRVlFQlRBQ1hybGh5NUw1RzhBQjg2M29QMzhGUWs5OTBjQkRsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9kZWJ1Zy1ibGFkZSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484812),('EIMHCowYNUL7arWFfFTep0cl6SsfwptCqgjWI3fB',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJ0V2NieHlDU0RJbVBrTnRpTW5xVnBJMjJMaHF5ODd5WWV4azZqSEVuIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484722),('iZlOMHTwAjl4o3F895LlJfdcmGviTxxt69mUKwf6',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJoc2JidFRQQzhjQmJOMU56UEh3ZlZiZXhnYXVLTHNmZjNGNU1vd21MIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484585),('jGV5Rm1LP81DZsKhxmOzh49k2Mab4cOQCWn9ArGQ',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJ6OHFmaHRmSWhmOUZNa1VLT0s1WjJHaUNpZG1oeDl1ekNUZ3pza2hGIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484408),('jRX8B2MX1Z7Iq7NGrUvhSpGmtookq6CZHQyejiUU',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJSZVdNenhkakY4eDFYNkZEWGpFVXJ2cU1sZWVtUVdKZ0UwT3BUZE9MIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484605),('LjpLMqbPrZIEWVVaO12THfbCz84lZ3wZeL5zThBu',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJrZkFxaW9scUJXQVN4RllDYXQzTzFZeVo1V3QxTVFycUNleE1aNFZYIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1775487461),('LmjIOZidUNv2MXBDht8gUGUOHmGreJBSPW2ds8PV',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJoMlRaZG0zMjhncXN5NDRJOURONEFoQVREWEc0b2VMWFNNSXE1RUFEIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484843),('nzNbTLLjQXMAtDmetiKUx4FNfiGPwNlk36OfbF2W',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJaQjRnVkFzdHBoMUM2MDRBNXFkZmtqUzJNQkxYZkp3b25MdG1sWWdSIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484662),('pchwoz6zUXpDGpEm05b4qYEupPjSwHCtuCs6Tebx',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiI1Y1JBUnFwWldmWU9Lc2U2ZWxTTlc5cUt1YmJlN290Z2hvNERlRWMwIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484777),('QGExOKZebGRdligaMIa44S1OrZMWXqyW8hnJDM8j',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJ5Rk5waDV2cnhrVjVyUzZpc3d1d2xHdHFFV3JHTGZ1VDB5TXBEa3BzIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484692),('rHk6A75tbqla2Q1whmFsUwm0HqqOkTlxdkAjpOEA',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJIVDRnblpXNDRlYTZBWHRFUnhUNXR3Y1o5ajdieXJPamp0Z3V5T1ljIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484625),('Wu2tYwyhbN5lC8UaASchFV6x6Jl05fGOA9PMWgtx',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJhanhuMXRoSzQ2eVZrSmx5b3RFMHVnNnFmQ0ttTFlWRTU5ZVE4NURVIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775484662);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_services`
--

DROP TABLE IF EXISTS `sub_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_id` bigint unsigned NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sub_services_slug_unique` (`slug`),
  KEY `sub_services_service_id_foreign` (`service_id`),
  CONSTRAINT `sub_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_services`
--

LOCK TABLES `sub_services` WRITE;
/*!40000 ALTER TABLE `sub_services` DISABLE KEYS */;
INSERT INTO `sub_services` VALUES (1,1,'General Construction','البناء العام','general-construction',NULL,NULL,'hammer',1,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(2,1,'Renovation','التجديد','renovation',NULL,NULL,'paint-roller',2,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(3,2,'AC Repair & Maintenance','إصلاح وصيانة المكيفات','ac-repair',NULL,NULL,'snowflake',1,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(4,2,'Plumbing','السباكة','plumbing',NULL,NULL,'droplet',2,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(5,2,'Electrical','الكهرباء','electrical',NULL,NULL,'zap',3,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(6,2,'Painting','الدهان','painting',NULL,NULL,'paintbrush',4,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(8,3,'Alarm Systems','أنظمة الإنذار','alarm-systems',NULL,NULL,'bell',2,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(11,2,'Pest Control','مكافحة الحشرات','pest-control',NULL,NULL,'bug',5,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(12,2,'Carpentry','النجارة والأخشاب','carpentry',NULL,NULL,'table',6,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(13,2,'Appliance Repair','إصلاح الأجهزة المنزلية','appliance-repair',NULL,NULL,'zap',7,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(14,3,'Intercom & Access Control','الإنتركم وأنظمة الدخول','intercom-access',NULL,NULL,'door-closed',3,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(15,3,'Smart Home Automation','أتمتة المنازل الذكية','smart-home',NULL,NULL,'wifi',4,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(18,1,'Gypsum & Ceilings','أسقف وجبس بورد','gypsum-ceilings',NULL,NULL,'layers',3,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(19,1,'Water & Heat Proofing','العزل المائي والحراري','waterproofing',NULL,NULL,'umbrella',4,1,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(27,3,'CCTV Hardware (Cameras)','كاميرات المراقبة','cctv-cameras',NULL,NULL,'camera',1,1,'2026-04-03 21:28:40','2026-04-03 21:28:40'),(28,3,'DVR/NVR Recorders','أجهزة التسجيل (DVR/NVR)','cctv-recorders',NULL,NULL,'hard-drive',2,1,'2026-04-03 21:28:40','2026-04-03 21:28:40'),(29,3,'Storage & Installation','التخزين والتركيب','cctv-accessories',NULL,NULL,'layers',3,1,'2026-04-03 21:28:40','2026-04-03 21:28:40'),(30,4,'Web Development','تطوير المواقع','web-development',NULL,NULL,'globe',1,1,'2026-04-03 21:28:40','2026-04-03 21:28:40'),(31,4,'Mobile App Development','تطوير تطبيقات الجوال','mobile-development',NULL,NULL,'smartphone',2,1,'2026-04-03 21:28:40','2026-04-03 21:28:40'),(32,4,'Digital Marketing','التسويق الرقمي','digital-marketing',NULL,NULL,'megaphone',5,1,'2026-04-03 21:28:40','2026-04-04 22:00:37'),(35,4,'Mobile Apps','تطبيقات الجوال','mobile-apps',NULL,NULL,'smartphone',2,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(36,4,'Point of Sale (POS)','أنظمة نقاط البيع','pos-systems',NULL,NULL,'printer',3,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(37,4,'Networking & IT','الشبكات والدعم الفني','networking',NULL,NULL,'server',4,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(38,4,'SEO & Analytics','تحسين الظهور والتحليلات','seo-analytics',NULL,NULL,'line-chart',6,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(39,4,'E-Commerce Solutions','حلول التجارة الإلكترونية','ecommerce-solutions',NULL,NULL,'shopping-cart',7,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(40,4,'Branding & Content','الهوية البصرية وصناعة المحتوى','branding-content',NULL,NULL,'pen-tool',8,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(41,4,'ERP / CRM Automation','أتمتة ERP و CRM','erp-crm-automation',NULL,NULL,'cpu',9,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(42,4,'UI/UX Design','تصميم واجهات وتجربة المستخدم','ui-ux-design',NULL,NULL,'layout',10,1,'2026-04-04 22:00:37','2026-04-04 22:00:37'),(43,3,'Complete Camera Packages','باقات الكاميرات الكاملة','camera-packages',NULL,NULL,'camera',1,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(44,3,'Installation Services','خدمات التركيب','store-installation',NULL,NULL,'hammer',2,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(45,3,'Attendance Devices & Smart Locks','أجهزة الحضور والأقفال الذكية','store-attendance-locks',NULL,NULL,'bell',3,1,'2026-04-05 11:20:15','2026-04-05 11:20:15'),(46,3,'Dashcams & GPS Tracking','داش كام وأجهزة التتبع','store-dashcams',NULL,NULL,'smartphone',4,1,'2026-04-05 11:20:15','2026-04-05 11:20:15');
/*!40000 ALTER TABLE `sub_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technician_profiles`
--

DROP TABLE IF EXISTS `technician_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `technician_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `bio_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bio_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `certifications` json DEFAULT NULL,
  `specializations` json DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_jobs` int NOT NULL DEFAULT '0',
  `completed_jobs` int NOT NULL DEFAULT '0',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `technician_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `technician_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technician_profiles`
--

LOCK TABLES `technician_profiles` WRITE;
/*!40000 ALTER TABLE `technician_profiles` DISABLE KEYS */;
INSERT INTO `technician_profiles` VALUES (1,3,'Expert AC and plumbing technician with 10+ years of experience.','فني تكييف وسباكة خبير بخبرة تفوق 10 سنوات.',NULL,NULL,1,1,4.80,160,148,NULL,NULL,'2026-03-31 15:39:53','2026-04-03 22:12:43'),(2,4,'Specialized in electrical systems and CCTV installation.','متخصص في الأنظمة الكهربائية وتركيب كاميرات المراقبة.',NULL,NULL,1,1,4.60,98,92,NULL,NULL,'2026-03-31 15:39:53','2026-03-31 16:29:46'),(3,6,'Expert in AC repair, CCTV installation, and general maintenance.','متخصص في صيانة المكيفات وتركيب كاميرات المراقبة والصيانة العامة.',NULL,'\"[\\\"AC Repair\\\",\\\"CCTV\\\",\\\"Plumbing\\\"]\"',1,1,4.80,17,11,24.88440513,46.83164215,'2026-03-31 16:31:44','2026-04-05 16:06:19');
/*!40000 ALTER TABLE `technician_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locale` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `is_blacklisted` tinyint(1) NOT NULL DEFAULT '0',
  `phone_verified` tinyint(1) NOT NULL DEFAULT '0',
  `otp_code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','admin@alkifah.com','+966500000001','super_admin',NULL,'ar',0,1,NULL,NULL,'2026-03-31 15:39:52','$2y$12$s60Vu4evZUN/ycWCSBUCievogtwaw2IvrwNcMEaKwGVywn.0CTXau',NULL,NULL,NULL,'2026-03-31 15:39:52','2026-04-03 18:01:47'),(2,'Ahmed Al-Rashid','manager@alkifah.com','+966500000002','technical_manager',NULL,'ar',0,1,NULL,NULL,'2026-03-31 15:39:52','$2y$12$TTfyD/0Zfot6Q9utpJoJUOzFogi0AM3ZQvIUkKeCaa.4JM0ME/ckS',NULL,NULL,NULL,'2026-03-31 15:39:52','2026-04-04 09:03:36'),(3,'Mohammed Hassan','tech1@alkifah.com','+966500000003','technician',NULL,'ar',0,1,NULL,NULL,'2026-03-31 15:39:52','$2y$12$kPJgVG5g2/BsV.QshG7w/Oe1Bta1bLq9a.c3.uJiYjNOn.DNKajTi',NULL,NULL,NULL,'2026-03-31 15:39:52','2026-04-03 14:04:55'),(4,'Khalid Omar','tech2@alkifah.com','+966500000004','technician',NULL,'en',0,1,NULL,NULL,'2026-03-31 15:39:53','$2y$12$5oalBqgws0T.KfY2X7GFV.7ynemSng1jq80vKEe3XlOtq5vN8a1gi',NULL,NULL,NULL,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(5,'Sara Abdullah','client@alkifah.com','+966500000005','client',NULL,'ar',0,1,NULL,NULL,'2026-03-31 15:39:53','$2y$12$Y/UvlfD8qbxZZCjQojCxYe2hGPxDD3QWPnqjomWEBIip8lGmOSCEO',NULL,NULL,NULL,'2026-03-31 15:39:53','2026-04-03 16:48:03'),(6,'Ahmed Al-Harbi','agent@kifah.test','+966501112233','technician',NULL,'en',0,1,NULL,NULL,NULL,'$2y$12$CtSUwDqEVV7Aj7s6DJZgAeRTMwKHjZVRVF7TyEd8Wy7Iyclh/lQ..',NULL,NULL,NULL,'2026-03-31 16:31:44','2026-03-31 16:31:44'),(7,'Guest_9347','guest_1775072575@kifah.app','0534152032','client',NULL,'en',0,0,'040761','2026-04-01 18:47:55',NULL,'$2y$12$hKGm77s8bSl0GvKYs9w6mub9DHRWJaUVyaH/ro5/4jrl6PAqQpbOS',NULL,NULL,NULL,'2026-04-01 18:42:55','2026-04-01 18:42:55'),(8,'Guest_4246','guest_1775221548@kifah.app','0555667432','client',NULL,'en',0,0,'480257','2026-04-03 12:10:48',NULL,'$2y$12$A9yXbjhC84p0vjWUFCRZSOiw0ExG8E6kq3Bd9ST8IGQv.L0yrASMO',NULL,NULL,NULL,'2026-04-03 12:05:48','2026-04-03 12:05:48'),(9,'Guest_5300','guest_1775221706@kifah.app','0565677889','client',NULL,'en',0,1,NULL,NULL,NULL,'$2y$12$0p1JhoI1kXgJS4zYRUAjzuXLahBLFqZeVFaaIT31bpRn/ix.e0nPS',NULL,NULL,'4RjwkhA5rHTVUS5vpRAVu2nuGksTURY9X7Syu0JLUnmnqYKi6CGTQyDoNYZy','2026-04-03 12:08:27','2026-04-03 12:11:18'),(10,'Guest_4680','guest_1775222123@kifah.app','0543266755','client',NULL,'en',0,1,NULL,NULL,NULL,'$2y$12$VzUV2b8snX1cKSjwQfaeXOjKCXMBP8oizKEtYFgffoH3rQvip/u7K',NULL,NULL,'39bAayYvEPEhE94u9B3sggB2ltvRt7uhoskCOefgDs235Cq4BhMd7CTeB7g9','2026-04-03 12:15:23','2026-04-03 12:15:40'),(11,'hamdi zine','client_1775222873@kifah.app','0512345678','client',NULL,'ar',0,1,NULL,NULL,NULL,'$2y$12$cldC9SUBCc5vckHfQfUv9.xME8.s7i5MI3JcLbHGqWA1YrvdU4T9S',NULL,NULL,'mXpxt9DTtdxjZt1T2lqix1w7sJMBFsh68BECqpO0L9955LWLwBsVJXF3JSz5','2026-04-03 12:27:54','2026-04-03 22:12:43'),(12,'hamdizo','client_1775257280@kifah.app','0598765432','client',NULL,'en',0,1,NULL,NULL,NULL,'$2y$12$qbVd3172/ukhlOAw2HdG8eRfwrNmfYcH8bP.B6aBElQ5NKqi78f2.',NULL,NULL,'4wKwsDJ08vUuso18mxTOhIo1MFqCP1nF6NADEpuodsaIoG1MVf04mNAAeFQx','2026-04-03 22:01:20','2026-04-03 22:01:35'),(13,'Hamdizan','client_1775408591@kifah.app','0587654321','client',NULL,'ar',0,1,NULL,NULL,NULL,'$2y$12$KhvtbgyflQR0gFVbYFFpFeMkMAAjOf.D7ZezeDjpPhnsQCdg5qWWC',NULL,NULL,'VGoyKqe8vfb9zQQ07kceTuSBlouZI92QEeFZ0OnZGLQM73C5UPiRQ6K8LHeI','2026-04-05 16:03:11','2026-04-05 16:05:52');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_transactions`
--

DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` bigint unsigned NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `balance_after` decimal(12,2) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transactionable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transactionable_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`),
  KEY `wt_transactionable_index` (`transactionable_type`,`transactionable_id`),
  CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_transactions`
--

LOCK TABLES `wallet_transactions` WRITE;
/*!40000 ALTER TABLE `wallet_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_earned` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_withdrawn` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallets_user_id_foreign` (`user_id`),
  CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallets`
--

LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
INSERT INTO `wallets` VALUES (1,3,3500.00,15200.00,0.00,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(2,4,2100.00,9800.00,0.00,'2026-03-31 15:39:53','2026-03-31 15:39:53'),(3,6,250.00,1850.00,0.00,'2026-03-31 16:31:44','2026-03-31 16:31:44');
/*!40000 ALTER TABLE `wallets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-06 16:05:04
