<?php

if (!defined('ANT_RBCOLUMNS'))
{
    define('ANT_RBCOLUMNS', 'ant_rbcolumns');
}
if (!defined('SYSTEM_TABLE'))
{
    define('SYSTEM_TABLE', DB_PRFX . 'system');
}

if (!defined('SESSION_TABLE'))
{
    define('SESSION_TABLE', DB_PRFX . 'session');
}

if (!defined('CUSTOMERS_TABLE'))
{
    define('CUSTOMERS_TABLE', DB_PRFX . 'customers');
}

if (!defined('ORDERS_TABLE'))
{
    define('ORDERS_TABLE', DB_PRFX . 'orders');
}

if (!defined('INVOICES_TABLE'))
{
    define('INVOICES_TABLE', DB_PRFX . 'invoices');
}

if (!defined('NEWINVOICES_TABLE'))
{

    define('NEWINVOICES_TABLE', 'nano_' . 'invoices');
}

if (!defined('CATEGORIES_TABLE'))
{

    define('CATEGORIES_TABLE', DB_PRFX . 'categories');
}

if (!defined('CATEGORY_PRODUCT_TABLE'))
{

    define('CATEGORY_PRODUCT_TABLE', DB_PRFX . 'category_product');
}

if (!defined('COMPANIES_TABLE'))
{

    define('COMPANIES_TABLE', DB_PRFX . 'companies');
}

if (!defined('CURRENCY_TYPES_TABLE'))
{

    define('CURRENCY_TYPES_TABLE', DB_PRFX . 'currency_types');
}

if (!defined('ONLINE_TABLE'))
{

    define('ONLINE_TABLE', DB_PRFX . 'online');
}

if (!defined('ORDERED_CARTS_TABLE'))
{

    define('ORDERED_CARTS_TABLE', DB_PRFX . 'ordered_carts');
}

if (!defined('PRODUCTS_TABLE'))
{

    define('PRODUCTS_TABLE', DB_PRFX . 'products');
}

if (!defined('SHOPPING_CARTS_TABLE'))
{
    define('SHOPPING_CARTS_TABLE', DB_PRFX . 'shopping_carts');
}
