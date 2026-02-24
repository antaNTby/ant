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
  `role` ENUM('admin', 'user', 'moderator') NOT NULL DEFAULT 'user',
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
ALTER TABLE `user_tokens`
ADD COLUMN `last_used_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
