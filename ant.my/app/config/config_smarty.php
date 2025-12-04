<?php
use Smarty\Smarty;

define( 'INDEX_TPL_HTML', 'index.tpl.html' );
// define( 'INDEX_TPL_HTML', 'admin.tpl.html' );

/*
ai-1.- Создать кастомный модификатор: Если вам нужно использовать intval в шаблоне, можно зарегистрировать свой модификатор:
*/

function smarty_modifier_intval( $value )
{
    // альтернатива использовать формат_строки {$value|string_format:"%d"}
    return intval( $value );
}
function smarty_modifier_dump( $value )
{
    return dump( $value );
}
function smarty_modifier_jlog( $value )
{
    return jlog( $value );
}
function smarty_modifier_formatUsd( $value )
{
    return formatUsd( $value );
}

function smarty_modifier_formatUnp( $string )
{
    // Удалим всё, кроме цифр
    $digits = preg_replace( '/\D/', '', $string );
    // Преобразуем в формат 123 456 789

    return preg_replace( '/(\d{3})(\d{3})(\d{3})/', '$1 $2 $3', $digits );
}
function smarty_modifier_zeroPad(
    $number,
    $length = 2,
    $symbol = '0'
) {
    return str_pad( $number, $length, $symbol, STR_PAD_LEFT );
}

// если влом писать Флайт
$smarty = Flight::view();
// $smarty->assign( 'blablabla', 'BLABLABLA' );

Flight::register( 'view', Smarty::class, [], function ( Smarty $smarty ) {
    $smarty->setTemplateDir( __TPL__ );                                                                        // здесь лежат шаблоны tpl.html
    $smarty->setCompileDir( __APP__ . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'compile_dir' );  // здесь компилируюся *.php
    $smarty->setConfigDir( __APP__ . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'smarty_config' ); // незнаю
    $smarty->setCacheDir( __APP__ . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'smarty_cache' );
    $smarty->compile_id    = 'admin_';
    $smarty->force_compile = true;

    // Обязательно отключите проверку компиляции в производстве для максимальной производительности.
    $smarty->setCompileCheck( \Smarty\Smarty::COMPILECHECK_OFF );
/*
ai-2.- Затем подключить его в Smarty:
*/
    $smarty->registerPlugin( 'modifier', 'intval', 'smarty_modifier_intval' );
    $smarty->registerPlugin( 'modifier', 'dump', 'smarty_modifier_dump' );
    $smarty->registerPlugin( 'modifier', 'jlog', 'smarty_modifier_jlog' );
    $smarty->registerPlugin( 'modifier', 'formatUsd', 'smarty_modifier_formatUsd' );
    $smarty->registerPlugin( 'modifier', 'formatUnp', 'smarty_modifier_formatUnp' );
    $smarty->registerPlugin( 'modifier', 'zeroPad', 'smarty_modifier_zeroPad' );

    // $smarty->testInstall();
} );

Flight::map( 'tplError', function (
    string $template = INDEX_TPL_HTML,
): void {
    Flight::view()->assign( [
        'template' => $template,
    ] );
    Flight::view()->display( 'tplError.tpl.html' );
    // $logger->error( $template . '<br> Smarty template not exists.<br>' );

} );

Flight::map( 'render', function (
    string $template = INDEX_TPL_HTML,
    array  $data = []
): void {
    // dd( $data );
    Flight::view()->assign( $data );

    if ( Flight::view()->templateExists( $template ) ) {
        Flight::view()->display( $template );
    } else {
        Flight::tplError( $template );
    }

} );

Flight::map( 'fetch', function (
    string $template = INDEX_TPL_HTML,
    array  $data = []
): void {
    Flight::view()->assign( $data );
    if ( Flight::view()->templateExists( $template ) ) {
        Flight::view()->fetch( $template );
    } else {
        Flight::tplError( $template );
        // Flight::halt( 406, '<em>' . $template . '</em>' . '<br><br> Smarty template not exists.<br> 406 Not Acceptable' );
    }
} );

//
