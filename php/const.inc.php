<?

/**
* const.inc - standard constants file.
* @author Eric Schabell <eric@schabell.com>
* @copyright 2006, GPL.
*/

// PHP should complain about uninitialized variables:
//
#error_reporting( E_ALL );
error_reporting( 0 );

// Database settings.
//
define( "DB_SERVER",    "127.1.7.1" );
define( "DB_USER",      "admin" );	
define( "DB_PASSWORD",  "NWh_ApJd8tGi" );	
define( "DB_DATABASE",  "babygame" );	

// admin processors of tech reports:
define( "ADMIN_GAME", "eric@schabell.org" );

// our logging location!
define( "BABYLOG", "/var/tmp/baby.log" );

// some general info.
define( "FAMILYNAME", "Schabell" );
define( "DUEDATE", "November 20th, 2012" );
define( "ENDSUBMITS", "2012-12-31" );
?>
