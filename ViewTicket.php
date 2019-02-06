<?php // <--- do NOT put anything before this PHP tag
	
	include('functions.php');
	$cookieMessage = getCookieMessage();
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8" /> 
	<title>CineMoose - Ticket Information</title>
	<link rel="stylesheet" type="text/css" href="TheaterStyle.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<h1>Ticket Information</h1>
	<?php 

		echo "$cookieMessage";
		
		if(isset($_GET['TicketID']))
		{		
			$TicketID = $_GET['TicketID'];
			
			// connect to the database using our function (and enable errors, etc)
			$dbh = connectToDatabase();
			
			// this file should show the details for a particular ticket.
			
			// this SQL will show the details of the ticket, we use left joins in case the ticket does not have a member connected with it.
			// please not that when using left joins the order of how they are written is important.
			$statement = $dbh->prepare('
				SELECT Sessions.SessionID, SessionTime, PricePaid, SessionTime, TimeStamp, NormalPrice, RoomID, Members.MemberID, FirstName, LastName, Email
				FROM Tickets 
				INNER JOIN Sessions ON Sessions.SessionID = Tickets.SessionID
				LEFT JOIN MemberTickets ON MemberTickets.TicketID = Tickets.TicketID
				LEFT JOIN Members ON Members.MemberID = MemberTickets.MemberID
				WHERE Tickets.TicketID = ? ');
			
			$statement->bindValue(1,$TicketID );
			
			//execute the SQL.
			$statement->execute();

			// get the result, there will only ever be one Session with a given ID 
			// so we can just use an if() rather than a while()
			if($row = $statement->fetch())
			{
				$SessionID = makeOutputSafe($row['SessionID']); 
				$SessionTime = makeOutputSafe($row['SessionTime']);
				$PricePaid = makeOutputSafe($row['PricePaid']);
				$NormalPrice = makeOutputSafe($row['NormalPrice']);
				$RoomID = makeOutputSafe($row['RoomID']);
				
				// TODO convert into a formatted date using the date() function.
				$SessionTime = date("j F, Y", makeOutputSafe($row['SessionTime'] ));
				$TicketPurchaseTime = date("j F, H:i", makeOutputSafe($row['TimeStamp'] ));
				
				
				// display all the ticket information. Such as the sesionID, sessionTime, RoomID when they purchased the ticket, price paid etc.
				
				// does this ticket have a member associated with it?
				if($row['MemberID'] != null)
				{
					$FirstName = makeOutputSafe($row['FirstName']); 
					$LastName = makeOutputSafe($row['LastName']); 
					
					echo "Name: $FirstName $LastName<br>";
				}
				
				// TODO if the NormalPrice is higher than the PricePaid it means they must have had a discount applied.
				// you should display this on the page and say how much money they saved.
				echo "Ticket Number: $TicketID<br>";
				echo "Purchased: $TicketPurchaseTime<br>";
				echo "Session Number: $SessionID<br>";				
				echo "Session Time: $SessionTime<br>";
				echo "Room: $RoomID<br>";
				echo "Price: $PricePaid<br>";
				echo "<a href='Homepage.php'>Back To Home</a>";
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
	?>
</body>
</html>