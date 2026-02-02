@echo off
setlocal enabledelayedexpansion

:: === НАСТРОЙКИ ===
set BASE_BACKUP_DIR=C:\git\.local_databases
set MYSQL_BIN=C:\OSPanel\modules\MySQL-8.4\bin
set MYSQL_USER=antaNT64
set MYSQL_PASSWORD=root
set MYSQL_HOST=mysql-8.4
set MYSQL_PORT=3306

:: === УНИВЕРСАЛЬНЫЙ TIMESTAMP (через PowerShell) ===
for /f %%a in ('powershell -NoProfile -Command "Get-Date -Format yyyy-MM-dd_HHmmss"') do set TIMESTAMP=%%a

set BACKUP_DIR=%BASE_BACKUP_DIR%\%TIMESTAMP%

:: === СОЗДАНИЕ ПАПОК ===
echo BACKUP TO %BACKUP_DIR%
if not exist "%BASE_BACKUP_DIR%" mkdir "%BASE_BACKUP_DIR%"
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

:: === СПИСОК ИСКЛЮЧАЕМЫХ БАЗ ===
set EXCLUDE_DBS=information_schema performance_schema mysql sys

:: === АВТОРИЗАЦИЯ ===
if "%MYSQL_PASSWORD%"=="" (
    set AUTH_OPTIONS=-u%MYSQL_USER%
) else (
    set AUTH_OPTIONS=-u%MYSQL_USER% -p%MYSQL_PASSWORD%
)

:: === ЦИКЛ ПО БАЗАМ ===
for /f %%a in ('call "%MYSQL_BIN%\mysql.exe" %AUTH_OPTIONS% -h%MYSQL_HOST% -P%MYSQL_PORT% -s -N -e "SHOW DATABASES;"') do (
    echo %EXCLUDE_DBS% | find "%%a" >nul
    if errorlevel 1 (
        echo DUMPING DATABASE: %%a
        call "%MYSQL_BIN%\mysqldump.exe" %AUTH_OPTIONS% -h%MYSQL_HOST% -P%MYSQL_PORT% --routines --triggers --events --single-transaction %%a > "%BACKUP_DIR%\%TIMESTAMP%_%%a.sql"
    )
)

echo ALL DATABASES HAVE BEEN BACKED UP TO %BACKUP_DIR%

endlocal


rem 🔑 Особенности
rem Не нужен PATH — всё работает напрямую через "%MYSQL_BIN%\mysql.exe" и "%MYSQL_BIN%\mysqldump.exe".
rem call внутри for /f — гарантирует правильную обработку кавычек и параметров.
rem TIMESTAMP через PowerShell — формат YYYY-MM-DD_HHMMSS, одинаковый на любой локали.
rem Исключения — системные базы (information_schema, performance_schema, mysql, sys) не дампятся.
rem Файлы — каждый дамп сохраняется в отдельный .sql с именем TIMESTAMP_DBNAME.sql.
rem Запуск
rem В PowerShell:
rem powershell
rem cd "C:\git\ant\ant.my"
rem .\backup_db.cmd
rem В CMD:
rem cmd
rem cd C:\git\ant\ant.my
rem backup_db.cmd
rem Теперь скрипт полностью автономный и готов к использованию на любой машине с MySQL‑bin в указанной папке.