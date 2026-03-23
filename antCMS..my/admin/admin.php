<?php

// echo (PATH_CORE);

$dird          = dirname( $_SERVER['PHP_SELF'] ); ## "/admin"
$sourcessrandd = ['//' => '/', '\\' => '/'];
$dird          = strtr( $dird, $sourcessrandd );
$dirf          = ( $dird != '/' ) ? '/' : '';

$url = 'http://' . $_SERVER['HTTP_HOST'] . $dird . $dirf;
define( 'CONF_FULL_SHOP_URL', trim( $url ) ); // "http://antcms.os/admin/"
define( 'PATH_DELIMITER', ( isset( $_SERVER['WINDIR'] ) || isset( $_SERVER['windir'] ) ) ? ';' : ':' );
// Set the default timezone
// date_default_timezone_set('America/New_York');
date_default_timezone_set( 'Europe/Minsk' );

// // Set the error reporting level
// error_reporting(E_ALL);

// Set the default character encoding
if ( function_exists( 'mb_internal_encoding' ) === true ) {
	mb_internal_encoding( 'UTF-8' );
}

// Set the default locale
if ( function_exists( 'setlocale' ) === true ) {
	// setlocale(LC_ALL, 'en_US.UTF-8');
	setlocale( LC_ALL, 'ru_Belarus.UTF-8' );
}

# сбрасываем время сессии
session_cache_expire();
#########
#########
#########
#########
#########
require_once 'core/' . 'const.php'; // управляющие и служебные константы
require_once PATH_CORE . 'bootstrap.php';

// include_once PATH_CORE . 'authentication.php';

// if ($LOG_OK === false)
// {
//     die(ERROR_FORBIDDEN_LOGIN);
// }
// $const = get_defined_constants();
// dd($const);

###

$_POST   = stripslashes_deep( $_POST );
$_GET    = stripslashes_deep( $_GET );
$_COOKIE = stripslashes_deep( $_COOKIE );

//define start smarty template
$smarty->assign( 'admin_main_content_template', 'start.tpl.html' );

### define department and subdepartment
include_once PATH_CORE . 'departments.php';

include_once PATH_CORE . 'admin_end.php';
