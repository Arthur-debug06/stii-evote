-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: voting_system
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `action_logs`
--

DROP TABLE IF EXISTS `action_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `action_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `trackable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trackable_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_uuid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `action_logs_document_type_document_id_index` (`document_type`,`document_id`),
  KEY `action_logs_trackable_type_trackable_id_index` (`trackable_type`,`trackable_id`),
  KEY `action_logs_user_id_index` (`user_id`),
  KEY `action_logs_action_index` (`action`),
  KEY `action_logs_batch_uuid_index` (`batch_uuid`),
  KEY `action_logs_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `action_logs`
--

LOCK TABLES `action_logs` WRITE;
/*!40000 ALTER TABLE `action_logs` DISABLE KEYS */;
INSERT INTO `action_logs` VALUES (1,'App\\Models\\applied_candidacy',1,1,1,'created','Created applied_candidacy','{\"attributes\":{\"students_id\":\"1\",\"position_id\":\"1\",\"school_year_and_semester_id\":\"2\",\"is_regular_student\":true,\"status\":\"pending\",\"id\":\"1\"},\"changes_summary\":[{\"field\":\"students_id\",\"from\":null,\"to\":\"1\"},{\"field\":\"position_id\",\"from\":null,\"to\":\"1\"},{\"field\":\"school_year_and_semester_id\",\"from\":null,\"to\":\"2\"},{\"field\":\"is_regular_student\",\"from\":null,\"to\":true},{\"field\":\"status\",\"from\":null,\"to\":\"pending\"},{\"field\":\"id\",\"from\":null,\"to\":\"1\"}]}','App\\Models\\applied_candidacy',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 02:27:53','2025-09-08 02:27:53'),(2,'App\\Models\\applied_candidacy',1,1,1,'candidacy_submitted','Candidacy_submitted applied_candidacy','{\"message\":\"Candidacy application submitted by student: Maite Garner\",\"signatories_notified\":1}','App\\Models\\applied_candidacy',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 02:27:53','2025-09-08 02:27:53'),(3,'App\\Models\\applied_candidacy',1,1,1,'updated','Updated applied_candidacy','{\"old\":{\"id\":\"1\",\"students_id\":\"1\",\"position_id\":\"1\",\"school_year_and_semester_id\":\"2\",\"is_regular_student\":\"1\",\"remarks\":null,\"status\":\"pending\"},\"attributes\":{\"id\":\"1\",\"students_id\":\"1\",\"position_id\":\"1\",\"school_year_and_semester_id\":\"2\",\"is_regular_student\":\"1\",\"remarks\":\"Your application is approved\",\"status\":\"approved\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"students_id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"position_id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"school_year_and_semester_id\",\"from\":\"2\",\"to\":\"2\"},{\"field\":\"is_regular_student\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"remarks\",\"from\":null,\"to\":\"Your application is approved\"},{\"field\":\"status\",\"from\":\"pending\",\"to\":\"approved\"}]}','App\\Models\\applied_candidacy',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 02:28:17','2025-09-08 02:28:17'),(4,'App\\Models\\applied_candidacy',1,1,1,'candidacy_approved','Candidacy_approved applied_candidacy','{\"message\":\"Candidacy application approved by signatory\",\"approved_by\":1,\"notification_id\":1}','App\\Models\\applied_candidacy',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 02:28:17','2025-09-08 02:28:17'),(5,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"3g1t0VdwSmrhTer41pIYa2jt98Jh0MpZSYdiSGc41AZyD4iXupd8hrWtdE7k\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"NjeSYmHdo2vV3mmkMWdRHSw3b6WjyX3x8yIT4q5XUVe5XAUmoWYdG34j3CTs\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"3g1t0VdwSmrhTer41pIYa2jt98Jh0MpZSYdiSGc41AZyD4iXupd8hrWtdE7k\",\"to\":\"NjeSYmHdo2vV3mmkMWdRHSw3b6WjyX3x8yIT4q5XUVe5XAUmoWYdG34j3CTs\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 02:29:01','2025-09-08 02:29:01'),(6,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"NjeSYmHdo2vV3mmkMWdRHSw3b6WjyX3x8yIT4q5XUVe5XAUmoWYdG34j3CTs\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"oP0nGlEQZ4Sf10Ti4o7mQBrnyE0OgbuYObDTV3OWUas5GEwaGi8KK6lMVapc\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"NjeSYmHdo2vV3mmkMWdRHSw3b6WjyX3x8yIT4q5XUVe5XAUmoWYdG34j3CTs\",\"to\":\"oP0nGlEQZ4Sf10Ti4o7mQBrnyE0OgbuYObDTV3OWUas5GEwaGi8KK6lMVapc\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 02:34:48','2025-09-08 02:34:48'),(7,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"oP0nGlEQZ4Sf10Ti4o7mQBrnyE0OgbuYObDTV3OWUas5GEwaGi8KK6lMVapc\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"sVM0xmrbRTZSQUzOIr2HbHGC4SYRngCNB7oT7YGl1yOcLo23IuW9QNzJmWss\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"oP0nGlEQZ4Sf10Ti4o7mQBrnyE0OgbuYObDTV3OWUas5GEwaGi8KK6lMVapc\",\"to\":\"sVM0xmrbRTZSQUzOIr2HbHGC4SYRngCNB7oT7YGl1yOcLo23IuW9QNzJmWss\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 02:48:00','2025-09-08 02:48:00'),(8,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"sVM0xmrbRTZSQUzOIr2HbHGC4SYRngCNB7oT7YGl1yOcLo23IuW9QNzJmWss\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"Hp0da8n87LTqFWaz8c89X36dMZO7krQBnvVvGZduuj5cluIBjL10bYzJa48Z\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"sVM0xmrbRTZSQUzOIr2HbHGC4SYRngCNB7oT7YGl1yOcLo23IuW9QNzJmWss\",\"to\":\"Hp0da8n87LTqFWaz8c89X36dMZO7krQBnvVvGZduuj5cluIBjL10bYzJa48Z\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 04:49:19','2025-09-08 04:49:19'),(9,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"Hp0da8n87LTqFWaz8c89X36dMZO7krQBnvVvGZduuj5cluIBjL10bYzJa48Z\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"KqIVVzPIGuzQ8Dy40N7JENAfko1GR8qxUiHYqQNPD4zhCBekVq5eZE3NpX0P\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"Hp0da8n87LTqFWaz8c89X36dMZO7krQBnvVvGZduuj5cluIBjL10bYzJa48Z\",\"to\":\"KqIVVzPIGuzQ8Dy40N7JENAfko1GR8qxUiHYqQNPD4zhCBekVq5eZE3NpX0P\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 04:56:16','2025-09-08 04:56:16'),(10,'App\\Models\\system_settings',1,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"1\",\"key\":\"sidebar_logo\",\"value\":null,\"type\":\"image\",\"module_id\":\"1\",\"description\":null,\"status\":null},\"attributes\":{\"id\":\"1\",\"key\":\"sidebar_logo\",\"value\":\"C:\\\\fakepath\\\\541989198_1326439312317329_283266132766343073_n.jpg\",\"type\":\"image\",\"module_id\":\"1\",\"description\":\"rtgvvfgvfvd\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"key\",\"from\":\"sidebar_logo\",\"to\":\"sidebar_logo\"},{\"field\":\"value\",\"from\":null,\"to\":\"C:\\\\fakepath\\\\541989198_1326439312317329_283266132766343073_n.jpg\"},{\"field\":\"type\",\"from\":\"image\",\"to\":\"image\"},{\"field\":\"module_id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"description\",\"from\":null,\"to\":\"rtgvvfgvfvd\"},{\"field\":\"status\",\"from\":null,\"to\":\"active\"}]}','App\\Models\\system_settings',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:16:59','2025-09-08 05:16:59'),(11,'App\\Models\\system_settings',1,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"1\",\"key\":\"sidebar_logo\",\"value\":\"C:\\\\fakepath\\\\541989198_1326439312317329_283266132766343073_n.jpg\",\"type\":\"image\",\"module_id\":\"1\",\"description\":\"rtgvvfgvfvd\",\"status\":\"active\"},\"attributes\":{\"id\":\"1\",\"key\":\"sidebar_logo\",\"value\":\"system_settings\\/1757308773_541989198_1326439312317329_283266132766343073_n.jpg\",\"type\":\"image\",\"module_id\":\"1\",\"description\":\"rtgvvfgvfvd\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"key\",\"from\":\"sidebar_logo\",\"to\":\"sidebar_logo\"},{\"field\":\"value\",\"from\":\"C:\\\\fakepath\\\\541989198_1326439312317329_283266132766343073_n.jpg\",\"to\":\"system_settings\\/1757308773_541989198_1326439312317329_283266132766343073_n.jpg\"},{\"field\":\"type\",\"from\":\"image\",\"to\":\"image\"},{\"field\":\"module_id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"description\",\"from\":\"rtgvvfgvfvd\",\"to\":\"rtgvvfgvfvd\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"}]}','App\\Models\\system_settings',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:19:33','2025-09-08 05:19:33'),(12,'App\\Models\\system_settings',2,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"2\",\"key\":\"sidebar_text_logo\",\"value\":null,\"type\":\"text\",\"module_id\":\"2\",\"description\":null,\"status\":null},\"attributes\":{\"id\":\"2\",\"key\":\"sidebar_text_logo\",\"value\":\"Voting System\",\"type\":\"text\",\"module_id\":\"2\",\"description\":\"fdcvdf\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"2\",\"to\":\"2\"},{\"field\":\"key\",\"from\":\"sidebar_text_logo\",\"to\":\"sidebar_text_logo\"},{\"field\":\"value\",\"from\":null,\"to\":\"Voting System\"},{\"field\":\"type\",\"from\":\"text\",\"to\":\"text\"},{\"field\":\"module_id\",\"from\":\"2\",\"to\":\"2\"},{\"field\":\"description\",\"from\":null,\"to\":\"fdcvdf\"},{\"field\":\"status\",\"from\":null,\"to\":\"active\"}]}','App\\Models\\system_settings',2,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:24:55','2025-09-08 05:24:55'),(13,'App\\Models\\system_settings',3,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"3\",\"key\":\"login_center_logo\",\"value\":null,\"type\":\"image\",\"module_id\":\"3\",\"description\":null,\"status\":null},\"attributes\":{\"id\":\"3\",\"key\":\"login_center_logo\",\"value\":\"system_settings\\/1757309292_541989198_1326439312317329_283266132766343073_n.jpg\",\"type\":\"image\",\"module_id\":\"3\",\"description\":\"bfgdbfgvbfg\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"3\",\"to\":\"3\"},{\"field\":\"key\",\"from\":\"login_center_logo\",\"to\":\"login_center_logo\"},{\"field\":\"value\",\"from\":null,\"to\":\"system_settings\\/1757309292_541989198_1326439312317329_283266132766343073_n.jpg\"},{\"field\":\"type\",\"from\":\"image\",\"to\":\"image\"},{\"field\":\"module_id\",\"from\":\"3\",\"to\":\"3\"},{\"field\":\"description\",\"from\":null,\"to\":\"bfgdbfgvbfg\"},{\"field\":\"status\",\"from\":null,\"to\":\"active\"}]}','App\\Models\\system_settings',3,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:28:12','2025-09-08 05:28:12'),(14,'App\\Models\\system_settings',3,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"3\",\"key\":\"login_center_logo\",\"value\":\"system_settings\\/1757309292_541989198_1326439312317329_283266132766343073_n.jpg\",\"type\":\"image\",\"module_id\":\"3\",\"description\":\"bfgdbfgvbfg\",\"status\":\"active\"},\"attributes\":{\"id\":\"3\",\"key\":\"login_center_logo\",\"value\":\"system_settings\\/1757309336_dssc_logo_official.png\",\"type\":\"image\",\"module_id\":\"3\",\"description\":\"bfgdbfgvbfg\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"3\",\"to\":\"3\"},{\"field\":\"key\",\"from\":\"login_center_logo\",\"to\":\"login_center_logo\"},{\"field\":\"value\",\"from\":\"system_settings\\/1757309292_541989198_1326439312317329_283266132766343073_n.jpg\",\"to\":\"system_settings\\/1757309336_dssc_logo_official.png\"},{\"field\":\"type\",\"from\":\"image\",\"to\":\"image\"},{\"field\":\"module_id\",\"from\":\"3\",\"to\":\"3\"},{\"field\":\"description\",\"from\":\"bfgdbfgvbfg\",\"to\":\"bfgdbfgvbfg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"}]}','App\\Models\\system_settings',3,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:28:56','2025-09-08 05:28:56'),(15,'App\\Models\\system_settings',6,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"6\",\"key\":\"login_top_logo\",\"value\":null,\"type\":\"image\",\"module_id\":\"6\",\"description\":null,\"status\":null},\"attributes\":{\"id\":\"6\",\"key\":\"login_top_logo\",\"value\":\"system_settings\\/1757309463_dssc_logo_official.png\",\"type\":\"image\",\"module_id\":\"6\",\"description\":\"\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"6\",\"to\":\"6\"},{\"field\":\"key\",\"from\":\"login_top_logo\",\"to\":\"login_top_logo\"},{\"field\":\"value\",\"from\":null,\"to\":\"system_settings\\/1757309463_dssc_logo_official.png\"},{\"field\":\"type\",\"from\":\"image\",\"to\":\"image\"},{\"field\":\"module_id\",\"from\":\"6\",\"to\":\"6\"},{\"field\":\"description\",\"from\":null,\"to\":\"\"},{\"field\":\"status\",\"from\":null,\"to\":\"active\"}]}','App\\Models\\system_settings',6,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:31:03','2025-09-08 05:31:03'),(16,'App\\Models\\system_settings',4,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"4\",\"key\":\"login_top_text\",\"value\":null,\"type\":\"text\",\"module_id\":\"4\",\"description\":null,\"status\":null},\"attributes\":{\"id\":\"4\",\"key\":\"login_top_text\",\"value\":\"Voting System\",\"type\":\"text\",\"module_id\":\"4\",\"description\":\"vfgvfdvdf\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"4\",\"to\":\"4\"},{\"field\":\"key\",\"from\":\"login_top_text\",\"to\":\"login_top_text\"},{\"field\":\"value\",\"from\":null,\"to\":\"Voting System\"},{\"field\":\"type\",\"from\":\"text\",\"to\":\"text\"},{\"field\":\"module_id\",\"from\":\"4\",\"to\":\"4\"},{\"field\":\"description\",\"from\":null,\"to\":\"vfgvfdvdf\"},{\"field\":\"status\",\"from\":null,\"to\":\"active\"}]}','App\\Models\\system_settings',4,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:33:08','2025-09-08 05:33:08'),(17,'App\\Models\\system_settings',5,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"5\",\"key\":\"login_center_text\",\"value\":null,\"type\":\"text\",\"module_id\":\"5\",\"description\":null,\"status\":null},\"attributes\":{\"id\":\"5\",\"key\":\"login_center_text\",\"value\":\"Voting Management System\",\"type\":\"text\",\"module_id\":\"5\",\"description\":\"vdfvfvdf\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"5\",\"to\":\"5\"},{\"field\":\"key\",\"from\":\"login_center_text\",\"to\":\"login_center_text\"},{\"field\":\"value\",\"from\":null,\"to\":\"Voting Management System\"},{\"field\":\"type\",\"from\":\"text\",\"to\":\"text\"},{\"field\":\"module_id\",\"from\":\"5\",\"to\":\"5\"},{\"field\":\"description\",\"from\":null,\"to\":\"vdfvfvdf\"},{\"field\":\"status\",\"from\":null,\"to\":\"active\"}]}','App\\Models\\system_settings',5,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:34:23','2025-09-08 05:34:23'),(18,'App\\Models\\system_settings',1,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"1\",\"key\":\"sidebar_logo\",\"value\":\"system_settings\\/1757308773_541989198_1326439312317329_283266132766343073_n.jpg\",\"type\":\"image\",\"module_id\":\"1\",\"description\":\"rtgvvfgvfvd\",\"status\":\"active\"},\"attributes\":{\"id\":\"1\",\"key\":\"sidebar_logo\",\"value\":\"system_settings\\/1757309884_dssc_logo_official.png\",\"type\":\"image\",\"module_id\":\"1\",\"description\":\"rtgvvfgvfvd\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"key\",\"from\":\"sidebar_logo\",\"to\":\"sidebar_logo\"},{\"field\":\"value\",\"from\":\"system_settings\\/1757308773_541989198_1326439312317329_283266132766343073_n.jpg\",\"to\":\"system_settings\\/1757309884_dssc_logo_official.png\"},{\"field\":\"type\",\"from\":\"image\",\"to\":\"image\"},{\"field\":\"module_id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"description\",\"from\":\"rtgvvfgvfvd\",\"to\":\"rtgvvfgvfvd\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"}]}','App\\Models\\system_settings',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:38:04','2025-09-08 05:38:04'),(19,'App\\Models\\system_settings',3,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"3\",\"key\":\"login_center_logo\",\"value\":\"system_settings\\/1757309336_dssc_logo_official.png\",\"type\":\"image\",\"module_id\":\"3\",\"description\":\"bfgdbfgvbfg\",\"status\":\"active\"},\"attributes\":{\"id\":\"3\",\"key\":\"login_center_logo\",\"value\":\"system_settings\\/1757310054_958f3106-933e-45f6-94d4-16494684712d-modified.png\",\"type\":\"image\",\"module_id\":\"3\",\"description\":\"bfgdbfgvbfg\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"3\",\"to\":\"3\"},{\"field\":\"key\",\"from\":\"login_center_logo\",\"to\":\"login_center_logo\"},{\"field\":\"value\",\"from\":\"system_settings\\/1757309336_dssc_logo_official.png\",\"to\":\"system_settings\\/1757310054_958f3106-933e-45f6-94d4-16494684712d-modified.png\"},{\"field\":\"type\",\"from\":\"image\",\"to\":\"image\"},{\"field\":\"module_id\",\"from\":\"3\",\"to\":\"3\"},{\"field\":\"description\",\"from\":\"bfgdbfgvbfg\",\"to\":\"bfgdbfgvbfg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"}]}','App\\Models\\system_settings',3,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:40:54','2025-09-08 05:40:54'),(20,'App\\Models\\system_settings',1,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"1\",\"key\":\"sidebar_logo\",\"value\":\"system_settings\\/1757309884_dssc_logo_official.png\",\"type\":\"image\",\"module_id\":\"1\",\"description\":\"rtgvvfgvfvd\",\"status\":\"active\"},\"attributes\":{\"id\":\"1\",\"key\":\"sidebar_logo\",\"value\":\"system_settings\\/1757310064_958f3106-933e-45f6-94d4-16494684712d-modified.png\",\"type\":\"image\",\"module_id\":\"1\",\"description\":\"rtgvvfgvfvd\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"key\",\"from\":\"sidebar_logo\",\"to\":\"sidebar_logo\"},{\"field\":\"value\",\"from\":\"system_settings\\/1757309884_dssc_logo_official.png\",\"to\":\"system_settings\\/1757310064_958f3106-933e-45f6-94d4-16494684712d-modified.png\"},{\"field\":\"type\",\"from\":\"image\",\"to\":\"image\"},{\"field\":\"module_id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"description\",\"from\":\"rtgvvfgvfvd\",\"to\":\"rtgvvfgvfvd\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"}]}','App\\Models\\system_settings',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:41:04','2025-09-08 05:41:04'),(21,'App\\Models\\system_settings',6,1,1,'updated','Updated system_settings','{\"old\":{\"id\":\"6\",\"key\":\"login_top_logo\",\"value\":\"system_settings\\/1757309463_dssc_logo_official.png\",\"type\":\"image\",\"module_id\":\"6\",\"description\":\"\",\"status\":\"active\"},\"attributes\":{\"id\":\"6\",\"key\":\"login_top_logo\",\"value\":\"system_settings\\/1757310198_958f3106-933e-45f6-94d4-16494684712d-modified.png\",\"type\":\"image\",\"module_id\":\"6\",\"description\":\"\",\"status\":\"active\"},\"changes_summary\":[{\"field\":\"id\",\"from\":\"6\",\"to\":\"6\"},{\"field\":\"key\",\"from\":\"login_top_logo\",\"to\":\"login_top_logo\"},{\"field\":\"value\",\"from\":\"system_settings\\/1757309463_dssc_logo_official.png\",\"to\":\"system_settings\\/1757310198_958f3106-933e-45f6-94d4-16494684712d-modified.png\"},{\"field\":\"type\",\"from\":\"image\",\"to\":\"image\"},{\"field\":\"module_id\",\"from\":\"6\",\"to\":\"6\"},{\"field\":\"description\",\"from\":\"\",\"to\":\"\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"}]}','App\\Models\\system_settings',6,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:43:18','2025-09-08 05:43:18'),(22,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"KqIVVzPIGuzQ8Dy40N7JENAfko1GR8qxUiHYqQNPD4zhCBekVq5eZE3NpX0P\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"NlnQjv382O3uOUOx1X8F5DiVxodKER44PxPaQtVZVXxmETI4L0cYLfGiBItj\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"KqIVVzPIGuzQ8Dy40N7JENAfko1GR8qxUiHYqQNPD4zhCBekVq5eZE3NpX0P\",\"to\":\"NlnQjv382O3uOUOx1X8F5DiVxodKER44PxPaQtVZVXxmETI4L0cYLfGiBItj\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 05:56:28','2025-09-08 05:56:28'),(23,'App\\Models\\User',1,1,1,'profile_accessed','Profile accessed for Admin profile','{\"user_type\":\"admin\",\"access_time\":\"2025-09-08T07:55:05.855083Z\",\"profile_management\":true,\"timestamp\":\"2025-09-08T07:55:05.855536Z\"}','App\\Models\\User',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','Profile Management',NULL,'2025-09-08 07:55:05','2025-09-08 07:55:05'),(24,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"NlnQjv382O3uOUOx1X8F5DiVxodKER44PxPaQtVZVXxmETI4L0cYLfGiBItj\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"RanpJdEQQfSOYxTqTsGoPZdoOO4LimzsEQO3ztlAp1lJ60e2r0xeisgqwkeb\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"NlnQjv382O3uOUOx1X8F5DiVxodKER44PxPaQtVZVXxmETI4L0cYLfGiBItj\",\"to\":\"RanpJdEQQfSOYxTqTsGoPZdoOO4LimzsEQO3ztlAp1lJ60e2r0xeisgqwkeb\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 07:55:11','2025-09-08 07:55:11'),(25,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"RanpJdEQQfSOYxTqTsGoPZdoOO4LimzsEQO3ztlAp1lJ60e2r0xeisgqwkeb\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"oAW7oVzIT2BcOhKEYVLMJVWfbOFikRV5MFUodsmj1zarT7qFjGmLPXMcYigL\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"RanpJdEQQfSOYxTqTsGoPZdoOO4LimzsEQO3ztlAp1lJ60e2r0xeisgqwkeb\",\"to\":\"oAW7oVzIT2BcOhKEYVLMJVWfbOFikRV5MFUodsmj1zarT7qFjGmLPXMcYigL\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 08:10:17','2025-09-08 08:10:17'),(26,'App\\Models\\User',1,1,1,'profile_accessed','Profile accessed for Admin profile','{\"user_type\":\"admin\",\"access_time\":\"2025-09-08T08:38:13.351743Z\",\"profile_management\":true,\"timestamp\":\"2025-09-08T08:38:13.354640Z\"}','App\\Models\\User',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','Profile Management',NULL,'2025-09-08 08:38:13','2025-09-08 08:38:13'),(27,'App\\Models\\User',1,1,1,'profile_accessed','Profile accessed for Admin profile','{\"user_type\":\"admin\",\"access_time\":\"2025-09-08T08:40:23.038431Z\",\"profile_management\":true,\"timestamp\":\"2025-09-08T08:40:23.039473Z\"}','App\\Models\\User',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','Profile Management',NULL,'2025-09-08 08:40:23','2025-09-08 08:40:23'),(28,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"oAW7oVzIT2BcOhKEYVLMJVWfbOFikRV5MFUodsmj1zarT7qFjGmLPXMcYigL\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"oPolYdO0CvrcMYFcdx9TARpE0y1gspdOOdu08jCx4FiFxrpzr81PLOZFPvNX\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"oAW7oVzIT2BcOhKEYVLMJVWfbOFikRV5MFUodsmj1zarT7qFjGmLPXMcYigL\",\"to\":\"oPolYdO0CvrcMYFcdx9TARpE0y1gspdOOdu08jCx4FiFxrpzr81PLOZFPvNX\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-08 08:42:49','2025-09-08 08:42:49'),(29,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"oPolYdO0CvrcMYFcdx9TARpE0y1gspdOOdu08jCx4FiFxrpzr81PLOZFPvNX\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"NgiOIEAy4TA04G26xbqmNDcN75uEkxui5lbSOwysbqYIoSIYlHGCCZF0JzYF\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"oPolYdO0CvrcMYFcdx9TARpE0y1gspdOOdu08jCx4FiFxrpzr81PLOZFPvNX\",\"to\":\"NgiOIEAy4TA04G26xbqmNDcN75uEkxui5lbSOwysbqYIoSIYlHGCCZF0JzYF\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-15 08:44:11','2025-09-15 08:44:11'),(30,'App\\Models\\applied_candidacy',1,1,1,'created','Created applied_candidacy','{\"attributes\":{\"students_id\":\"1\",\"position_id\":\"1\",\"school_year_and_semester_id\":\"2\",\"is_regular_student\":true,\"status\":\"pending\",\"id\":\"1\"},\"changes_summary\":[{\"field\":\"students_id\",\"from\":null,\"to\":\"1\"},{\"field\":\"position_id\",\"from\":null,\"to\":\"1\"},{\"field\":\"school_year_and_semester_id\",\"from\":null,\"to\":\"2\"},{\"field\":\"is_regular_student\",\"from\":null,\"to\":true},{\"field\":\"status\",\"from\":null,\"to\":\"pending\"},{\"field\":\"id\",\"from\":null,\"to\":\"1\"}]}','App\\Models\\applied_candidacy',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-15 11:23:41','2025-09-15 11:23:41'),(31,'App\\Models\\applied_candidacy',1,1,1,'candidacy_submitted','Candidacy_submitted applied_candidacy','{\"message\":\"Candidacy application submitted by student: Maite Garner\",\"signatories_notified\":1}','App\\Models\\applied_candidacy',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-15 11:23:41','2025-09-15 11:23:41'),(32,'App\\Models\\applied_candidacy',1,1,1,'created','Created applied_candidacy','{\"attributes\":{\"students_id\":\"1\",\"position_id\":\"1\",\"school_year_and_semester_id\":\"2\",\"partylist_id\":\"1\",\"grade_attachment\":\"grade_attachments\\/toB3NrfhI74eons8y6h4knWro7bOZ8GMsPfQuMEI.pdf\",\"is_regular_student\":true,\"status\":\"pending\",\"id\":\"1\"},\"changes_summary\":[{\"field\":\"students_id\",\"from\":null,\"to\":\"1\"},{\"field\":\"position_id\",\"from\":null,\"to\":\"1\"},{\"field\":\"school_year_and_semester_id\",\"from\":null,\"to\":\"2\"},{\"field\":\"partylist_id\",\"from\":null,\"to\":\"1\"},{\"field\":\"grade_attachment\",\"from\":null,\"to\":\"grade_attachments\\/toB3NrfhI74eons8y6h4knWro7bOZ8GMsPfQuMEI.pdf\"},{\"field\":\"is_regular_student\",\"from\":null,\"to\":true},{\"field\":\"status\",\"from\":null,\"to\":\"pending\"},{\"field\":\"id\",\"from\":null,\"to\":\"1\"}]}','App\\Models\\applied_candidacy',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-15 11:49:07','2025-09-15 11:49:07'),(33,'App\\Models\\applied_candidacy',1,1,1,'candidacy_submitted','Candidacy_submitted applied_candidacy','{\"message\":\"Candidacy application submitted by student: Maite Garner\",\"signatories_notified\":1}','App\\Models\\applied_candidacy',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-15 11:49:07','2025-09-15 11:49:07'),(34,'App\\Models\\students',1,1,1,'profile_accessed','Profile accessed for Student profile','{\"user_type\":\"student\",\"access_time\":\"2025-09-15T12:14:41.926391Z\",\"profile_management\":true,\"timestamp\":\"2025-09-15T12:14:41.927279Z\"}','App\\Models\\students',1,'127.0.0.1','Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1','Profile Management',NULL,'2025-09-15 12:14:41','2025-09-15 12:14:41'),(35,'App\\Models\\students',1,1,1,'profile_accessed','Profile accessed for Student profile','{\"user_type\":\"student\",\"access_time\":\"2025-09-15T12:14:56.983233Z\",\"profile_management\":true,\"timestamp\":\"2025-09-15T12:14:56.983526Z\"}','App\\Models\\students',1,'127.0.0.1','Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1','Profile Management',NULL,'2025-09-15 12:14:56','2025-09-15 12:14:56'),(36,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"NgiOIEAy4TA04G26xbqmNDcN75uEkxui5lbSOwysbqYIoSIYlHGCCZF0JzYF\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"Ijlka2S9TtpCF2s7cWFvBpdkOyHKi5aDsx8lXkkshTQm2fXRFvRAXOLZK1Fp\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"NgiOIEAy4TA04G26xbqmNDcN75uEkxui5lbSOwysbqYIoSIYlHGCCZF0JzYF\",\"to\":\"Ijlka2S9TtpCF2s7cWFvBpdkOyHKi5aDsx8lXkkshTQm2fXRFvRAXOLZK1Fp\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-15 12:52:59','2025-09-15 12:52:59'),(37,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"Ijlka2S9TtpCF2s7cWFvBpdkOyHKi5aDsx8lXkkshTQm2fXRFvRAXOLZK1Fp\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"ThUUg1QLc9TCjv9qHMQSEfrZ1Bm49mMB8tcR9TAisTvztacFYAka2PQnjTyV\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"Ijlka2S9TtpCF2s7cWFvBpdkOyHKi5aDsx8lXkkshTQm2fXRFvRAXOLZK1Fp\",\"to\":\"ThUUg1QLc9TCjv9qHMQSEfrZ1Bm49mMB8tcR9TAisTvztacFYAka2PQnjTyV\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-15 12:58:54','2025-09-15 12:58:54'),(38,'App\\Models\\User',1,1,1,'updated','Updated user','{\"old\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"ThUUg1QLc9TCjv9qHMQSEfrZ1Bm49mMB8tcR9TAisTvztacFYAka2PQnjTyV\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"attributes\":{\"id\":\"1\",\"name\":\"Test User\",\"email\":\"test@example.com\",\"email_verified_at\":\"2025-09-04 13:51:52\",\"password\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"remember_token\":\"40FTWmNcDKugRYos4VOG23lhtGUwynk7A8A3y1JUqgBczIGktkQpOYj6h4cY\",\"role\":\"stsg\",\"profile_image\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"status\":\"active\",\"is_online\":null},\"changes_summary\":[{\"field\":\"id\",\"from\":\"1\",\"to\":\"1\"},{\"field\":\"name\",\"from\":\"Test User\",\"to\":\"Test User\"},{\"field\":\"email\",\"from\":\"test@example.com\",\"to\":\"test@example.com\"},{\"field\":\"email_verified_at\",\"from\":\"{\\\"year\\\":2025,\\\"month\\\":9,\\\"day\\\":4,\\\"dayOfWeek\\\":4,\\\"dayOfYear\\\":247,\\\"hour\\\":13,\\\"minute\\\":51,\\\"second\\\":52,\\\"micro\\\":0,\\\"timestamp\\\":1756965112,\\\"formatted\\\":\\\"2025-09-04 13:51:52\\\",\\\"timezone\\\":{\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"Asia\\\\\\/Manila\\\"}}\",\"to\":\"2025-09-04 13:51:52\"},{\"field\":\"password\",\"from\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\",\"to\":\"$2y$12$UAhgKL8L2kWfcFmHpIi\\/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq\"},{\"field\":\"remember_token\",\"from\":\"ThUUg1QLc9TCjv9qHMQSEfrZ1Bm49mMB8tcR9TAisTvztacFYAka2PQnjTyV\",\"to\":\"40FTWmNcDKugRYos4VOG23lhtGUwynk7A8A3y1JUqgBczIGktkQpOYj6h4cY\"},{\"field\":\"role\",\"from\":\"stsg\",\"to\":\"stsg\"},{\"field\":\"profile_image\",\"from\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\",\"to\":\"profile_picture\\/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg\"},{\"field\":\"status\",\"from\":\"active\",\"to\":\"active\"},{\"field\":\"is_online\",\"from\":null,\"to\":null}]}','App\\Models\\User',1,'127.0.0.1','Chrome','Unknown Location',NULL,'2025-09-16 10:56:54','2025-09-16 10:56:54');
/*!40000 ALTER TABLE `action_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `applied_candidacy`
--

DROP TABLE IF EXISTS `applied_candidacy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applied_candidacy` (
  `id` int NOT NULL AUTO_INCREMENT,
  `students_id` varchar(255) DEFAULT NULL,
  `position_id` varchar(45) DEFAULT NULL,
  `school_year_and_semester_id` varchar(45) DEFAULT NULL,
  `is_regular_student` varchar(255) DEFAULT NULL,
  `grade_attachment` varchar(255) DEFAULT NULL,
  `partylist_id` varchar(45) DEFAULT NULL,
  `remarks` longtext,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applied_candidacy`
--

LOCK TABLES `applied_candidacy` WRITE;
/*!40000 ALTER TABLE `applied_candidacy` DISABLE KEYS */;
INSERT INTO `applied_candidacy` VALUES (1,'1','1','2','1','grade_attachments/toB3NrfhI74eons8y6h4knWro7bOZ8GMsPfQuMEI.pdf','1',NULL,'pending','2025-09-15 11:49:07','2025-09-15 11:49:07',NULL);
/*!40000 ALTER TABLE `applied_candidacy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1757941161),('356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1757941161;',1757941161),('5c785c036466adea360111aa28563bfd556b5fba','i:2;',1757936992),('5c785c036466adea360111aa28563bfd556b5fba:timer','i:1757936992;',1757936992);
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
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (1,'BSIT','INFORMATION TECH','active','2025-09-04 06:40:17','2025-09-04 06:40:17',NULL);
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `department` (
  `id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (1,'cscscds','sccssd','active','2025-09-04 06:22:27','2025-09-04 06:39:42','2025-09-04 06:39:42'),(2,'DEPARTMENT TECHNOLOGY','BOBO','active','2025-09-04 06:39:48','2025-09-04 06:40:44',NULL),(3,'DEPARTMENT AGRICULTURE','TANGA','active','2025-09-04 06:41:54','2025-09-04 06:41:54',NULL);
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_de_abanse`
--

DROP TABLE IF EXISTS `meeting_de_abanse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meeting_de_abanse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `students_id` varchar(45) DEFAULT NULL,
  `meeting_de_abanse_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_de_abanse`
--

LOCK TABLES `meeting_de_abanse` WRITE;
/*!40000 ALTER TABLE `meeting_de_abanse` DISABLE KEYS */;
INSERT INTO `meeting_de_abanse` VALUES (1,'1','vfdvdfvdfvdf','vdfvdfvdfvfd','2025-09-06 20:39:00','2025-09-06 21:39:00','active','2025-09-06 03:39:47','2025-09-06 03:39:47',NULL);
/*!40000 ALTER TABLE `meeting_de_abanse` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `signatory_id` bigint DEFAULT NULL,
  `release_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `message` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documentable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documentable_id` bigint DEFAULT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifiable_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `view_data` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_index` (`user_id`),
  KEY `notifications_signatory_id_index` (`signatory_id`),
  KEY `notifications_status_index` (`status`),
  KEY `notifications_documentable_type_documentable_id_index` (`documentable_type`,`documentable_id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  KEY `notifications_read_at_index` (`read_at`),
  KEY `notifications_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,'App\\Notifications\\DocumentNotification',1,1,NULL,'approved','You have been assigned as a signatory for candidacy application #1 by Maite Garner','Candidacy Signatory Assignment','bell','blue','/candidacy-management',NULL,'App\\Models\\applied_candidacy',1,'App\\Models\\User','1',NULL,NULL,'2025-09-08 02:28:17','2025-09-08 02:27:53','2025-09-08 02:28:17'),(2,'App\\Notifications\\DocumentNotification',1,NULL,NULL,'approved','Your candidacy application has been approved.','Candidacy Application Update','check-circle','green','/candidacy-management',NULL,'App\\Models\\applied_candidacy',1,'App\\Models\\students','1',NULL,NULL,NULL,'2025-09-08 02:28:17','2025-09-08 02:28:17'),(3,'App\\Notifications\\DocumentNotification',1,1,NULL,'pending','You have been assigned as a signatory for candidacy application #1 by Maite Garner','Candidacy Signatory Assignment','bell','blue','/candidacy-management',NULL,'App\\Models\\applied_candidacy',1,'App\\Models\\User','1',NULL,NULL,NULL,'2025-09-15 11:23:41','2025-09-15 11:23:41'),(4,'App\\Notifications\\DocumentNotification',1,1,NULL,'pending','You have been assigned as a signatory for candidacy application #1 by Maite Garner','Candidacy Signatory Assignment','bell','blue','/candidacy-management',NULL,'App\\Models\\applied_candidacy',1,'App\\Models\\User','1',NULL,NULL,NULL,'2025-09-15 11:49:07','2025-09-15 11:49:07');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otp`
--

DROP TABLE IF EXISTS `otp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `otp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email_from_id` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `otp_number` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otp`
--

LOCK TABLES `otp` WRITE;
/*!40000 ALTER TABLE `otp` DISABLE KEYS */;
INSERT INTO `otp` VALUES (1,'students','dennisbongaitan18@gmail.com','837964','used','2025-09-04 08:08:59','2025-09-04 07:58:59','2025-09-04 09:45:16','2025-09-04 09:45:16'),(2,'students','dennisbongaitan18@gmail.com','634458','used','2025-09-04 09:55:16','2025-09-04 09:45:16','2025-09-04 10:35:03','2025-09-04 10:35:03'),(3,'students','dennisbongaitan18@gmail.com','459755','used','2025-09-04 10:45:03','2025-09-04 10:35:03','2025-09-08 00:57:51','2025-09-08 00:57:51'),(4,'students','dennisbongaitan18@gmail.com','271060','pending','2025-09-08 01:07:51','2025-09-08 00:57:51','2025-09-08 00:58:09','2025-09-08 00:58:09'),(5,'students','dennisbongaitan18@gmail.com','930058','pending','2025-09-08 01:08:09','2025-09-08 00:58:09','2025-09-08 01:04:59','2025-09-08 01:04:59'),(6,'students','dennisbongaitan18@gmail.com','637739','pending','2025-09-08 01:14:59','2025-09-08 01:04:59','2025-09-08 01:15:25','2025-09-08 01:15:25'),(7,'students','dennisbongaitan18@gmail.com','730642','used','2025-09-08 01:25:25','2025-09-08 01:15:25','2025-09-08 01:21:58','2025-09-08 01:21:58'),(8,'students','dennisbongaitan18@gmail.com','973213','used','2025-09-08 01:31:58','2025-09-08 01:21:58','2025-09-08 05:37:12','2025-09-08 05:37:12'),(9,'students','dennisbongaitan18@gmail.com','316259','pending','2025-09-08 05:47:12','2025-09-08 05:37:12','2025-09-08 05:37:12',NULL);
/*!40000 ALTER TABLE `otp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partylist`
--

DROP TABLE IF EXISTS `partylist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partylist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partylist_name` varchar(255) DEFAULT NULL,
  `description` longtext,
  `partylist_image` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partylist`
--

LOCK TABLES `partylist` WRITE;
/*!40000 ALTER TABLE `partylist` DISABLE KEYS */;
INSERT INTO `partylist` VALUES (1,'aaa','AAAA','partylist_images/partylist_1757941103_9OgJ6OPCdH.png','active','2025-09-15 11:45:18','2025-09-15 12:58:23',NULL);
/*!40000 ALTER TABLE `partylist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position`
--

DROP TABLE IF EXISTS `position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `position` (
  `id` int NOT NULL AUTO_INCREMENT,
  `position_name` varchar(255) DEFAULT NULL,
  `allowed_number_to_vote` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position`
--

LOCK TABLES `position` WRITE;
/*!40000 ALTER TABLE `position` DISABLE KEYS */;
INSERT INTO `position` VALUES (1,'SSS','2','active','2025-09-04 09:43:20','2025-09-04 09:43:20',NULL),(2,'s s s','4','active','2025-09-08 02:57:58','2025-09-08 02:58:03','2025-09-08 02:58:03');
/*!40000 ALTER TABLE `position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registration_request`
--

DROP TABLE IF EXISTS `registration_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registration_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `students_id` varchar(45) DEFAULT NULL,
  `remarks` longtext,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registration_request`
--

LOCK TABLES `registration_request` WRITE;
/*!40000 ALTER TABLE `registration_request` DISABLE KEYS */;
INSERT INTO `registration_request` VALUES (1,'1','Student registration has been approved by admin.','approved','2025-09-04 09:26:57','2025-09-04 09:26:57',NULL),(2,'4','Student registration has been approved by admin.','approved','2025-09-06 19:45:47','2025-09-06 19:45:47',NULL),(3,'3','Student registration has been approved by admin.','approved','2025-09-06 19:45:49','2025-09-06 19:45:49',NULL);
/*!40000 ALTER TABLE `registration_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_campaign`
--

DROP TABLE IF EXISTS `room_campaign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_campaign` (
  `id` int NOT NULL AUTO_INCREMENT,
  `students_id` varchar(255) DEFAULT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `description` longtext,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_campaign`
--

LOCK TABLES `room_campaign` WRITE;
/*!40000 ALTER TABLE `room_campaign` DISABLE KEYS */;
INSERT INTO `room_campaign` VALUES (1,'1','csdcsdcsd','zzz','2025-09-06 14:39:00','2025-09-06 15:39:00','active','2025-09-05 21:39:58','2025-09-06 03:56:45',NULL);
/*!40000 ALTER TABLE `room_campaign` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school_year_and_semester`
--

DROP TABLE IF EXISTS `school_year_and_semester`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `school_year_and_semester` (
  `id` int NOT NULL AUTO_INCREMENT,
  `school_year` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school_year_and_semester`
--

LOCK TABLES `school_year_and_semester` WRITE;
/*!40000 ALTER TABLE `school_year_and_semester` DISABLE KEYS */;
INSERT INTO `school_year_and_semester` VALUES (1,'2024-2025','1st Semester','inactive','2025-09-04 06:53:34','2025-09-04 06:53:44',NULL),(2,'2024-2025','2nd Semester','active','2025-09-04 06:53:44','2025-09-04 06:53:44',NULL);
/*!40000 ALTER TABLE `school_year_and_semester` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('40p8dRIg4fke4ErKcjhOuxEEoHgbAnWlvED5FHR1',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT1h0Rm9VaU4zNzRKNWRIVktPSzZYZTZicVlTNnVtdnhPaGZQRDI0UCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjE6Imh0dHBzOi8vd2ViLWJhc2VkLXZvdGluZy1zeXN0ZW0udGVzdC9hcGkvbm90aWZpY2F0aW9ucy91bnJlYWQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758120559);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_signatory`
--

DROP TABLE IF EXISTS `set_signatory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `set_signatory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `users_id` varchar(45) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL,
  `academic_suffix` varchar(45) DEFAULT NULL,
  `signatory_action_id` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_signatory`
--

LOCK TABLES `set_signatory` WRITE;
/*!40000 ALTER TABLE `set_signatory` DISABLE KEYS */;
INSERT INTO `set_signatory` VALUES (1,'1','sds','wew','3','active','2025-09-04 10:09:55','2025-09-04 10:09:55',NULL);
/*!40000 ALTER TABLE `set_signatory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `signatory_action`
--

DROP TABLE IF EXISTS `signatory_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signatory_action` (
  `id` int NOT NULL AUTO_INCREMENT,
  `action_name` varchar(255) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `signatory_action`
--

LOCK TABLES `signatory_action` WRITE;
/*!40000 ALTER TABLE `signatory_action` DISABLE KEYS */;
INSERT INTO `signatory_action` VALUES (1,'Prepared by','active','2025-09-04 17:57:13',NULL,NULL),(2,'Approved by','active','2025-09-04 17:57:13',NULL,NULL),(3,'Noted by','active','2025-09-04 17:57:13',NULL,NULL),(4,'Checked by','active','2025-09-06 00:38:01',NULL,NULL),(5,'Recommended by','active','2025-09-06 00:38:01',NULL,NULL),(6,'Reviewed by','active','2025-09-06 00:38:01',NULL,NULL),(7,'Verified by','active','2025-09-06 00:38:01',NULL,NULL),(8,'Endorsed by','active','2025-09-06 00:38:01',NULL,NULL);
/*!40000 ALTER TABLE `signatory_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` varchar(255) DEFAULT NULL,
  `course_id` varchar(255) DEFAULT NULL,
  `department_id` varchar(255) DEFAULT NULL,
  `school_year_and_semester_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `student_id_image` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `marital_status` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'232324-2344','1','3','2','Maite','Justine Evans','Garner','','Male','1982-07-18','43','Ipsum aut magnam cor','profile_pictures/OE2j7GP9mirqWDD07diH9nZLIUVr6iOUOgpqJhSt.jpg','student_id_images/fuQaGkyseiKeaIYx7hm0w2Ml0VWdRCFZAWff8Hmo.jpg','dennisbongaitan18@gmail.com','$2y$12$UAhgKL8L2kWfcFmHpIi/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq','Single','active','2025-09-04 07:46:19','2025-09-08 01:46:30',NULL),(2,'34','1','2','2','DENNIS','vbgf','BONGAITAN','','male','1998-03-07','27','Davao City','profile_pictures/909m96nYOunmEW4NLhRLdqmV4VExEDMmdZRwE5tv.jpg','student_id_images/T2yUDCz8oXWqvzJSuYQaUwP0IzstTxL85gbcdUjw.jpg','sdcscdscd@gmail.com','$2y$12$SXlTKZQMuPkK9IcTJrIbxu4beP5JO/JgMeNr2PPLP2ORopHGaDSPm',NULL,'active','2025-09-06 16:40:35','2025-09-06 16:40:35',NULL),(3,'232324-2343','1','3','2','DENNIS',NULL,'BOoNGAITAN',NULL,'Male','1998-03-07','27','Davao City','student_images/1757216641_profile_51bb03cf-c2ce-469f-8ad2-b010091ecae1.jpg','student_images/1757216641_id_51bb03cf-c2ce-469f-8ad2-b010091ecae1.jpg','a@gmail.com','$2y$12$w07Jlsa.X49VpZuYRuixbOT2/vgt30Ny4RKrpm5csTPGy4lvmz7ju',NULL,'active','2025-09-06 19:44:01','2025-09-06 19:45:49',NULL),(4,'232324-2346','1','3','2','DENNnIS','as','BONnGAITAN',NULL,'Male','1997-03-07','28','Davao City','student_images/1757216698_profile_51bb03cf-c2ce-469f-8ad2-b010091ecae1.jpg','student_images/1757216698_id_51bb03cf-c2ce-469f-8ad2-b010091ecae1.jpg','aa@gmail.com','$2y$12$4JTLpl9kKmytdBgkoo3ckeseBv3cwuKPSIyTKHBCdUsPuEMd/fPfu',NULL,'active','2025-09-06 19:44:58','2025-09-06 19:45:47',NULL);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `module_id` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES (1,'sidebar_logo','system_settings/1757310064_958f3106-933e-45f6-94d4-16494684712d-modified.png','image','1','rtgvvfgvfvd','active','2025-09-08 04:59:11','2025-09-08 05:41:04',NULL),(2,'sidebar_text_logo','Voting System','text','2','fdcvdf','active','2025-09-08 04:59:11','2025-09-08 05:24:55',NULL),(3,'login_center_logo','system_settings/1757310054_958f3106-933e-45f6-94d4-16494684712d-modified.png','image','3','bfgdbfgvbfg','active','2025-09-08 04:59:11','2025-09-08 05:40:54',NULL),(4,'login_top_text','Voting System','text','4','vfgvfdvdf','active','2025-09-08 05:32:22','2025-09-08 05:33:08',NULL),(5,'login_center_text','Voting Management System','text','5','vdfvfvdf','active','2025-09-08 04:59:11','2025-09-08 05:34:23',NULL),(6,'login_top_logo','system_settings/1757310198_958f3106-933e-45f6-94d4-16494684712d-modified.png','image','6','','active','2025-09-08 04:59:11','2025-09-08 05:43:18',NULL);
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_online` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','test@example.com','2025-09-04 05:51:52','$2y$12$UAhgKL8L2kWfcFmHpIi/XuEZQqlT.bmMdpiW1btkmQEf13Zn1xrUq','40FTWmNcDKugRYos4VOG23lhtGUwynk7A8A3y1JUqgBczIGktkQpOYj6h4cY','stsg','profile_picture/MAO7MxsiV3iwvmskyI39gP1PvR8uYhOOzAOsILXH.jpg','active',NULL,'2025-09-04 05:51:52','2025-09-06 05:53:24'),(3,'csdcscsd','csdcscsd@gmail.com',NULL,'$2y$12$GL3Ts.GnGqbjBRIXk.oJHu4FsswMdVGKzzszagvDD67XX6kEK1cg2',NULL,'stsg','profile_picture/3MPHlHPwKEOWO1XS4krMwziVwrLANpIfkFop94rt.jpg','active',NULL,'2025-09-06 07:04:03','2025-09-06 07:04:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voting_exclusive`
--

DROP TABLE IF EXISTS `voting_exclusive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voting_exclusive` (
  `id` int NOT NULL AUTO_INCREMENT,
  `department_id` varchar(255) DEFAULT NULL,
  `course_id` varchar(255) DEFAULT NULL,
  `school_year_id` varchar(45) DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voting_exclusive`
--

LOCK TABLES `voting_exclusive` WRITE;
/*!40000 ALTER TABLE `voting_exclusive` DISABLE KEYS */;
INSERT INTO `voting_exclusive` VALUES (1,'2','1','2','2025-09-07 11:46:00','2025-09-07 12:47:00','completed','2025-09-07 02:47:07','2025-09-08 02:51:27',NULL);
/*!40000 ALTER TABLE `voting_exclusive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voting_vote_count`
--

DROP TABLE IF EXISTS `voting_vote_count`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voting_vote_count` (
  `id` int NOT NULL AUTO_INCREMENT,
  `voting_exclusive_id` varchar(45) DEFAULT NULL,
  `students_id` varchar(45) DEFAULT NULL,
  `number_of_vote` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voting_vote_count`
--

LOCK TABLES `voting_vote_count` WRITE;
/*!40000 ALTER TABLE `voting_vote_count` DISABLE KEYS */;
INSERT INTO `voting_vote_count` VALUES (1,'1','1','0','win','2025-09-08 02:28:58','2025-09-08 02:51:27',NULL);
/*!40000 ALTER TABLE `voting_vote_count` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voting_voted_by`
--

DROP TABLE IF EXISTS `voting_voted_by`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voting_voted_by` (
  `id` int NOT NULL AUTO_INCREMENT,
  `voting_vote_count_id` varchar(45) DEFAULT NULL,
  `students_id` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voting_voted_by`
--

LOCK TABLES `voting_voted_by` WRITE;
/*!40000 ALTER TABLE `voting_voted_by` DISABLE KEYS */;
/*!40000 ALTER TABLE `voting_voted_by` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-17 23:19:25
