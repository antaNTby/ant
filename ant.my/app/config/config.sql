-- создаём пользователя
CREATE USER 'antaNT64'@'%' IDENTIFIED BY 'root';

-- выдаём полный доступ ко всем базам
GRANT ALL PRIVILEGES ON *.* TO 'antaNT64'@'%' WITH GRANT OPTION;

-- применяем изменения
FLUSH PRIVILEGES;

-- ОПЦИОНАЛЬНО

-- создаём роль DBA
CREATE ROLE 'DBA';

-- назначаем ей все права
GRANT ALL PRIVILEGES ON *.* TO 'DBA' WITH GRANT OPTION;

-- выдаём роль пользователю
GRANT 'DBA' TO 'antaNT64'@'%';

-- делаем её ролью по умолчанию
SET DEFAULT ROLE 'DBA' TO 'antaNT64'@'%';

-- SET PASSWORD FOR 'antaNT64'@'localhost' = PASSWORD('root');
SET PASSWORD FOR 'antaNT64'@'%' = 'root';


-- https://adminer.db/adminer-5.4.1.php?server=MySQL-8.4&username=antaNT64&db=nixby_UTF8

-- для MySql-5.7
-- создаём пользователя
CREATE USER 'antaNT64'@'%' IDENTIFIED BY 'root';

-- выдаём полный доступ ко всем базам
GRANT ALL PRIVILEGES ON *.* TO 'antaNT64'@'%' WITH GRANT OPTION;

-- применяем изменения
FLUSH PRIVILEGES;

-- изменить пароль (вариант для MySQL 5.7)
SET PASSWORD FOR 'antaNT64'@'%' = PASSWORD('root');



-- Вот SQL-запрос для создания таблицы users. Я добавил поле role с типом ENUM (перечисление) и значением по умолчанию user, чтобы логика в PHP работала корректно.
-- 1. Создание таблицы
-- sql
CREATE DATABASE newdb;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('administrator', 'user', 'guest') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Добавление первого администратора
-- Чтобы войти под своим логином, нужно сначала создать хеш пароля через PHP, так как password_verify не поймет обычный текст.
-- Запустите этот PHP-код один раз, чтобы получить строку для вставки:
-- php
-- echo password_hash('12345', PASSWORD_DEFAULT);
-- // Скопируйте результат (например:$2y$12$CehthLX3BoZ/53RS/UO/j.s.Y1LOuTC2GXPErJTIcCz1kqqc0TcCC)
--
-- Затем вставьте его в SQL:
-- sql
-- INSERT INTO `users` (`username`, `password_hash`, `role`)
-- VALUES ('admin', 'РЕЗУЛЬТАТ_ХЕШИРОВАНИЯ_ИЗ_PHP', 'admin');

INSERT INTO `users` (`username`, `password_hash`, `role`)
VALUES ('admin', '$2y$12$CehthLX3BoZ/53RS/UO/j.s.Y1LOuTC2GXPErJTIcCz1kqqc0TcCC', 'admin');

-- В текущем запросе CREATE TABLE индекс на username уже создан автоматически, так как вы указали ключевое слово UNIQUE. В MySQL уникальность поля всегда поддерживается с помощью индекса (B-Tree), что обеспечивает мгновенный поиск.
-- Однако, если таблица уже создана без этого ключа, или вы хотите добавить обычный индекс (не уникальный), используйте следующую команду:
-- Если таблица уже есть (добавление индекса):
-- sql
ALTER TABLE `users` ADD INDEX `idx_username` (`username`);

-- Чтобы добавить отслеживание последнего входа, нужно выполнить два шага: обновить структуру таблицы и дополнить логику в PHP.
-- 1. SQL-запрос (обновление таблицы)
-- Если таблица уже создана, добавьте поле last_login с типом DATETIME:
-- sql
ALTER TABLE `users` ADD COLUMN `last_login` DATETIME DEFAULT NULL;


-- Для реализации мониторинга активных сессий добавим колонку last_used_at. Это позволит вам выводить в админке список устройств (браузер + IP)
-- и давать пользователю возможность «выбить» подозрительные входы.
CREATE TABLE `user_tokens` (
    `id`          INT NOT NULL AUTO_INCREMENT, -- Убрали UNSIGNED
    `user_id`     INT NOT NULL,                -- Убрали UNSIGNED (теперь совпадает с users.id)
    `token_hash`  CHAR(64) NOT NULL,
    `user_agent`  VARCHAR(255) DEFAULT NULL,
    `created_ip`  VARCHAR(45) DEFAULT NULL,
    `expires_at`  DATETIME NOT NULL,
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_token_hash` (`token_hash`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_expires_at` (`expires_at`),

    CONSTRAINT `fk_user_tokens_user`
        FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4


ALTER TABLE `user_tokens`
ADD COLUMN `last_used_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `users`
ADD COLUMN `is_active` TINYINT(1) DEFAULT 1 NOT NULL AFTER `password_hash`;

ALTER TABLE users ADD COLUMN email VARCHAR(255) NOT NULL AFTER username;

-- нужно заполнить почты в таблицу а затем :
ALTER TABLE users ADD UNIQUE (email);


ALTER TABLE `user_tokens`
CHANGE `user_agent` `user_agent` text COLLATE 'utf8mb4_0900_ai_ci' NULL AFTER `token_hash`;

ALTER TABLE `users`
CHANGE `role` `role` enum('administrator','user','guest') COLLATE 'utf8mb4_0900_ai_ci' NOT NULL DEFAULT 'user' AFTER `is_active`;









/*ff*/
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_raw` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `role` enum('administrator','user','guest') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@ant.by','$2y$12$JCk.1OCDwDuiaNmfgknaJ.xsVjfCVrUhetvFdfUd9iC7kA3Mo1vO.','',1,'administrator','2026-02-26 18:37:42','2026-03-16 05:18:50'),(2,'user','user@tut.by','$2y$12$5gpBBWESv0fRdrl6NgJGBe6lK3UUWqgQ9M2Z21VfB5lWs6Xn4D5H2','',1,'user','2026-02-26 20:38:59','2026-03-10 22:56:16'),(3,'ban','ban@tut.by','$2y$12$IBmrqt40r.PKSgA1nJxf9.goEX6fnxFrYsu4nA3EZFY7DF8qsTtsK','',0,'user','2026-02-26 20:39:30','2026-02-26 23:39:30'),(48,'ne','ne@tut.by','$2y$12$DO31P./66NYlI2kpSFXIZeynkJrGZpO1UsrVnkRd0M8OTUXj4iz9e','',0,'administrator','2026-02-26 20:52:28','2026-02-26 23:52:28'),(49,'crypt','crypt@gmail.by','$2y$12$WIIVgbYFBowLr.TTbuzYVeqo3o3OWlLQvEJokdJ7RUNfpl2y132gC','',1,'user','2026-02-28 14:26:31','2026-03-05 02:19:18'),(56,'hash','hash@hash.hash','$2y$12$ERDnqX6lKfTCCgQtR4loCeAls3POlL/m/1hs5JPAx9DqBy0zTZn/.','hash',1,'user','2026-03-05 07:44:06','2026-03-05 10:44:06'),(57,'hash1','hash@hash.hashd3','$2y$12$KbaBJWQi/bQBxPik8OfpEerZnzoSeO/PVvtyt0Pkp4vzi4070RBCy','1',1,'user','2026-03-07 12:10:13','2026-03-07 15:10:13'),(58,'dd','dd@ss.dd','$2y$12$M7F8xp0WXdmhD2OkXITvyOP6mKPF/rbwQdxt0wu78plwioIMCrJlu','d',1,'user','2026-03-10 07:42:59','2026-03-10 11:06:32');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


DROP TABLE IF EXISTS `user_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token_hash` char(64) NOT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_ip` varchar(45) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_used_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_token_hash` (`token_hash`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `fk_user_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=433 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tokens`
--

LOCK TABLES `user_tokens` WRITE;
/*!40000 ALTER TABLE `user_tokens` DISABLE KEYS */;
INSERT INTO `user_tokens` VALUES (415,58,'4d8a0bbe84c4c6a804c4faf65f09a01f6996639352d1c049e755c7f484a74a71','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','127.0.0.1','2026-03-12 11:06:32','2026-03-10 08:06:32','2026-03-10 08:06:32'),(422,2,'2703f1617b9dd8c83c1e1c0a6ab6ac9ac315fe8a85f9f8d3cbb5c7c7aa212a2c','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','127.0.0.1','2026-03-12 22:56:16','2026-03-10 19:56:16','2026-03-10 19:56:16'),(430,1,'62ce16ee63d4293ee70f9ca53baa8b5236cb146aca01a5f1433db6e6d03b6f53','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','127.0.0.1','2026-03-13 11:47:20','2026-03-11 08:47:20','2026-03-11 08:47:20'),(431,1,'0efe8e852b0e2426fb8c994782dd03c32c4e2dbb3be83e807c118c25c0c910bf','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0','127.0.0.1','2026-03-14 09:22:44','2026-03-12 06:22:44','2026-03-12 06:22:44'),(432,1,'bb841ad4fb6785e23b047292c3a0264084ac72ebe3d0ae6d1127f1b1c4a68c9a','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0','127.0.0.1','2026-03-18 05:18:50','2026-03-16 02:18:50','2026-03-16 02:18:50');
/*!40000 ALTER TABLE `user_tokens` ENABLE KEYS */;
UNLOCK TABLES;