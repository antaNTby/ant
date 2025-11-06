<?php
// ini_set( 'session.serialize_handler', 'php_serialize' );

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
    $fp = fopen( __DIR__ . DIRECTORY_SEPARATOR . 'debug.json', 'a' );
    fwrite( $fp, mb_strtoupper( gettype( $variable ) ) . "\r\n" );
    fwrite( $fp, json_encode( $variable, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE ) );
    fwrite( $fp, "\r\n" );
    fwrite( $fp, "\r\n" );
    fclose( $fp );
}

function jlog(
    $text,
    $text2 = ''
) {
    debugfile( $text );
    if ( $text2 !== '' ) {
        debugfile( $text2 );
    }

    return true;
}

define( '__PARENT_DIR__', dirname( __DIR__, 1 ) );
define( '__ROOT__', __DIR__ );
define( '__PUBLIC__', __ROOT__ . DIRECTORY_SEPARATOR . 'public' );
define( 'SERVER_NAME', $_SERVER['SERVER_NAME'] );

require_once __PUBLIC__ . DIRECTORY_SEPARATOR . 'admin.php';
