<?php 
	
	// this file exists so that the code in ViewMovie.php is kept to a reasonable limit.
	// this file CANNOT be viewed directly, it will not work.
	// instead it needs to be included by ViewMovie.php
	
	// are we connected to the database and have they provided a movieID?
	if(isset($dbh,  $_GET['MovieID'] ))
	{
		$MovieID = $_GET['MovieID'];
		
		// find the next 20 sessions for this movie.
		// you will need to join to tickets so we can calculate the number of seats that are remaining.
		// see the appendix for more info. Only show sessions that are no more than 30 mins in the past
		$statement2 = $dbh->prepare("SELECT Sessions.SessionID, SessionTime, RoomID, NormalPrice, SeatsAvailable, COUNT(TicketID) as TicketSold
			FROM Sessions
			LEFT JOIN Tickets ON Sessions.SessionID = Tickets.SessionID
			WHERE MovieID = ?  
			GROUP BY Sessions.SessionID, SessionTime, SeatsAvailable, RoomID, NormalPrice
			ORDER BY SessionTime ASC
			LIMIT 10			
			;");
		
		$statement2->bindValue(1,$MovieID);
		/* $statement2->bindValue(2, /* TODO Time() calculation * ); */
		
		//execute the SQL.
		$statement2->execute();
		
		// now we can output the table.
		// don't forget to have a heading row on your table.
		echo "<section class='view-movie-container'>";
		echo "<h2>Session Times</h2>";

		echo "<table>";
		// TODO put a table heading here 
			echo "<tr>";
				echo "<th>Session Time</th>";				
				echo "<th>Room</th>";
				echo "<th>Price</th>";				
				echo "<th>Seats Remaining</th>";
				echo "<th></th>";
			echo "</tr>";
		while($row2 = $statement2->fetch())
		{
			$SessionID = makeOutputSafe($row2['SessionID']); 
			$SessionTime = date("j F, H:i", makeOutputSafe($row2['SessionTime']));			
			$RoomID = makeOutputSafe($row2['RoomID']); 
			$NormalPrice = makeOutputSafe($row2['NormalPrice']); 
			$SeatsAvailable = makeOutputSafe($row2['SeatsAvailable']);
			$TicketSold = makeOutputSafe($row2['TicketSold']); 
			$SeatsRemaining = $SeatsAvailable - $TicketSold;
			 
			
			// TODO output a table row with the following information
			// SessionTime, RoomID, NormalPrice, Seats remaining. clicking on SessionTime should take the user to ViewSession.php.
			// you can easily calculate the seats remaining by subtracting TicketsSold from SeatsAvailable.
			// if there are no free seats, do not provide a link to ViewSession.php
			echo "<tr>";
				echo "<td>$SessionTime</td>";				
				echo "<td>$RoomID</td>";
				echo "<td>$$NormalPrice</td>";				
				echo "<td>$SeatsRemaining</td>";
				echo "<td><a href='ViewSession.php?SessionID=$SessionID'> Buy Tickets</a></td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	else 
	{
		echo 'This file must be included by ViewMovie.php and $dbh and $ViewMovie must be defined';
	}
	echo "</section>"; 

	?>