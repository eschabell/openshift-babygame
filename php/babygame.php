<?

/**
* babygame.php
* Created by Eric D. Schabell <eric@schabell.com>
* Copyrite 2006, GPL.
*/

include_once( 'const.inc.php' );

// Global variables.
$thisMonth  = 10;          // my b-month.
$endOfMonth = 31;          // end of a month.
$december   = 12;          // last month of year.
$thisDay    = 20;          // my b-day.
$thisHour   = 18;          // isabel birth hour.
$thisMin    = 41;          // isabel birth min.
$maxHour    = 24;
$maxMin     = 59;
$timestamp  = date( Y ) . "-" . $_POST['birthmonth'] . "-" . $_POST['birthday'] . " " . $_POST['birthhour'] . ":" . $_POST['birthminute'] . ":00";
	
$connect = mysql_connect( DB_SERVER, DB_USER, DB_PASSWORD );
@mysql_select_db( DB_DATABASE, $connect ) or die( "Unable to select database");

// Incomming data.
#print "DEBUG: POST data comming in - <br> \n";
#print_r( $_POST );
#print "<hr>\n";


/**
 * Reports a failure message with back button and includes history.
 *
 * @param string The message to be displayed.
 */
function failure ( $message )
{
	print "<center>\n\n";
	print "<p>\n";
	print "<h2>$message</h3>";
	print "Please go back and try again.\n";
	print "</p>\n";
	print "<form name='buttonbar'>\n";
	print "<input type='button' STYLE='color: yellow;background: navy' value='Back' onClick='history.back()'>\n";
	print "</input>\n";
	print "</form>\n";
	print "</center>\n\n";
	return;
}

/**
 * Displays the total guesses in a table overview.
 *
 * @param resource A mysql db connection resource.
 */
function displayOverview( $connect )
{
	$today = date( 'Y-m-d H:i' );
	print "<table width='80%' border='1'>";
	print "<tr bgcolor='lightyellow'><th>Name:</th><th>Birthdate:</th><th>Sex:</th><th>Baby name:</th></tr>";

	@mysql_select_db( DB_DATABASE, $connect ) or die( "Unable to select database");
	$guesses = "SELECT name, birthdate, birthsex, babyname FROM guesses ORDER BY birthdate;";
	$results = mysql_query( $guesses, $connect );

	
	while ( $row = mysql_fetch_array( $results ) )
	{
		// pick up day for checks on due date.
		list( $day, $time ) = split( " ", $row['birthdate'] );

		// special color on due date.
		if ( $row['birthdate'] < $today ) 
		{
			print "<tr bgcolor='red'>";
		}
		else
		{
			if ( $day == ENDSUBMITS ) { print "<tr bgcolor='lightgreen'>"; }
			elseif ( $row['birthsex'] == 'boy' ) { print "<tr bgcolor='lightblue'>"; }
			else { print "<tr bgcolor='lightpink'>"; }
		}
			
		print "<td align='center'>" . $row['name'] . "</td><td align='center'>" . $row['birthdate'];
		print "</td><td align='center'>" . $row['birthsex'] . "</td>";
		print "</td><td align='center'>" . $row['babyname'] . "</td></tr>";
	}

	print "</table>";
	print "Note on colors: green = due date, blue = boy, pink = girl, red = date past, you lose!";
}

/**
 * Addes a guess to the database.
 *
 * @param resource A mysql database connection resource.
 * @param string The submitted birthdate guess.
 * @param array The guess data being submitted.
 */
function addGuess( $connect, $timestamp, $data )
{
	@mysql_select_db( DB_DATABASE, $connect ) or die( "Unable to select database");
		
	// input our guess.
	$query  = "INSERT INTO guesses VALUES ( NULL,'" . $data['submitername'] . "', '";
	$query .= $data['submiteremail'] . "', '" . $timestamp . "', '" . $data['birthsex'];
	$query .= "', '" . $data['babyname'] . "');";
	mysql_query( $query, $connect );
}

/**
 * Addes a guess to the database.
 *
 * @param resource A mysql database connection resource.
 * @param string The submitted birthdate guess.
 * @param string The submitted birthsex guess.
 */
function duplicateEntry( $connect, $date, $sex )
{
	@mysql_select_db( DB_DATABASE, $connect ) or die( "Unable to select database");
	$select = "SELECT birthdate, birthsex FROM guesses;";
	$selectResults = mysql_query( $select, $connect );

	while ( $row = mysql_fetch_array( $selectResults ) )
	{
		#print "DEBUG: " . $row['birthdate'] . " == " . $date . " && " . $row['birthsex'] . " == " . $sex;
		if ( ( $row['birthdate'] == $date ) && ( $row['birthsex'] == $sex ) ) { return TRUE; }
	}

	// no duplicates!
	return FALSE;
}

/**
 * Displays some statistics in a table.
 *
 * @param resource A mysql database connection resource.
 */
function displaySubmissionForm()
{
	// variables needed.
	$thisMonth  = 10;          // my b-month.
	$endOfMonth = 31;          // end of a month.
	$december   = 12;          // last month of year.
	$thisDay    = 20;          // my b-day.
	$thisHour   = 18;          // isabel birth hour.
	$thisMin    = 41;          // isabel birth min.
	$maxHour    = 24;
	$maxMin     = 59;
	$timestamp  = date( Y ) . "-" . $_POST['birthmonth'] . "-" . $_POST['birthday'] . " " . $_POST['birthhour'] . ":" . $_POST['birthminute'] . ":00";

	print '<h4>Submissions will close on the due date (' . DUEDATE . ')</h4>';
	print '<form action="babygame.php" method="post">';
	print '<input type="hidden" name="action" value="guess">';
	print '<table width="80%" border="1">';
	print '<tr bgcolor="lightyellow">';
	print '<th width="30%" align="left">Your name:</th>';
	print '<td width="70%"><input type="text" size="30" value="" aliagn="left" name="submitername"></input></td>';
	print '</tr><tr bgcolor="lightyellow"><th width="30%" align="left">Your email:*</th>';
	print '<td width="70%"><input type="text" size="30" value="" aliagn="left" name="submiteremail"></input></td>';
	print '</tr><tr bgcolor="lightyellow"><th width="30%" align="left">Birthdate (DD-MM-YYYY HH:MM):</th>';
	print '<td width="70%"><select name="birthday">';
	print '      <option selected value=\'' . $thisDay . '\'>' . $thisDay . '</option>' . "\n";

	for ( $day = 1; $day <= $endOfMonth; $day++ )
	{
		if ( $day != $thisDay )
		{
			print ' <option value=\'';

			// add zero to days less than 10.
			if ( $day < 10 ) { print "0" . $day; } else { print $day; }
			print '\'>';

			if ( $day < 10 ) { print "0" . $day; } else { print $day; }
			print '</option>' . "\n";
		}
	}

	print '</select> - <select name="birthmonth">';
	print '      <option selected value=\'' . $thisMonth . '\'>' . $thisMonth . '</option>' . "\n";

	for ( $month = 1; $month <= $december; $month++ )
	{
		if ( $month != $thisMonth )
		{
			print ' <option value=\'';
	 
			// add zero to months less than 10.
			if ( $month < 10 ) { print "0" . $month; } else { print $month; }
			print '\'>';
	 
			if ( $month < 10 ) { print "0" . $month; } else { print $month; }
			print '</option>' . "\n";
		}
	}

	print '</select> - ' . date( Y ) . ' <select name="birthhour">';
	print '      <option selected value=\'' . $thisHour . '\'>' . $thisHour . '</option>' . "\n";
	
	for ( $hour = 0; $hour <= $maxHour; $hour++ )
	{
		if ( $hour != $thisHour )
		{
			if ( $hour < 10 ) { print ' <option value=\'0' . $hour . '\'>0' . $hour . '</option>' . "\n"; }
			else { print ' <option value=\'' . $hour . '\'>' . $hour . '</option>' . "\n"; }
		}
	}

	print '</select> : <select name="birthminute">';
	print '      <option selected value=\'' . $thisMin . '\'>' . $thisMin . '</option>' . "\n";

	for ( $minute = 0; $minute <= $maxMin; $minute++ )
	{
		if ( $minute != $thisMin ) 
		{ 
			if ( $minute < 10 ) { print ' <option value=\'0' . $minute . '\'>0' . $minute . '</option>' . "\n"; }
			else { print ' <option value=\'' . $minute . '\'>' . $minute . '</option>' . "\n"; }
		}
	}

	print '</select>hrs</td></tr><tr bgcolor="lightyellow">';
	print '<th width="30%" align="left">Sex of baby:</th><td width="70%">';
	print '<select name="birthsex"><option selected value="boy">boy</option>';
	print '<option value="girl">girl</option></select></td></tr>';
	print '<tr bgcolor="lightyellow"><th width="30%" align="left">Baby name:</th>';
	print '<td width="70%"><input type="text" size="30" value="" aliagn="left" name="babyname"></input></td></tr>';
	print '</table>';

	print '* Will not be published online, needed to contact if you win!';
	print '<!-- submit button. -->';
	print '<p><INPUT TYPE="submit" VALUE="Submit my guess" STYLE="color: yellow;background: green">';
	print '</INPUT></p></form>';
}


	
/**
 * Displays some statistics in a table.
 *
 * @param resource A mysql database connection resource.
 */
function displayStats( $connect )
{
	// some stats counters.
	$numberBoys  = 0;
	$numberGirls = 0;
	$mornings    = 0;  // 0600 - 1200
	$afternoons  = 0;  // 1200 - 1800
	$evenings    = 0;  // 1800 - 0000
	$nites       = 0;  // 0000 - 0600

	print "<h2>Some statistics:</h2>";
	print "<table width='50%' border='1'>";
	
	@mysql_select_db( DB_DATABASE, $connect ) or die( "Unable to select database");
	$guesses = "SELECT name, birthdate, birthsex FROM guesses ORDER BY birthdate;";
	$results = mysql_query( $guesses, $connect );
	
	while ( $row = mysql_fetch_array( $results ) )
	{
		// count sex.
		if ( $row['birthsex'] == 'boy' ) { $numberBoys++; } else { $numberGirls++; };

		// count time.
		list( $day, $time ) = split( " ", $row['birthdate'] );
		list( $hr, $min, $sec ) = split( ":", $time );
		if ( "06" <= $hr && $hr < "12" ) { $mornings++; }
		elseif ( "12" <= $hr && $hr < "18" ) { $afternoons++; }
		elseif ( "18" <= $hr && $hr < "24" ) { $evenings++; }
		else { $nites++; }
	}
	$totalGuesses = $numberBoys + $numberGirls;

	print "<tr bgcolor='lightyellow'><th align='left'>Morning births:</th><td>$mornings</td></tr>";
	print "<tr bgcolor='lightyellow'><th align='left'>Afternoon births:</th><td>$afternoons</td></tr>";
	print "<tr bgcolor='lightyellow'><th align='left'>Evening births:</th><td>$evenings</td></tr>";
	print "<tr bgcolor='lightyellow'><th align='left'>Night births:</th><td>$nites</td></tr>";
	print "<tr bgcolor='lightpink'><th align='left'>Number girls:</th><td>$numberGirls</td></tr>";
	print "<tr bgcolor='lightblue'><th align='left'>Number boys:</th><td>$numberBoys</td></tr>";
	print "<tr bgcolor='lightyellow'><th align='left'>Total guesses:</th><td>$totalGuesses</td></tr>";

	print "</table>";
}

/**
 * Main program.
 */
if ( $_POST['action'] == 'guess' )
{
	// check for data.
	//
	if ( strlen( $_POST['submitername'] ) > 0  && strlen( $_POST['submiteremail'] ) > 0 )
	{
		// check for dup date/sex.
		if ( duplicateEntry( $connect, $timestamp, $_POST['birthsex'] ) )
		{
			failure( "Your date and time has already been taken." );
			exit;
		}

		addGuess( $connect, $timestamp, $_POST );
	}
	else
	{
		failure( "You need to supply both a name and an email for submission." );
		exit;		
	}
	
	// mail ADMIN to notify of submission.
	$message  = 'This is a warning that a submission of a babygame guess has happened.' . "\n";
	$message .= 'The following information is submitted::' . "\n\n";
	$message .= 'Submitted by: ' . $_POST['submitername'];
	$message .= '    Email: ' . $_POST['submiteremail'];
	$message .= '    Birthdate: ' . $timestamp . "\n\n";
	$message .= '    Sex: ' . $_POST['birthsex'] . "\n";
	$message .= '    Babyname: ' . $_POST['babyname'] . "\n";
	$message .= 'See <a href="http://www.schabell.com/babygame/babygame.php" target="_new">Baby Game</a>' . "\n";

	$headers  = 'From: Babygame <info@schabell.com>' . "\r\n";
	mail( ADMIN_GAME, 'Babygame: Babygame Guess Submission', $message, $headers );
?>

	<center>
	<h2>Your guess has been submitted:</h2>

<? 
	displayOverview( $connect );
	displayStats( $connect );
	print "</center>";
	mysql_close( $connect );
}
else
{

	// setup for submitting a guess
	//
	require( 'introtext.php' );
		
	$today = date( 'Y-m-d' );
	if ( ENDSUBMITS <= $today )
	{
		print '<h4>Submissions closed, we are in overtime!</h4>';
	}
	else
	{
		displaySubmissionForm();
	}

?>
	<hr>
	<h2>Current guesses:</h2>

<?
	displayOverview( $connect );
	displayStats( $connect );
	mysql_close( $connect );
?>
	</center>
	</body>
	</html>
<?
}
?>
