<?php

// Package Control.sublime-settings
{
    'bootstrapped':true,
    'in_process_packages':
    [
    ],
    'installed_packages':
    [
        'BootstrapAutocomplete',
        'Color Scheme - Bass',
        'Color Scheme - Creamy',
        'Cool & Clear - Color Scheme',
        'Dark Knight Color Scheme',
        'HTML-CSS-JS Prettify',
        'INI',
        'JsPrettier',
        'LSP',
        'LSP-svelte',
        'Nette + Latte + Neon',
        'Package Control',
        'phpfmt',
        'PHPGrammar',
        'ReadonlyMode',
        'SideBarTools',
        'Smarty',
        'SqlBeautifier',
        'Svelte',
    ],
}

// Settings in here override those in "Default/Preferences.sublime-settings",
// and are overridden in turn by syntax-specific settings.
{
    'update_check':false,
    'theme':'Default Dark.sublime-theme',
    'color_scheme':'Mariana.sublime-color-scheme',
    'ignored_packages':
    [
        'Vintage',
    ],
    'index_files':true,
    // Show "project - file" or "file - project" in the title bar.
    'show_project_first':true,
    'default_encoding':'UTF-8',
    'fallback_encoding':'UTF-8',
    'tab_size':4,
    'font_size':8,
    'font_face':'Consolas',
}

// Settings in here override those in "/phpfmt/phpfmt.sublime-settings",
{
    'autocomplete':false,
    'autoimport':false,
    'format_on_save':true,
    'indent_with_space':true, // влючить для HEREDOC
    'php_bin':'c:/OSPanel/modules/PHP-8.3/PHP/php-win.exe',
    'psr1':false,
    'psr1_naming':false,
    'psr2':true, // влючить для HEREDOC
    'readini':true,
    'smart_linebreak_after_curly':true,
    'version':4,
    'wp':false,
    /*"php_bin": "c:/OSPanel/modules/PHP-8.3/PHP/php-win.exe",*/
    // "smart_linebreak_after_curly": false,

    'excludes':[
        'SpaceBetweenMethods',
    ],
    'passes':[
        'AlignConstVisibilityEquals',
        'AlignDoubleArrow',
        'AlignDoubleSlashComments',
        'AlignEquals',
        'AlignGroupDoubleArrow',
        'AlignPHPCode',
        'AlignSuperEquals',
        'AlignTypehint',
        'AutoSemicolon',
        'ConvertOpenTagWithEcho',
        'DoubleToSingleQuote',
        'ExtraCommaInArray',
        'NewLineBeforeReturn',
        'OnlyOrderUseClauses',
        'OnlyOrderUseClauses',
        'PSR2MultilineFunctionParams',
        'ReindentSwitchBlocks',
        'RemoveSemicolonAfterCurly',
        'RestoreComments',
        'ShortArray',
        'SmartLnAfterCurlyOpen',
        'SpaceAroundParentheses',
        // "MergeNamespaceWithOpenTag",
        // "SortUseNameSpace",
        // "SpaceAfterExclamationMark",

    ],

}

//.jsbeautifyrc
{
    'all':{
        'tabWidth':4,
        'semi':true,
        'singleQuote':true,
        'trailingComma':'es5',
        'plugins':['prettier-plugin-svelte'],
    },

    'js':{
        'indent_size':8,
        'indent_char':' ',
        'brace_style':'collapse,preserve-inline',
        'break_chained_methods':false,
        'space_in_empty_paren':false,
        'space_in_paren':false,
        'space_before_conditional':false,
        'preserve_newlines':true,
        'max_preserve_newlines':10,
        'end_with_newline':false,
        'keep_array_indentation':true,
        'unescape_strings':false,
        'jslint_happy':false,
        'wrap_line_length':120,
        'indent_with_tabs':false,
        'comma_first':false,
        'e4x':false,
        'indent_empty_lines':false,
        'operator_position':'before-newline',
    }
}

// Settings in here override those in "/JsPrettier/JsPrettier.sublime-settings",
{
    'auto_format_on_save':true,

    'auto_format_on_save_excludes':[
        // "*/node_modules/*",
        // "*/file.js",
        '*.tpl.html',
    ],

    // "prettier_cli_path": "/usr/local/bin/prettier"
    // "prettier_cli_path": "C:/Users/a/AppData/Roaming/npm/prettier.cmd",
    // "prettier_cli_path": "%USERPROFILE%\\AppData\\Roaming\\npm\\prettier.cmd",
    'prettier_options':
    {

        'printWidth':80,
        'tabWidth':4,
        'singleQuote':true,
        'singleAttributePerLine':true,
        'disable_prettier_cursor_offset':true,
        // "overrides": [
        //     {
        //         "files": "*.tpl.html",
        //         "options": {
        //             "printWidth": 99999
        //         }
        //     }
        // ]

    }
}

#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################
#################

try {
    if ( in_array( $operation, ['insert', 'addnew'] ) && $id <= 0 ) {
        $rowData   = $records->removePrefixForColumnFieds( $requestBody['data'] ?? [] );
        $res['db'] = $records->insertRecord( $rowData );
    } else {
        $operationsMap = [
            'duplicate'  => fn()  => $records->duplicateRecord( $id ),
            'clone'      => fn()      => $records->duplicateRecord( $id ),
            'remove'     => fn()     => $records->deleteRecord( $id ),
            'delete'     => fn()     => $records->deleteRecord( $id ),
            'update'     => fn()     => $records->updateRecord( $id, $requestBody['data'] ?? [] ),
            'saverow'    => fn()    => $records->updateRecord( $id, $requestBody['data'] ?? [] ),

            'sorttop'    => fn()    => $records->sortRefresh() && $records->sortTop( $id ) && $records->sortRefresh(),
            'sortup'     => fn()     => $records->sortRefresh() && $records->sortUp( $id ) && $records->sortRefresh(),
            'sortdown'   => fn()   => $records->sortRefresh() && $records->sortDown( $id ) && $records->sortRefresh(),
            'sortbottom' => fn() => $records->sortRefresh() && $records->sortBottom( $id ) && $records->sortRefresh(),
        ];

        if ( !isset( $operationsMap[$operation] ) ) {
            throw new Exception( "Неприемлемая операция: $operation" );
        }

        $res['db'] = $operationsMap[$operation]();
    }

    if ( !$res['db'] ) {
        throw new Exception( 'Ошибка операции с БД: ' . json_encode( ['action' => $action, 'id' => $id] ) );
    }

    Flight::json( $res, 200, JSON_PRETTY_PRINT );

} catch ( Exception $e ) {
    $errorMessage = $e->getMessage() . ' ' . pathinfo( $e->getFile() )['basename'] . ':' . $e->getLine();
    Flight::logger()->error( $errorMessage );
    Flight::json( ['error' => $e->getMessage(), 'file' => pathinfo( $e->getFile() )['basename'] . ':' . $e->getLine()], 500, JSON_PRETTY_PRINT );
}

/*
error_log("Ошибка обновления записи: " . $e->getMessage());

error_log

(PHP 4, PHP 5, PHP 7, PHP 8)

error_log — Отправляет сообщение об ошибке заданному обработчику ошибок
Описание ¶
error_log(
    string $message,
    int $message_type = 0,
    ?string $destination = null,
    ?string $additional_headers = null
): bool

Функция отправляет сообщение об ошибке в журнал ошибок веб-сервера или в файл.

*/

/*
 * A route is really just a URL, but saying route makes you sound cooler.
 * When someone hits that URL, you point them to a function or method
 * that will handle the request.
 *
 * The below are just examples you can use to understand how it works. Feel free to change it up.
 */
Flight::route( 'GET /', function () {
    echo '<h1>Welcome to the Flight Simple Example!</h1><h2>You are gonna do great things!</h2>';
} );

Flight::route( 'GET /hello-world/@name', function ( $name ) {
    echo '<h1>Hello world! Oh hey ' . $name . '!</h1>';
} );

Flight::group( '/api', function () {
    Flight::route( 'GET /users', function () {
        // You could actually pull data from the database if you had one set up
        // $users = Flight::db()->fetchAll("SELECT * FROM users");
        $users = [
            ['id' => 1, 'name' => 'Bob Jones', 'email' => 'bob@example.com'],
            ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bsmith@example.com'],
            ['id' => 3, 'name' => 'Suzy Johnson', 'email' => 'suzy@example.com'],
        ];

        // You actually could overwrite the json() method if you just wanted to
        // to Flight::json($users); and it would auto set pretty print for you.
        // https://flightphp.com/learn#overriding
        Flight::json( $users, 200, true, 'utf-8', JSON_PRETTY_PRINT );
    } );
    Flight::route( 'GET /users/@id:[0-9]', function ( $id ) {
        // You could actually pull data from the database if you had one set up
        // $user = Flight::db()->fetchRow("SELECT * FROM users WHERE id = ?", [ $id ]);
        $users = [
            ['id' => 1, 'name' => 'Bob Jones', 'email' => 'bob@example.com'],
            ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bsmith@example.com'],
            ['id' => 3, 'name' => 'Suzy Johnson', 'email' => 'suzy@example.com'],
        ];

        $users_filtered = array_filter( $users, function ( $data ) use ( $id ) {
            return $data['id'] === (int) $id;
        } );
        if ( $users_filtered ) {
            $user = array_pop( $users_filtered );
        }

        Flight::json( $user, 200, true, 'utf-8', JSON_PRETTY_PRINT );
    } );
    Flight::route( 'POST /users/@id:[0-9]', function ( $id ) {
        // You could actually update data from the database if you had one set up
        // $statement = Flight::db()->runQuery("UPDATE users SET email = ? WHERE id = ?", [ Flight::data['email'], $id ]);
        Flight::json( ['success' => true, 'id' => $id], 200, true, 'utf-8', JSON_PRETTY_PRINT );
    } );
} );

Flight::route( '/cls', function () {
    echo 'log.ini очищен!';
    \cls();
} );

Flight::route( '/', function () {
    echo 'hello? world';
} );

Flight::route( '/log', function () {
    Flight::linfo( time(), ['nix2025'] );
} );

####################
####################
####################
####################
####################
####################
####################
####################
####################
####################

const paramparam = 3333344443;
const ass        = 'ass';
Flight::set( 'Flight . paramparam', 33 );
$supa = 'supaSupa захотел';
// Назначить данные шаблона
Flight::view()->assign( 'name', 'Bob' );

// This will create the database and the users table if it doesn't exist already

$config = require __APP__ . DIRECTORY_SEPARATOR . 'connect.php';

$sql = 'CREATE TABLE  IF NOT EXISTS currencies (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, value TEXT NOT NULL , symbol TEXT NOT NULL);';

$sql2 = 'CREATE TABLE  IF NOT EXISTS customers (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, email TEXT NOT NULL UNIQUE, password TEXT NOT NULL);';

$sql3 = 'CREATE TABLE  IF NOT EXISTS products (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, description TEXT, categoryID INTEGER NOT NULL);';

$ldb = Flight::ldb();

monolog( $sql );
$ldb->runQuery( $sql );
$ldb->runQuery( $sql2 );
$ldb->runQuery( $sql3 );

$sql_T = '
BEGIN TRANSACTION;
CREATE TABLE  IF NOT EXISTS categories (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, description TEXT, parentID INTEGER NOT NULL);
CREATE TABLE  IF NOT EXISTS orders (id INTEGER PRIMARY KEY AUTOINCREMENT, customerID INTEGER NOT NULL, time_stump DATETIME);
COMMIT;
';

$ldb->runQuery( $sql_T );

$sql4 = "
CREATE TABLE IF NOT EXISTS session (
  `id` varchar(32) NOT NULL,
  `data` text NOT NULL,
  `expire` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(15) NOT NULL,
  `Referer` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `URL` text NOT NULL,
  PRIMARY KEY (`id`)
  )
;
";

$ldb->runQuery( $sql4 );

// $ldb->runQuery( 'CREATE TABLE customers (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, email TEXT NOT NULL UNIQUE, password TEXT NOT NULL)' );

/*
function currGetAllCurrencies() {
	$q = db_query( 'select Name, code, currency_iso_3, currency_value, where2show, CID, sort_order, roundval from ' .
		CURRENCY_TYPES_TABLE . ' order by sort_order' );
	$data = [];
	while ( $row = db_fetch_row( $q ) ) {
		$data[] = $row;
	}

	return $data;
}

// get all currencies
$currencies = currGetAllCurrencies();
$smarty->assign( 'currencies', $currencies );

//set sub-department template
$smarty->assign( 'admin_sub_dpt', 'conf_currencies.tpl.html' );
*/
##
##
##
##
##
##
##
##
##
##
##
##

const paramparam = 3333344443;
const ass        = 'ass';
Flight::set( 'Flight . paramparam', 33 );
$supa = 'supaSupa захотел';
// Назначить данные шаблона
Flight::view()->assign( 'name', 'Bob' );

$file = 'test.tpl.html';

if ( Flight::view()->templateExists( $file ) ) {

    Flight::fetch( $file,

        [
            'admin_main_content_template' => __TPL__ . DIRECTORY_SEPARATOR . 'subs' . DIRECTORY_SEPARATOR . $sub . '_sub.tpl.html',
            'paramparam'                  => Flight::get( 'Flight.paramparam' ),
            'supa'                        => [$supa, $sub],
        ]

    );
} else {
    Flight::notFound();
}

#############
#############
#############
#############
$curr                 = new CurrencyTypes( $database_connection );
$curr->Name           = 'Bobby Tables';
$curr->currency_value = 5;
$curr->insert();

$usd = $curr->find( 1 );
dump( $usd->code );

$currencies = $curr->findAll();

$tpl = <<<HTML
    {* <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script> *}
    <script src="/lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    {* include some javascript in your template *}
    {* {fetch file="https://{$smarty.const.SERVER_NAME}/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"} *}

     {* <script {fetch file='https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js'}> *}
    {* {fetch file='/export/httpd/www.example.com/docs/navbar.js'} *}

    <script>
      fetch('{$script_url}')
        .then(response => response.text())
        .then(script => {
          const scriptElement = document.createElement('script');
          scriptElement.textContent = script;
          document.body.appendChild(scriptElement);
        })
        .catch(error => console.error('Ошибка:', error));
    </script>
HTMLl;
