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
	echo $_POST['start'];
	
	mysqli_select_db($myDB, $database);
	mysqli_query($myDB, "INSERT INTO `tblCalendar` (`evID`, `name`, `start`, `end`) VALUES
		(3, '" .$_POST['name'] ."', '".$_POST['start'] ."', '" .$_POST['end'] ."');");
	mysqli_close($myDB);
	
	//echo("Successfully initialised DB");
}
?>