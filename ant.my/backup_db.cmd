@echo off

:: === ЗАПУСКАТЬ через PowerShell  ===
:: PS C:\WINDOWS\system32> cd "C:\git\ant\ant.my"
:: PS C:\git\ant\ant.my> .\backup_db.cmd


:: === ЗАПУСКАТЬ через CMD  ===
:: C:\WINDOWS\system32> cd "C:\git\ant\ant.my"
:: C:\git\ant\ant.my> backup_db.cmd

rem set BASE_BACKUP_DIR=c:\git\.local_databases
rem set MYSQL_USER=root
rem set MYSQL_PASSWORD=
rem set MYSQL_HOST=mysql-8.4
rem set MYSQL_PORT=3306
rem set TIMESTAMP=%date:~-4,4%%date:~-7,2%%date:~0,2%_%time:~0,2%%time:~3,2%
rem set TIMESTAMP=%TIMESTAMP: =0%
rem set BACKUP_DIR=%BASE_BACKUP_DIR%\%TIMESTAMP%


set BASE_BACKUP_DIR=c:\git\.local_databases
set MYSQL_BIN=C:\OSPanel\modules\MySQL-8.4\bin
set MYSQL_USER=antaNT64
set MYSQL_PASSWORD=root
set MYSQL_HOST=mysql-8.4
set MYSQL_PORT=3306

set TIMESTAMP=%date%_%time:~0,2%%time:~3,2%
set TIMESTAMP=%TIMESTAMP: =0%
:: === УНИВЕРСАЛЬНЫЙ TIMESTAMP (через PowerShell) ===
for /f %%a in ('powershell -NoProfile -Command "Get-Date -Format yyyy-MM-dd_HHmmss"') do set TIMESTAMP=%%a
set BACKUP_DIR=%BASE_BACKUP_DIR%\%TIMESTAMP%






echo BACKUP TO %BACKUP_DIR%
if not exist "%BASE_BACKUP_DIR%" mkdir "%BASE_BACKUP_DIR%"
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

set EXCLUDE_DBS=information_schema performance_schema mysql sys

if "%MYSQL_PASSWORD%"=="" (
    set AUTH_OPTIONS=-u%MYSQL_USER%
) else (
    set AUTH_OPTIONS=-u%MYSQL_USER% -p%MYSQL_PASSWORD%
)

for /f "tokens=1 delims= " %%a in ('mysql %AUTH_OPTIONS% -h%MYSQL_HOST% -P%MYSQL_PORT% -e "show databases" -s --skip-column-names') do (
    echo %EXCLUDE_DBS% | find "%%a" >nul || (
        echo DUMPING DATABASE: %%a
        mysqldump %AUTH_OPTIONS% -h%MYSQL_HOST% -P%MYSQL_PORT% --routines --triggers --events --single-transaction %%a > "%BACKUP_DIR%\%TIMESTAMP%_%%a.sql"
    )
)

echo ALL DATABASES HAVE BEEN BACKED UP TO %BACKUP_DIR%



rem 1. Добавить MySQL в PATH (самый простой)
rem В системных переменных Windows:
rem Открой Панель управления → Система → Дополнительные параметры → Переменные среды.
rem В переменной Path добавь строку:
rem Code
rem C:\OSPanel\modules\MySQL-8.4\bin