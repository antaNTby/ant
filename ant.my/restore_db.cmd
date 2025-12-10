@echo off

set MYSQL_USER=root
set MYSQL_PASSWORD=
set MYSQL_HOST=mysql-8.4
set MYSQL_PORT=3306
set BASE_BACKUP_DIR=c:\dev\.local_databases

if "%~1"=="" (
    echo Error:   Backup folder name parameter is required
    echo Usage:   %0 [backup_folder_name]
    echo Example: %0 20250622_1729
    goto end
)

set BACKUP_FOLDER=%~1
set FULL_BACKUP_PATH=%BASE_BACKUP_DIR%\%BACKUP_FOLDER%

if not exist "%FULL_BACKUP_PATH%" (
    echo Backup directory does not exist: %FULL_BACKUP_PATH%
    goto end
)

echo Restoring from %FULL_BACKUP_PATH%

if "%MYSQL_PASSWORD%"=="" (
    set AUTH_OPTIONS=-u%MYSQL_USER%
) else (
    set AUTH_OPTIONS=-u%MYSQL_USER% -p%MYSQL_PASSWORD%
)

for %%f in ("%FULL_BACKUP_PATH%\*.sql") do (
    for /f "tokens=2* delims=_" %%a in ("%%~nf") do (
        set DB_NAME=%%b
        echo Restoring database: %%b
        
        mysql %AUTH_OPTIONS% -h%MYSQL_HOST% -P%MYSQL_PORT% -e "CREATE DATABASE IF NOT EXISTS %%b"
        
        mysql %AUTH_OPTIONS% -h%MYSQL_HOST% -P%MYSQL_PORT% %%b < "%%f"
        
        if errorlevel 1 (
            echo [ERROR]             Failed to restore %%b
        ) else (
            echo [SUCCESS]           Restored %%b
        )
    )
)

echo Restoration complete from %FULL_BACKUP_PATH%
:end