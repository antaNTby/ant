<?php
use classes\SimplePaginator;
use classes\SmartyControl;

// currency_sub.php
$smarty = Flight::view();

$sql_create_table = <<<SQL
-- nixby_UTF8.UTF_order_status определение
CREATE TABLE `UTF_order_status` (
  `statusID` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(60) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`statusID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
SQL;

// Получите экземпляр вашего класса
$records        = Flight::statusesSub();
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
// $tableData = $records->viewPage();
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
            'index'         => $row['statusID'],
            'controlName'   => $k,
            'current_value' => $v,
        ];

        $params           = $records->generateConfigForTableDataSmartyControl( $k, $params );
        $controls[$key][] = new SmartyControl(
            $params
        );

    } // закончили с горизонтальными данными
      ### создаем контролы всей строки данных
    $params = [
        'index'       => $row['statusID'],
        'controlName' => 'actions',
        'controlType' => 'row_buttons',
        'btnClone'    => 1,
        // 'btnClear'  => 1,
        'btnDelete'   => 1,
        // 'btnAddNew' => 1,
        'btnSaveRow'  => 1,
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
        'controlType' => 'row_buttons',
        'controlName' => 'actions',
        'btnAddNew'   => 1,
    ]
);
$controls['mainToolbar']['actions'] = new SmartyControl(
    [
        'index'       => 'mainTable',
        'controlType' => 'button_toolbar',
        'controlName' => 'actionsAll',
        'btnFixSort'  => 1,
        'btnSaveAll'  => 1,
        // 'btnDeleteAll' => 1,
    ]
);

$smartyData = [
    'data'      => $tableData,
    'controls'  => $controls,

    'td_names'  => $records->td_names ?? null,
    'td_styles' => $records->td_styles ?? null,
    'th_styles' => $records->th_styles ?? null,
    'th_titles' => $records->th_titles ?? null,
    'pageH1'    => 'Администрирование статусов заказов',
    'title_ru'  => 'Администрирование статусов заказов',
];
