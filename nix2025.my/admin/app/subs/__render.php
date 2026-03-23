<?php

if ( Flight::get( 'current_sub' ) ) {
    $contentTemplate = __TPL__ . DIRECTORY_SEPARATOR . 'subs' . DIRECTORY_SEPARATOR . Flight::get( 'current_sub' ) . '_sub.tpl.html';

    if ( !Flight::view()->templateExists( $contentTemplate ) ) {
        $contentTemplate = __TPL__ . DIRECTORY_SEPARATOR . 'page_not_found.tpl.html';
        // dump( $contentTemplate );
        Flight::halt( 406, '<em>' . $contentTemplate . '</em>' . "<br><br>Smarty main template `$contentTemplate` not exist.<br>406 Not Acceptable" );
    } else {

        $contentTemplate_exist = 1;
        Flight::fetch( $contentTemplate, $smartyData );
    }
} else {
    $contentTemplate       = __TPL__ . DIRECTORY_SEPARATOR . 'home.tpl.html';
    $contentTemplate_exist = 1;

}

$renderData = [
    'admin_main_content_template' => $contentTemplate,
    'template_exist'              => $contentTemplate_exist,
    'body_height'                 => Flight::get( 'body_height' ),
    'body_width'                  => Flight::get( 'body_width' ),
    'aside_reverse'               => Flight::get( 'aside_reverse' ),
    'aside_hide'                  => Flight::get( 'aside_hide' ),
    'title'                       => $smartyData ? $smartyData['title_ru'] : '-=ЗАГОЛОВОК=-',
    'current_sub'                 => Flight::get( 'current_sub' ) ?? '',
    'primary_key'                 => Flight::get( 'primary_key' ) ?? '',
];
$renderData = array_merge( $renderData, $smartyData ); //SmartyData может переписать дефольтные рендерДата

Flight::render( 'index.tpl.html', $renderData );
