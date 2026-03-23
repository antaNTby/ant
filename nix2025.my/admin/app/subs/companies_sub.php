<?php
use classes\SimplePaginator;
use classes\SmartyControl;

// customers_sub.php
$smarty = Flight::view();

$sql_create_table = <<<SQL

-- nixby_UTF8.UTF_companies определение
CREATE TABLE `UTF_companies` (
  `companyID` int(11) NOT NULL AUTO_INCREMENT,
  `company_title` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_unp` varchar(32) DEFAULT NULL,
  `company_okpo` varchar(32) DEFAULT NULL,
  `company_adress` text,
  `company_bank` text,
  `company_contacts` text,
  `company_director` text,
  `company_data` text,
  `company_admin` text,
  `read_only` int(1) DEFAULT '0',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`companyID`),
  KEY `company_unp` (`company_unp`)
) ENGINE=InnoDB AUTO_INCREMENT=3000 DEFAULT CHARSET=utf8 COMMENT='Таблица реквизитов Компаний';

SQL;

/*

companyID
company_title
company_name
company_unp
company_okpo
company_adress
company_bank
company_contacts
company_director
company_data
company_admin
read_only
update_time

*/

// Получите экземпляр вашего класса
$records        = Flight::companiesSub();
$tableDataAll   = $records->showAll();
$totalItemCount = count( $tableDataAll );
if ( Flight::get( 'current_page' ) == -1 && Flight::get( 'items_per_page' ) == -1 ) {
    Flight::set( 'current_page', 1 );
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
            'index'         => $row[$records->getPrimaryKey()],
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
    'data'       => $tableData,
    'controls'   => $controls,

    'td_names'   => $records->td_names ?? null,
    'td_styles'  => $records->td_styles ?? null,
    'th_styles'  => $records->th_styles ?? null,
    'th_titles'  => $records->th_titles ?? null,
    'pageH1'     => 'Справочник реквизитов',
    'title_ru'   => 'Справочник реквизитов',
    'aside_hide' => true,

    'dataT'      => [1, 2, 3, 4, 5, 6, 7, 8, 9],
    'tr'         => ['bgcolor="#ee11ee"', 'bgcolor="#1111dd"'],
];
