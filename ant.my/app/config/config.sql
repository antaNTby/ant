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