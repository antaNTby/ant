@echo off
setlocal enabledelayedexpansion

:: === НАСТРОЙКИ ===
set MYSQL_BIN=C:\OSPanel\modules\MySQL-8.4\bin
set MYSQL_USER=antaNT64
set MYSQL_PASSWORD=root
set MYSQL_HOST=mysql-8.4
set MYSQL_PORT=3306
set BASE_BACKUP_DIR=C:\git\.local_databases

:: === ПОИСК ПОСЛЕДНЕГО БЭКАПА ===
for /f "delims=" %%d in ('dir /b /ad /o-d "%BASE_BACKUP_DIR%"') do (
    set LAST_BACKUP=%%d
    goto found
)

:found
if not defined LAST_BACKUP (
    echo [ERROR] No backup folders found in %BASE_BACKUP_DIR%
    goto end
)

set FULL_BACKUP_PATH=%BASE_BACKUP_DIR%\%LAST_BACKUP%
echo Restoring from latest backup: %FULL_BACKUP_PATH%

:: === АВТОРИЗАЦИЯ ===
if "%MYSQL_PASSWORD%"=="" (
    set AUTH_OPTIONS=-u%MYSQL_USER%
) else (
    set AUTH_OPTIONS=-u%MYSQL_USER% -p%MYSQL_PASSWORD%
)

:: === ЦИКЛ ПО ФАЙЛАМ ===
for %%f in ("%FULL_BACKUP_PATH%\*.sql") do (
    for /f "tokens=2* delims=_" %%a in ("%%~nf") do (
        set DB_NAME=%%b
        echo Restoring database: %%b

        call "%MYSQL_BIN%\mysql.exe" %AUTH_OPTIONS% -h%MYSQL_HOST% -P%MYSQL_PORT% -e "CREATE DATABASE IF NOT EXISTS %%b"
        call "%MYSQL_BIN%\mysql.exe" %AUTH_OPTIONS% -h%MYSQL_HOST% -P%MYSQL_PORT% %%b < "%%f"

        if errorlevel 1 (
            echo [ERROR]   Failed to restore %%b
        ) else (
            echo [SUCCESS] Restored %%b
        )
    )
)

echo Restoration complete from %FULL_BACKUP_PATH%
:end
endlocal
