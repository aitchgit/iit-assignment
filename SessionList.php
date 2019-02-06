<?php // <--- do NOT put anything before this PHP tag
	
	include('functions.php');
	/// get the cookieMessage, this must be done before any HTML is sent to the browser.
	$cookieMessage = getCookieMessage();	
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8" /> 
	<title>CineMoose - Session List</title>
	<link rel="stylesheet" type="text/css" href="TheaterStyle.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php 
		include "Navigation.inc.php";
	?>
	
	<section class='view-slist-container'>
		<h1>Session List</h1>
		<a href="AddNewSession.php">Add New Session</a>
		<table>
		<tr>
			<th>Session ID</th>
			<th>Start Time</th>
			<th>Room</th>
			<th>Movie</th>
			<th>Runtime</th>
			<th>Seats Available</th>
			<th>Tickets Sold</th>
			<th>Seats Remaining</th>
			<th>Normal Price</th>
			<th>Average Ticket Price</th>
			<th>Revenue</th>
			<th>Session Licence Cost</th>
			<th>Net Profit</th>
		</tr>
	<?php 
		
		echo $cookieMessage;

		
		// connect to the database using our function (and enable errors, etc)
		$dbh = connectToDatabase();
		
		// select all the Session details, see the file details document for more info.
		// Columns required:
		// SessionID, StartTime, RoomID, MovieTitle, Runtime, SeatsAvalible, TicketsSold, NormalPrice, AvgTicketPrice, Revenue, SessionLicencePercent
		$statement = $dbh->prepare('SELECT Sessions.SessionID, Movies.MovieID, SessionTime, RoomID, Title, Runtime, SeatsAvailable, COUNT(Tickets.TicketID) as TicketSold, NormalPrice, AVG(PricePaid) AS AvgTicketPrice, SUM(NormalPrice) AS Revenue, (1.1 / (1.8 + (SessionTime - Released)/604800) + 0.2) AS SessionLicencePercent 
		FROM Sessions
		LEFT JOIN Movies ON Sessions.MovieID = Movies.MovieID
		LEFT JOIN Tickets ON Sessions.SessionID = Tickets.SessionID
		GROUP BY Sessions.SessionID, SessionTime, SeatsAvailable, RoomID, NormalPrice');
		
		//execute the SQL.
		$statement->execute();

		// get the results
		while($row = $statement->fetch())
		{
			// Remember that the data in the database could be untrusted data. 
			// so we need to escape the data to make sure its free of evil XSS code.
			$SessionID = makeOutputSafe($row['SessionID']); 
			$SessionTime = date("j F, H:i", makeOutputSafe($row['SessionTime'])); 
			$RoomID = makeOutputSafe($row['RoomID']); 
			$Title = makeOutputSafe($row['Title']); 
			$Runtime = makeOutputSafe($row['Runtime']); 
			$SeatsAvailable = makeOutputSafe($row['SeatsAvailable']); 
			$TicketSold = makeOutputSafe($row['TicketSold']); 
			$NormalPrice = makeOutputSafe($row['NormalPrice']); 
			$AvgTicketPrice = makeOutputSafe($row['AvgTicketPrice']); 
			$Revenue = makeOutputSafe($row['Revenue']); 
			$SessionLicencePercent = makeOutputSafe($row['SessionLicencePercent']); 
			$SeatsRemaining = $SeatsAvailable - $TicketSold;
			$NetProfit = $Revenue - $SessionLicencePercent;			
			$MovieID = makeOutputSafe($row['MovieID']);
			

			
			// TODO - get other relevant columns.		
			$NetProfitRounded = round($NetProfit, 2);
			$AvgTicketPriceRounded = round($AvgTicketPrice, 2);


			// TODO using maths, calculate the Session Licence Cost, Net Profit, and Seats Remaining.
			
			// output the row in a TR tag, and each item should be in a TD tag.
			echo "<tr>";
			echo "<td><a class='slist-table-link' href='ViewSession.php?SessionID=$SessionID'>$SessionID</a></td>";
			echo "<td>$SessionTime</td>";
			echo "<td>$RoomID</td>";
			echo "<td><a class='slist-table-movie' href='ViewMovie.php?MovieID=$MovieID'>$Title</a></td>";
			echo "<td>$Runtime</td>";
			echo "<td>$SeatsAvailable</td>";
			echo "<td>$TicketSold </td>";
			echo "<td>$SeatsRemaining </td>";
			echo "<td>$$NormalPrice</td>";
			echo "<td>$$AvgTicketPriceRounded</td>";
			echo "<td>$$Revenue</td>";
			echo "<td>$SessionLicencePercent</td>";
			echo "<td>$$NetProfitRounded</td>";
				//TODO output TD tags with data.
			echo "</tr> \n";			
		}
	?>
	</table>
	</section>
</body>
</html>