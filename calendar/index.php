<!DOCTYPE html>
<html>
<head>
    <title>Open-Source JavaScript Event Calendar</title>

    <!-- head -->
    <meta charset="utf-8"/>
    <meta name="referrer" content="no-referrer-when-downgrade"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../helpers/v2/main.css?v=2022.4.438" type="text/css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet"/>
    <script src="../js/daypilot-all.min.js?v=2022.4.438"></script>

    <!-- /head -->

</head>
<body>

<!-- top -->
<template id="content" data-version="2022.4.438">

    <!-- /top -->

    <div class="note">Read more about the <a href="https://doc.daypilot.org/calendar/">JavaScript Event Calendar</a>.
    </div>

    <div id="dp"></div>

    <script type="text/javascript">
        const dp = new DayPilot.Calendar("dp", {
            viewType: "Week",
            startDate: "2022-03-21",
            headerDateFormat: "dddd",
            onEventClick: async args => {

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

            },
            onBeforeEventRender: args => {
                args.data.barBackColor = "transparent";
                if (!args.data.barColor) {
                    args.data.barColor = "#333";
                }
            },
            onTimeRangeSelected: async args => {
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
				
                dp.events.add(event);
            },
            onHeaderClick: args => {
				console.log("lol");
                console.log("args", args);
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
					
					//mysqli_select_db($myDB, $database);
					//mysqli_query($myDB, "INSERT INTO `tblCalendar` (`evID`, `name`, `start`, `end`) VALUES
					//(99, 'rxadvasde', '1999-01-01', '1999-01-02');");
					mysqli_close($myDB);
					
					//echo("Successfully initialised DB");
				}
				?>
            },
			onEventMoved: args => {
				console.log("event moved");
			}
        });
		
		dp.onEventMoved = function(args) {
		console.log("Moved: " + args.e.text());
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
			
			mysqli_query($myDB, "DROP DATABASE IF EXISTS `dbCalendar`;");
			mysqli_query($myDB, "CREATE DATABASE `dbCalendar`;");
			
			mysqli_select_db($myDB, $database);
			
			mysqli_query($myDB, "CREATE TABLE `tblCalendar` (
		  `evID` int NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `start` date NOT NULL,
		  `end` date NOT NULL,
		   PRIMARY KEY (`evID`));"
			);
			
			mysqli_query($myDB, "INSERT INTO `tblCalendar` (`evID`, `name`, `start`, `end`) VALUES
		(1, 'rave', '1999-01-01', '1999-01-02');");
		mysqli_query($myDB, "INSERT INTO `tblCalendar` (`evID`, `name`, `start`, `end`) VALUES
		(2, 'raveee', '1999-01-01', '1999-01-02');");

			mysqli_close($myDB);
			
			//echo("Successfully initialised DB");
		}
		?>
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
				$results = mysqli_query($myDB, "SELECT * FROM `tblCalendar`;");
				while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC))
				{
					//echo "start: new DayPilot.Date(\"2022-03-24T12:00:00\"),"
					//."end: new DayPilot.Date(\"2022-03-24T16:00:00\"),"
					//."id: " .$row['evID'] .","
					//."text: \"" .$row['name'] ."\","
					//."barColor: \"#cc0000\"";
					echo "{start: \"2022-03-24T12:00:00\","
					."end: \"2022-03-24T16:00:00\","
					."id: " .$row['evID'] .","
					."text: \"" .$row['name'] ."\","
					."barColor: \"#cc0000\"},";
				}
				mysqli_close($myDB);
		}
		?>];
		console.log({events});
        dp.update({events});

    </script>

    <!-- bottom -->
</template>

<script src="../helpers/v2/app.js?v=2022.4.438"></script>

<!-- /bottom -->

</body>
</html>

