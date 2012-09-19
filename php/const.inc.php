<?php

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
define( "DB_SERVER",    $_ENV['OPENSHIFT_DB_HOST'] );
define( "DB_USER",      $_ENV['OPENSHIFT_DB_USERNAME'] );	
define( "DB_PASSWORD",  $_ENV['OPENSHIFT_DB_PASSWORD'] );	
define( "DB_DATABASE",  "babygame" );	

// admin processors of tech reports:
define( "ADMIN_GAME", "eric@schabell.org" );

// some general info.
define( "FAMILYNAME", "OpenShift" );
define( "DUEDATE", "December 14th, 2011" );
define( "ENDSUBMITS", "2014-12-14" );
?>
