<?php 
$host = "localhost";
$username = "root";
$passwd = "";
$database = "dbCalendar";

$myDB = mysqli_connect($host, $username, $passwd);

if (mysqli_connect_errno())
{
	echo ("Could not connect to DB");
}
else
{
	echo "Connected to DB";
	//echo "Deleting " .$_POST['oldid'];
	//echo "Creating " .$_POST['evid'];
	echo $_POST['userid'];
	
	mysqli_select_db($myDB, $database);
	
	if( isset($_POST['oldid']) ) {
		mysqli_query($myDB, "DELETE FROM `tblcalendar` WHERE `evID` = '" .$_POST['oldid'] ."';");
	}
	mysqli_query($myDB, "INSERT INTO `tblCalendar` (`evID`, `userID`, `name`, `barColor`, `start`, `end`) VALUES
		('" .$_POST['evid'] ."', '" .$_POST['userid'] ."', '" .$_POST['name'] ."', '" .$_POST['barColor'] ."', '".$_POST['start'] ."', '" .$_POST['end'] ."');");
	mysqli_close($myDB);
	
	//echo("Successfully initialised DB");
}
?>