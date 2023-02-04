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
	echo $_POST['evid'];
	mysqli_select_db($myDB, $database);
	mysqli_query($myDB, "DELETE FROM `tblcalendar` WHERE `evID` = '" .$_POST['evid'] ."';");
	mysqli_close($myDB);
	
	//echo("Successfully initialised DB");
}
?>