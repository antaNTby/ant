@echo off

set BASE_BACKUP_DIR=c:\dev\.local_databases
set MYSQL_USER=root
set MYSQL_PASSWORD=
set MYSQL_HOST=mysql-8.4
set MYSQL_PORT=3306

set TIMESTAMP=%date:~-4,4%%date:~-7,2%%date:~0,2%_%time:~0,2%%time:~3,2%
set TIMESTAMP=%TIMESTAMP: =0%
set BACKUP_DIR=%BASE_BACKUP_DIR%\%TIMESTAMP%

echo Backup to %BACKUP_DIR%
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
        echo Dumping database: %%a
        mysqldump %AUTH_OPTIONS% -h%MYSQL_HOST% -P%MYSQL_PORT% --routines --triggers --events --single-transaction %%a > "%BACKUP_DIR%\%TIMESTAMP%_%%a.sql"
    )
)

echo All databases have been backed up to %BACKUP_DIR%