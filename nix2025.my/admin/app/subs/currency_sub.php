<?php
use classes\SimplePaginator;
use classes\SmartyControl;
use subs\CurrencyTypes;

// currency_sub.php
$smarty = Flight::view();

$sql_create_table = <<<SQL

CREATE TABLE `UTF_currency_types` (
`CID` int(11) NOT NULL AUTO_INCREMENT,
`Name` varchar(60) DEFAULT NULL,
`code` varchar(60) DEFAULT NULL,
`currency_value` float DEFAULT NULL,
`where2show` int(11) DEFAULT NULL,
`sort_order` int(11) DEFAULT '0',
`currency_iso_3` char(3) DEFAULT NULL,
`roundval` int(11) DEFAULT '2',
PRIMARY KEY (`CID`),
KEY `UTF_currency_types_sort_order_IDX` (`sort_order`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

SQL;

// Получите экземпляр вашего класса
// $records        = Flight::currencySub();
$records        = new CurrencyTypes( [] );
$tableDataAll   = $records->showAll();
$totalItemCount = count( $tableDataAll );
if ( Flight::get( 'current_page' ) == -1 && Flight::get( 'items_per_page' ) == -1 ) {
    Flight::set( 'items_per_page', $totalItemCount );
}
$tableData = $records->viewPage(
    Flight::get( 'current_page' ) ?? 1,
    Flight::get( 'items_per_page' ) ?? DEFAULT_ITEMS_PER_PAGE,
    ( null !== Flight::get( 'sort_direction' ) ) ? ( ( trim( strtoupper( Flight::get( 'sort_direction' ) ) ) === 'DESC' ) ? 1 : 0 ) : 0,
    Flight::get( 'sort_field' ) ?? 'sort_order'
);
Flight::set( 'primary_key', $records->getPrimaryKey() );
$navigatorConfig = [
    'subName'          => Flight::get( 'current_sub' ),
    'total_item_count' => $totalItemCount,
    'current_page'     => Flight::get( 'current_page' ),
    'items_per_page'   => Flight::get( 'items_per_page' ),
    'sort_field'       => Flight::get( 'sort_field' ),
    'sort_direction'   => Flight::get( 'sort_direction' ),
];
$navigator = new SimplePaginator( $smarty, $navigatorConfig );

$controls = [];
foreach ( $tableData as $key => $row ) {
// по вертикали
    foreach ( $row as $k => $v ) {
// по горизонтали
        ### создаем контролы для табличных данных
        $params = [
            'index'         => $row[$records->getPrimaryKey()],
            'controlName'   => $k,
            'current_value' => $v,
        ];
        // $params           = _getControlParameters( $k, $params );
        $params           = $records->generateConfigForTableDataSmartyControl( $k, $params );
        $controls[$key][] = new SmartyControl(
            $params
        );
    } // закончили с горизонтальными данными

    ### создаем контролы всей строки данных
    $params = [
        'index'       => $row[$records->getPrimaryKey()],
        'controlName' => 'actions',
        'controlType' => 'row_buttons',
        'btnSaveRow'  => 1,
        'btnClone'    => 1,
        // 'btnClear'  => 1,
        'btnDelete'   => 1,
    ];
    $controls[$key]['actions'] = new SmartyControl(
        $params
    );

} // закончили с табличными данными

### создаем контролы всей строки для добавления новоой строки в таблицу
$newFields = $records->defaultValues;

foreach ( $newFields as $key => $value ) {
    $params = [
        'index'         => -1,
        'controlName'   => $key,
        'current_value' => $value,
    ];
    $params                   = $records->generateConfigForTableDataSmartyControl( $key, $params );
    $controls['addNew'][$key] = new SmartyControl(
        $params
    );

}

$controls['addNew']['actions'] = new SmartyControl(
    [
        'index'       => -1,
        'controlName' => 'actions',
        'controlType' => 'row_buttons',
        'btnAddNew'   => 1,
    ]
);

$controls['mainToolbar']['actions'] = new SmartyControl(
    [
        'index'       => 'mainTable',
        'controlName' => 'actionsAll',
        'controlType' => 'button_toolbar',
        'btnFixSort'  => 1,
        'btnSaveAll'  => 1,
        // 'btnDeleteAll' => 1,
    ]
);

$smartyData = [
    'data'      => $tableData,
    'controls'  => $controls,
    // 'nav_links' => $nav_links,
    'td_names'  => $records->td_names ?? null,
    'td_styles' => $records->td_styles ?? null,
    'th_styles' => $records->th_styles ?? null,
    'th_titles' => $records->th_titles ?? null,
    'pageH1'    => 'Администрирование типов валют',
    'title_ru'  => 'Администрирование типов валют',

    'dataT'     => [1, 2, 3, 4, 5, 6, 7, 8, 9],
    'tr'        => ['bgcolor="#ee11ee"', 'bgcolor="#1111dd"'],
];
