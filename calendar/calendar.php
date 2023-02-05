<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Open-Source JavaScript Event Calendar</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <!-- head -->
    <meta charset="utf-8"/>
    <meta name="referrer" content="no-referrer-when-downgrade"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../helpers/v2/main.css?v=2022.4.438" type="text/css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet"/>
    <script src="../js/daypilot-all.min.js?v=2022.4.438"></script>

    <!-- /head -->

	<style>
	        a.navi-button {
            width: 120px;
            padding: 10px 10px;
            text-align: center;
            display: inline-block;
            background-color: #3495CF;
            color: white;
            text-decoration: none;
            box-sizing: border-box;
        }
	</style>
</head>
<body>

<!-- top -->
<template id="content" data-version="2022.4.438">

    <!-- /top -->
    <div class="note">Read more about the <a href="https://doc.daypilot.org/calendar/">JavaScript Event Calendar</a>.
    </div>
	
	<a href="server/logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
	
	<div id="dp"></div>
	
	 <div class="space" style="text-align: center">
        <a href="javascript:dp.startDate = dp.startDate.addDays(-7); dp.update();" class="navi-button">Previous Week</a>
        <a href="javascript:dp.startDate = dp.startDate.addDays(-1); dp.update();" class="navi-button">Previous</a>
        <a href="javascript:dp.startDate = dp.startDate = new DayPilot.Date.now(); dp.update();" class="navi-button">Today</a>
        <a href="javascript:dp.startDate = dp.startDate.addDays(1); dp.update();" class="navi-button">Next</a>
        <a href="javascript:dp.startDate = dp.startDate.addDays(7); dp.update();" class="navi-button">Next Week</a>
    </div>
	


    <script type="text/javascript">
	
	var uid = <?php
		echo $_SESSION['uid'];
	?>;
	var dp = new DayPilot.Calendar("dp");
	$(document).ready(function() {
		function updateEventDB(id, text, color, start, end, oldID = "") {
				$.ajax({
				type: "POST",
				url: 'server/eventadd.php',
				dataType: 'text',
				data: {evid: id, userid: uid, name: text, barColor: color, start: start.toString("yyyy-MM-dd HH:mm:ss"), end: end.toString("yyyy-MM-dd HH:mm:ss"), oldid: oldID},

				success: function (obj, textstatus) {
							  console.log(obj);
						},
				error: function (jqXHR, exception) {
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n' + jqXHR.responseText;
				}
				console.log(msg);
				}});
		}
		
        
            dp.viewType = "Days",
			dp.days = "7",
            dp.startDate = new DayPilot.Date.now(),
            dp.headerDateFormat ="yyyy-MM-dd",
            dp.onEventClick = async args => {

                const colors = [
                    {name: "Blue", id: "#3c78d8"},
                    {name: "Green", id: "#6aa84f"},
                    {name: "Yellow", id: "#f1c232"},
                    {name: "Red", id: "#cc0000"},
                ];

                const form = [
                    {name: "Text", id: "text"},
                    {name: "Start", id: "start", type: "datetime"},
                    {name: "End", id: "end", type: "datetime"},
                    {name: "Color", id: "barColor", type: "select", options: colors},
                ];

                const modal = await DayPilot.Modal.form(form, args.e.data);

                if (modal.canceled) {
                    return;
                }

                dp.events.update(modal.result);
				console.log(modal.result);
				updateEventDB(modal.result.id, modal.result.text, modal.result.barColor, modal.result.start, modal.result.end, args.e.cache.id);
            },
            dp.onBeforeEventRender = args => {
                args.data.barBackColor = "transparent";
                if (!args.data.barColor) {
                    args.data.barColor = "#333";
                }
            },
            dp.onTimeRangeSelected = async args => {
				const form = [
                    {name: "Name", id: "text"}
                ];

                const data = {
                    text: "Event"
                };

                const modal = await DayPilot.Modal.form(form, data);

                dp.clearSelection();

                if (modal.canceled) {
                    return;
                }
				
				const event = {
                    start: args.start,
                    end: args.end,
                    id: DayPilot.guid(),
                    text: modal.result.text,
                    barColor: "#3c78d8"
                };
				
				console.log(args.end.toString());
				
                dp.events.add(event);
				
				updateEventDB(event.id, event.text, event.barColor, args.start, args.end);
            },
            onHeaderClick = args => {
				console.log("lol");
                console.log("args", args);
            }
        
		
		dp.onEventMoved = function(args) {
		console.log(args);
		console.log("Moved: " + args.e.cache.id);
		<?php 
		$host = "localhost";
		$username = "root";
		$passwd = "";
		$database = "dbCalendar";

		$myDB = mysqli_connect($host, $username, $passwd);

		if (mysqli_connect_errno())
		{
			;//console.log("Could not connect to DB");
		}
		else
		{
			//console.log("Successfully connected to DB");
			
			//mysqli_query($myDB, "DROP DATABASE IF EXISTS `dbCalendar`;");
			mysqli_query($myDB, "CREATE DATABASE IF NOT EXISTS `dbCalendar`;");
			
			mysqli_select_db($myDB, $database);
			
			mysqli_query($myDB, "CREATE TABLE IF NOT EXISTS `tblCalendar` (
		  `evID` varchar(255) NOT NULL,
		  `userID` varchar(255) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `barColor` varchar(16) NOT NULL,
		  `start` datetime NOT NULL,
		  `end` datetime NOT NULL,
		   PRIMARY KEY (`evID`));"
			);
			
		//	mysqli_query($myDB, "INSERT INTO `tblCalendar` (`evID`, `name`, `start`, `end`) VALUES
		//(1, 'rave', '1999-01-01 01:05:03', '1999-01-02');");
		//mysqli_query($myDB, "INSERT INTO `tblCalendar` (`evID`, `name`, `start`, `end`) VALUES
		//(2, 'raveee', '1999-01-01', '1999-01-02');");

			mysqli_close($myDB);
			
			//echo("Successfully initialised DB");
		}
		?>
		console.log(args);
		updateEventDB(args.e.cache.id, args.e.cache.text, args.e.cache.barColor, args.newStart, args.newEnd, args.e.cache.id);
		};
		
		
		dp.eventDeleteHandling = "Update";
		dp.onEventDelete = function (e) {
			console.log(e.e.cache.id);
				
								$.ajax({
				type: "POST",
				url: 'server/eventremove.php',
				dataType: 'text',
				data: {evid: e.e.cache.id},

				success: function (obj, textstatus) {
							  console.log(obj);
						},
				error: function (jqXHR, exception) {
				console.log(jqHXR.status);
				}});
		};
		
		dp.onEventResize = function (args) {
			updateEventDB(DayPilot.guid().toString(), args.e.cache.text, args.e.cache.barColor, args.newStart, args.newEnd, args.e.cache.id);
		};
        dp.init();
		
		const events = [<?php 
			$host = "localhost";
			$username = "root";
			$passwd = "";
			$database = "dbCalendar";

			$myDB = mysqli_connect($host, $username, $passwd);

			if (mysqli_connect_errno())
			{
				;//console.log("Could not connect to DB");
			}
			else
			{
				mysqli_select_db($myDB, $database);
				$results = mysqli_query($myDB, "SELECT * FROM `tblCalendar` WHERE userID = '" . $_SESSION['uid'] . "';");
				//$results = mysqli_query($myDB, "SELECT * FROM `tblCalendar`;");
				while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC))
				{
					echo "{start: \"" .$row['start'] ."\","
					."end: \"" .$row['end'] ."\","
					."id: \"" .$row['evID'] ."\","
					."text: \"" .$row['name'] ."\","
					."barColor: \"" .$row['barColor'] ."\"},";
				}
				mysqli_close($myDB);
		}
		?>];
		console.log(events);
        dp.update({events});
	});
    </script>

    <!-- bottom -->
</template>

<script src="../helpers/v2/app.js?v=2022.4.438"></script>

<!-- /bottom -->

</body>
</html>

