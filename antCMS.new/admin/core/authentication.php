<?php

define( 'SECURITY_EXPIRE', 60 * 60 * CONF_SECURITY_EXPIRE );
$LOG_OK = false;

session_set_save_handler( 'sess_open', 'sess_close', 'sess_read', 'sess_write', 'sess_destroy', 'sess_gc' );

session_set_cookie_params( SECURITY_EXPIRE );

# стартуем сессию
session_start();

# посылаем cookie сессии
if ( isset( $_COOKIE['PHPSESSID'] ) ) {
	if ( SECURITY_EXPIRE > 0 ) {
		set_cookie( 'PHPSESSID', $_COOKIE['PHPSESSID'], time() + SECURITY_EXPIRE );
	} else {
		set_cookie( 'PHPSESSID', $_COOKIE['PHPSESSID'] );
	}
}

// ЗДЕСЬ ВСТАВЛЯЮТСЯ DO=PROCESSOR

// cls();

$relaccess = checklogin();

if ( ( !isset( $_SESSION['log'] ) || !in_array( 100, $relaccess ) ) ) {

	if ( isset( $_POST['user_login'] ) && isset( $_POST['user_pw'] ) )
	// if (isset($_POST['user_login']))

	{

		// включено подтверждение регистрации
		if ( regAuthenticate( $_POST['user_login'], $_POST['user_pw'] ) ) {

			Redirect( set_query( '&__tt=' ) );

		}
		die( ERROR_FORBIDDEN_LOGIN );
	}

	$wrongLoginOrPw = 1;
	die( ERROR_FORBIDDEN );
} else {
	$LOG_OK = true;
}

# user logout
if ( isset( $_GET['logout'] ) ) {
	unset( $_SESSION['log'] );
	unset( $_SESSION['pass'] );

	// RedirectJavaScript(ADMIN_FILE . '?access_deny=' . SITE_URL);
	// RedirectJavaScript(ADMIN_FILE);

	if ( in_array( 100, $relaccess ) ) {
		Redirect( ADMIN_FILE );
	} else {
		// Redirect("/index.php?user_details=yes");
		Redirect( '/index.php' );
	}

	die( ERROR_FORBIDDEN );
}

$LOG_OK = true;
Redirect( ADMIN_FILE );

// die(ERROR_FORBIDDEN);
/*

INSERT INTO `ant_customers` (`customerID`, `Login`, `cust_password`, `Email`, `first_name`, `last_name`, `subscribed4news`, `custgroupID`, `addressID`, `reg_datetime`, `ActivationCode`, `CID`, `affiliateID`, `affiliateEmailOrders`, `affiliateEmailPayments`, `actions`, `customer_aka`) VALUES
(1, 'admin',    '', 'antaNT.pro@gmail.com', 'Alexander',    '+375 296583031',   0,  2,  14, '2018-08-25 10:23:23',  '', 5,  0,  1,  1,  'a:35:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:3:\"100\";}',    NULL);

 */
