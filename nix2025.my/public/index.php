<?php
ini_set( 'session.serialize_handler', 'php_serialize' );
#### For issue relatedtocodeigniterprojectupload,
#### go tothebasedirectoryindex . php and add thiscode:

// if ( $_SERVER['SERVER_NAME'] == 'nix2025.new ' ) {
//     define( 'ENVIRONMENT', 'development' );
// } else {
//     define( 'ENVIRONMENT', 'production' );
// }

// if ( defined( 'ENVIRONMENT' ) ) {
//     switch ( ENVIRONMENT ) {
//         case 'development':
//             error_reporting( E_ALL );
//             break;

//         case 'testing':
//         case 'production':
//             error_reporting( 0 );
//             break;

//         default:
//             exit( 'The application environment is not set correctly.' );
//     }
// }

// define( 'ENVIRONMENT', isset( $_SERVER['CI_ENV'] ) ? $_SERVER['CI_ENV'] : 'development' );

define( '__ROOT__', dirname( __DIR__, 1 ) );
define( '__ADMIN__', __ROOT__ . DIRECTORY_SEPARATOR . 'admin' );
define( '__PUBLIC__', __ROOT__ . DIRECTORY_SEPARATOR . 'public' );
define( '__VENDOR__', __ROOT__ . DIRECTORY_SEPARATOR . 'vendor' );

define( '__APP__', __ADMIN__ . DIRECTORY_SEPARATOR . 'app' );
define( '__TPL__', __ADMIN__ . DIRECTORY_SEPARATOR . 'tpl' );

define( '__SUBS__', __APP__ . DIRECTORY_SEPARATOR . 'subs' );
define( '__CLASSES__', __APP__ . DIRECTORY_SEPARATOR . 'classes' );

const SITE_URL = '--== nix2025 ==--';
const LOGO256  = 'logo256.jpg'; //   {$smarty.const.LOGO256}
const LOGO64   = 'logo64.jpg';  //
define( 'SERVER_NAME', $_SERVER['SERVER_NAME'] );

// Set the default timezone
date_default_timezone_set( 'Europe/Minsk' );

// Set the error reporting level
error_reporting( E_ALL );

// Set the default character encoding
if ( function_exists( 'mb_internal_encoding' ) === true ) {
    mb_internal_encoding( 'UTF-8' );
}

// Set the default locale
if ( function_exists( 'setlocale' ) === true ) {
    // setlocale(LC_ALL, 'en_US.UTF-8');
    setlocale( LC_ALL, 'ru_BY.UTF-8' );
}

function debug( $variable )
{
    if ( !isset( $variable ) ) {
        echo( 'undefined' );
    } else {
        echo '<div align="left">';
        echo( _value( $variable ) . '<br>' );
        echo '</div>';
    }
}

function _value( $variable )
{
    if ( !isset( $variable ) ) {
        return 'undefined';
    }

    $res = '';
    if ( is_null( $variable ) ) {
        $res .= 'NULL';
    } elseif ( is_array( $variable ) ) {
        $res .= '<strong style="opacity:0.5">array</strong>';
        $res .= '<ol style="list-style-type: decimal" start=0>';
        foreach ( $variable as $key => $value ) {
            $res .= '<li>';
            $res .= '[ ' . _value( $key ) . ' ]=' . _value( $value );
            $res .= '</li>';
        }
        $res .= '</ol>';
    } elseif ( is_int( $variable ) ) {
        $res .= '<em style="opacity:0.5; color:red">int</em> ';
        $res .= (string) $variable;
    } elseif ( is_bool( $variable ) ) {
        $res .= '<em style="opacity:0.5">bool</em> ';
        if ( $variable ) {
            $res .= '<strong>true</strong>';
        } else {
            $res .= '<strong>false</strong>';
        }
    } elseif ( is_string( $variable ) ) {
        $res .= '<em style="opacity:0.5; color:limegreen">string</em> ';
        $res .= "'" . (string) $variable . "'";
    } elseif ( is_float( $variable ) ) {
        $res .= '<em style="opacity:0.5; color:tomato">float</em> ';
        $res .= (string) $variable;
    }

    return $res;
}

function debugfile(
    $variable,
) {
    $fp = fopen( __ADMIN__ . DIRECTORY_SEPARATOR . 'monolog.log', 'a' );
    fwrite( $fp, '__' . gettype( $variable ) . "__  ::  \r\n" );
    fwrite( $fp, json_encode( $variable, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE ) );
    fwrite( $fp, "\r\n" );
    fclose( $fp );
}

function jlog(
    $text,
    $text2 = ''
) {
    debugfile( $text );

    return true;
}

// define( '__APP__', __ADMIN__ . DIRECTORY_SEPARATOR . 'app' );

// debug(
// 	[
// 		'__DIR__'    => __DIR__,
// 		'__ROOT__'   => __ROOT__,
// 		'__ADMIN__'  => __ADMIN__,
// 		'__PUBLIC__' => __PUBLIC__,
// 		'__VENDOR__' => __VENDOR__,
// 	]
// );

// debug( $_SERVER );
require __ADMIN__ . DIRECTORY_SEPARATOR . 'admin.php';
