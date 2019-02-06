<?php // <--- do NOT put anything before this PHP tag
	
	include('functions.php');
	
	// get the cookieMessage, this must be done before any HTML is sent to the browser.
	$cookieMessage = getCookieMessage();	
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8" /> 
	<title>CineMoose - Sessions</title>
	<link rel="stylesheet" type="text/css" href="TheaterStyle.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php 
		include "Navigation.inc.php";
	?>
	<section class="view-movie-container">
	<?php 
		
		// if we have any error messages echo them now. TODO style this message so that it is noticeable.
		echo "$cookieMessage";
		
		if(isset($_GET['SessionID']))
		{		
			// connect to the database using our function (and enable errors, etc)
			$dbh = connectToDatabase();
			
			// this file should show the details for a particular session. and have a button to but tickets for it.
			
			// select the Session with the specified ID, join it to the Movies table to get the Movie details.
			// you should also get the number of seats remaining. (to do that you will need to join to tickets and use count())
			$statement = $dbh->prepare('SELECT Movies.MovieID, SessionID, Title, Classification, Runtime, SessionTime, RoomID, NormalPrice, SeatsAvailable, group_concat(Genre) as GenreList
			FROM Sessions
			LEFT JOIN Movies ON Sessions.MovieID = Movies.MovieID
			INNER JOIN MovieGenre ON Movies.MovieID = MovieGenre.MovieID
			WHERE SessionID = ?
			GROUP BY Movies.MovieID, Plot, Title, Classification, Runtime ');
			
			// TODO: bind the URL parameter value here
			$statement -> bindValue(1, $_GET['SessionID']);
			


			//execute the SQL.
			$statement->execute();

			// get the result, there will only ever be one Session with a given ID 
			// so we can just use an if() rather than a while()
			if($row = $statement->fetch())
			{
				$MovieID = makeOutputSafe($row['MovieID']);
				$SessionID = makeOutputSafe($row['SessionID']);
				$Title = makeOutputSafe($row['Title'] );
				$Classification = makeOutputSafe($row['Classification'] ); 
				$Runtime = makeOutputSafe($row['Runtime'] ); 
				$SessionTime = makeOutputSafe($row['SessionTime']);
				$RoomID = makeOutputSafe($row['RoomID']);
				$NormalPrice = makeOutputSafe($row['NormalPrice']);
				$SeatsAvailable = makeOutputSafe($row['SeatsAvailable']);				
				$GenreList = makeOutputSafe($row['GenreList']);
				
				// TODO display the session details here.
				
				echo "
				<div class='view-movie-row'>
					<div class='view-movie-section-image'>
						<img src='../IIT_Assets/MoviePosters/$MovieID.jpg' />
					</div>
					<div class='view-movie-section-info-vsfix'>						
						<h2>Session Information</h2>
						<p>Session ID: $SessionID</p>
						<h3>$Title</h3>
							<p class='classifruntime'><span class='movie-label label-$Classification'>$Classification</span> $Runtime mins</p>
							<p>Genre: $GenreList</p>
							<p>Seats Remaining: $SeatsAvailable</p>
							<p>Room: $RoomID</p>
							
							
					";

				// TODO don't allow people to buy tickets to sessions that are in the past.
				// however we will allow up to 30 minuets of lateness. remember that the timestamps are in seconds.
				
				if( strtotime($row['SessionTime']) > strtotime("-30 minutes") /* TODO check if the session started more than 30 mins in the past */ )
				{
					echo "Sorry, the session has closed and is no longer accepting purchases. pleases select a different session time.";
				}
				elseif( $row['SeatsAvailable'] = 0 /* TODO check if the session is full */ )
				{
					echo "Sorry, this session is full. pleases select a different session time.";
				}
				else 
				{
					echo "<form action = 'BuyTicket.php?SessionID=$SessionID' method = 'POST'>";
						// TODO ECHO YOUR INPUT TAGS HERE, you should have a text box for the username and a submit button 
						echo "<label for='UserName'>Username:</label><br>";
						echo "<input type='text' name='UserName' id='UserName' /><br>";
						echo "<input type='submit' value='Buy Now'>";
						echo "<p>Receive 10&#37; off your ticket now&#33;</p>";
						echo "<p>Don't have an account&#63; Sign Up Now&#33;</p>";
					echo "</form>";
				}
				
				// TODO if the URL contains "details=1" display a table showing the tickets for this session.
				if(isset($_GET['details']) && $_GET['details'] == "1")
				{
					// TODO display a table of all the tickets for this session. ONLY IF details=1
					// This table should include the ticketID, the time it was purchased, price paid, and the details of the person who purchaed the ticket.
					// clicking the ticketID should take me to ViewTicket.php. this table must show all tickets for this session even if they are not bought by members.
					// So you will need to use 3 tables, Tickets, MemberTickets and Members. HINT: the SQL in ViewTicket.php is very similar to what you need here.
					
					$statement2 = $dbh->prepare('  -- TODO PUT YOUR SQL SELECT HERE   -- ');
					// TODO bind the SessionID here.
					$statement2->execute();
					
					//TODO echo a table tag.
					
					while($row2 = $statement2->fetch())
					{
						// TODO output TRs and TDs for the tickets table. notice that we are using $row2 so we don't conflict with $row
					}
					
					// TODO close the table tag.
				}
				
				
			}
			else
			{
				echo "Unknown SessionID";
			}
		}
		else
		{
			echo "No SessionID provided!";
		}
		echo "</div>
		</div>";
	?>
	</section>
</body>
</html>