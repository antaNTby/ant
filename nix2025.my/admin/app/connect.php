<?php
// connect.php

const DBMS      = 'mysqli'; //   {$smarty.const.DBMS}          database host   # Database Driver Type (optional)
const DB_DRIVER = 'mysqli'; //   {$smarty.const.DBMS}          database host   # Database Driver Type (optional)
// const DB_NAME           = 'db_antCMS';          //   {$smarty.const.DB_NAME}       password
// const DB_PRFX           = 'ant_';               # Database Prefix (optional)
const DB_PRFX           = 'UTF_';               # Database Prefix (optional)
const DB_CHARSET        = 'utf8mb4';            # Database Charset (optional)
const DB_HEADERSCHARSET = 'utf8';               # Database Charset (optional)
const DB_COLLATION      = 'utf8mb4_unicode_ci'; # Database Charset Collation (optional)
// const DB_CACHEDIR       = '../admin/database/database_cache';
const DB_PORT = 3306;

/*
root

GRANT ALL PRIVILEGES ON *.* TO 'antaNT'@'localhost' WITH GRANT OPTION; FLUSH PRIVILEGES;

*/

// const DB_NAME = 'nixby_UTF8'; //   {$smarty.const.DB_NAME}       password
// const DB_HOST   = 'MySQL-5.7'; //   {$smarty.const.DB_HOST}       username # hostname:port (for Port Usage. Example: 127.0.0.1:1010)
// const DB_USER   = 'root';      //   {$smarty.const.DB_USER}       database name # Database Name (required)
// const DB_PASS   = '';          //   {$smarty.const.DB_PASS}       database prefix
// const DB_NAME = 'nixby_UTF8'; //   {$smarty.const.DB_NAME}       password
const DB_NAME = 'db_nix2025'; //   {$smarty.const.DB_NAME}       password
const DB_HOST = 'MySQL-8.4';  //   {$smarty.const.DB_HOST}       username # hostname:port (for Port Usage. Example: 127.0.0.1:1010)
const DB_USER = 'antaNT64';   //   {$smarty.const.DB_USER}       database name # Database Name (required)
const DB_PASS = 'root';       //   {$smarty.const.DB_PASS}       database prefix

#### const DB_HOST = 'MySQL-5.7'; //   {$smarty.const.DB_HOST}       username # hostname:port (for Port Usage. Example: 127.0.0.1:1010)
#### const DB_USER = 'root';      //   {$smarty.const.DB_USER}       database name # Database Name (required)
#### const DB_PASS = '';          //   {$smarty.const.DB_PASS}       database prefix

// const DB_HOST   = 'MySQL-8.4'; //   {$smarty.const.DB_HOST}       username # hostname:port (for Port Usage. Example: 127.0.0.1:1010)
// const DB_USER   = 'antaNT';      //   {$smarty.const.DB_USER}       database name # Database Name (required)
// const DB_PASS   = 'root';          //   {$smarty.const.DB_PASS}       database prefix

return [
    'sqlite_database_path' => __ADMIN__ . DIRECTORY_SEPARATOR . 'nixDB.sqlite',
    'database'             => [
        'host'     => DB_HOST,
        'dbname'   => DB_NAME,
        'user'     => DB_USER,
        'password' => DB_PASS,
    ],

];
