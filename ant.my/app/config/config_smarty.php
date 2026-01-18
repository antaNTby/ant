<?php
use Smarty\Smarty;

function smarty_modifier_intval( $value )
{
    return intval( $value ); // альтернатива использовать формат_строки {$value|string_format:"%d"}
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

Flight::register( 'view', Smarty::class, [], function ( Smarty $smarty ) {
    $smarty->setTemplateDir( __TPL__ );                                                                        // здесь лежат шаблоны tpl.html
    $smarty->setCompileDir( __APP__ . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'compile_dir' );  // здесь компилируюся *.php
    $smarty->setConfigDir( __APP__ . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'smarty_config' ); // незнаю
    $smarty->setCacheDir( __APP__ . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'smarty_cache' );

// Sensible defaults
// $smarty->setCompileCheck( \Smarty\Smarty::COMPILECHECK_OFF ); // ОТКЛЮЧИТЬ проверку компиляции в производстве для максимальной производительности.
// $smarty->compile_check = true; // disable on prod ai:старый булевый флаг (Smarty 3.x).

/*##############
    Режимы кэширования;
    В Smarty4/5 доступны несколько констант:
    Smarty::CACHING_OFF —кэшированиеотключено.
    Smarty::CACHING_LIFETIME_CURRENT —стандартныйрежим: кэш живёт столько, сколько заданов $smarty->cache_lifetime.
    Если lifetime истёк, шаблон пересобирается и кэш обновляется.
    Smarty::CACHING_LIFETIME_SAVED —кэш живёт столько, сколько было сохранено пр исоздании( можно задавать разное время для разных вызовов ).
###############*/

    $smarty->compile_id = 'admin_';

    $smarty->cache_lifetime = 3600 * 24 * 365; // кэш живёт 1 час * 1 день * 1 год
    $smarty->caching        = Smarty::CACHING_LIFETIME_CURRENT;

    $smarty->setCompileCheck( \Smarty\Smarty::COMPILECHECK_ON ); // ВКЛЮЧИТЬ проверку компиляции в производстве для максимальной производительности.
    $smarty->force_compile = true;

    // $smarty->escape_html = true;

/*##############
    $smarty->force_compile = true;
    Smarty игнорирует кэш компиляции и каждый раз заново компилирует .tpl в PHP‑код.
    Это сильно замедляет работу, но гарантирует, что любые изменения в шаблонах сразу применяются, даже если compile_check выключен.
    ⚖️ Отличие от compile_check
    compile_check → проверяет дату изменения .tpl и перекомпилирует только если файл изменился.
    force_compile → перекомпилирует всегда, независимо от изменений.
###############*/

    $smarty->registerPlugin( 'modifier', 'intval', 'smarty_modifier_intval' );
    $smarty->registerPlugin( 'modifier', 'dump', 'smarty_modifier_dump' );
    $smarty->registerPlugin( 'modifier', 'jlog', 'smarty_modifier_jlog' );
    $smarty->registerPlugin( 'modifier', 'formatUsd', 'smarty_modifier_formatUsd' );
    $smarty->registerPlugin( 'modifier', 'formatUnp', 'smarty_modifier_formatUnp' );
    $smarty->registerPlugin( 'modifier', 'zeroPad', 'smarty_modifier_zeroPad' );

    // $smarty->testInstall();

} );

Flight::map( 'render', function (
    string $template,
    array  $data = []
): void {
    Flight::view()->assign( $data );
    Flight::view()->display( $template );
} );

Flight::map( 'fetch', function (
    string $template,
    array  $data = []
): void {
    Flight::view()->assign( $data );
    Flight::view()->fetch( $template );
} );

// если влом писать Флайт
$smarty = Flight::view();
