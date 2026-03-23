<?php

const UI_DEPARTMENTS = array(
####
    ##catalog
    array(
        'id'              => 'catalog',          //department ID
        'sort_order'      => 10,                 //sort order (less `sort_order`s appear first)
        'name'            => LANG_ADMIN_CATALOG, //department name
        'sub_departments' => array
        (
            array('id' => 'products_categories', 'name' => LANG_CATEGORIES_PRODUCTS, 'default' => true),
            array('id' => 'tree', 'name' => LANG_JSTREE)
            // array( "id" => "dbsync", "name" => ADMIN_SYNCHRONIZE_TOOLS ),
            // array( "id" => "CSV_import", "name" => ADMIN_IMPORT_FROM_CSV ),
            // array( "id" => "import_vendor_price", "name" => "Импорт товаров \"jet.by\" *.XML" ),
            // array( "id" => "excel_export", "name" => ADMIN_EXPORT_TO_EXCEL ),
            // array( "id" => "discuss", "name" => ADMIN_DISCUSSIONS ),
            //
        )
    ),
    ##trade
    array(
        'id'              => 'trade',    //department ID
        'sort_order'      => 20,         //sort order (less `sort_order`s appear first)
        'name'            => LANG_TRADE, //department name
        'sub_departments' => array
        (
            array('id' => 'orders', 'name' => LANG_ORDER_LIST, 'default' => true),
            array('id' => 'companies', 'name' => LANG_COMPANIES),
            array('id' => 'contracts', 'name' => LANG_CONTRACTS),
            array('id' => 'custlist', 'name' => LANG_CUSTOMERS)
        )
    ),
    ##conf                                     // conf
    array('id'        => 'conf',        //department ID
        'sort_order'      => 100,           //sort order (less `sort_order`s appear first)
        'name'            => LANG_SETTINGS, //department name
        'sub_departments' => array
        (
            array('id' => 'currencies', 'name' => LANG_CURRENCY_TYPES, 'default' => true),
            array('id' => 'admin_edit', 'name' => LANG_CONF_ADMINS),
            array('id' => 'settings', 'name' => LANG_SETTINGS_GENERAL),
            array('id' => 'columns', 'name' => 'Конфигуратор колонок'),
            array('id' => 'shipping', 'name' => LANG_SHIPPING_TYPE),
            array('id' => 'payment', 'name' => LANG_PAYMENT_TYPE),
        )
    )

####
);

const UI_TABLES = array(
    'products_categories' => PRODUCTS_TABLE,
    'tree'                => CATEGORIES_TABLE,
    'orders'              => ORDERS_TABLE,
    'companies'           => COMPANIES_TABLE,
    'custlist'            => CUSTOMERS_TABLE,
    'currencies'          => CURRENCY_TYPES_TABLE,
    'admin_edit'          => CUSTOMERS_TABLE
// "settings"=>TABLE,
    // "shipping"=>TABLE,
    // "payment"=>TABLE
);
const TABLES_PRIMARY_KEYS = array(
    PRODUCTS_TABLE       => 'productID',
    CATEGORIES_TABLE     => 'categoryID',
    ORDERS_TABLE         => 'orderID',
    COMPANIES_TABLE      => 'companyID',
    CUSTOMERS_TABLE      => 'customerID',
    CURRENCY_TYPES_TABLE => 'CID',
);
