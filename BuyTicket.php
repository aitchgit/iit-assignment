<?php // <--- do NOT put anything before this PHP tag
	
	/*
		This file has been completely implemented for you.
		
		- This file deals with what happens after the user clicks the 'Buy Ticket' button.
		The system needs to perform a number of steps:
		1) check the username they entered is valid, we can do this by trying to find them in the members table
		2) check that the session has tickets available to buy, and also fetch the normal Price of the session.
		3) add a new ticket to the tickets table
		4) add a new ticket to the MemberTickets table.
		5) redirect them to the ViewTicket page if it was a success or to ViewSession.php if it was not.
		
		If the username is not valid, instead of just displaying the message on the page, set a special message cookie.
		then redirect them back to ViewSession.php and we will handle the message cookie there.
		
		If the purchase is a success redirect them to ViewTicket.php with a cookie message.
		
		This file has 4 separate SQL statements, don't let that confuse you.
		
	*/
	
	include('functions.php');
	
	
	// did the user provide a UserName via POST? (empty will return true if the varible does not exist or if the vairble contains an empty string)
	if(empty($_POST['UserName']))
	{
		echo "System Error: UserName was not provided, did you setup the form on ViewSession.php correctly?";
	}
	elseif(empty($_GET['SessionID']))
	{
		echo "System Error: SessionID not provided in URL, for example BuyTicket.php?SessionID=1234";
	}
	else
	{
		$UserName = trim($_POST['UserName']);
		$SessionID = trim($_GET['SessionID']);// this is not a typo
		
		
		$dbh = connectToDatabase();
		
		// start a database transaction.
		// a transaction in a database is almost like locking the data so that if two people tried to buy the last ticket we don't have a conflict.
		// data is not actually saved until commit is called.
		$dbh->beginTransaction();
		
		// first check the user name is valid, if so fetch their MemberID
		// COLLATE NOCASE tells SQLite to do a case insensitive match.
		$statement1 = $dbh->prepare('SELECT MemberID FROM Members WHERE UserName = ? COLLATE NOCASE; ');
		$statement1->bindValue(1,$UserName);
		$statement1->execute();
		
		// did we find a match??
		if($row = $statement1->fetch())
		{
			// get their MemberID Available
			$MemberID = $row['MemberID'];
			
			// now check that the session they requested exists and find how many seats are sold.
			$statement2 = $dbh->prepare('
				SELECT Sessions.SessionID, NormalPrice, SessionTime, SeatsAvailable, COUNT(TicketID) as TicketsSold 
				FROM Sessions 
				LEFT JOIN Tickets ON Sessions.SessionID = Tickets.SessionID  
				WHERE Sessions.SessionID = ?
				GROUP BY Sessions.SessionID, NormalPrice, SessionTime');
			$statement2->bindValue(1, $SessionID);
			$statement2->execute();	
			
			// did we find a match??
			if($row2 = $statement2->fetch())
			{			
				// so we know that the session is real, but should we sell the ticket?
				$NormalPrice = $row2['NormalPrice'];
				$SessionTime = $row2['SessionTime'];
				$SeatsAvailable = $row2['SeatsAvailable'];
				$TicketsSold = $row2['TicketsSold'];
				
				// we will allow a ticket to be purchased up to 30 minuets into the session.
				if($SessionTime <  ( time() - 30*60))
				{
					setCookieMessage("Sorry, the session has closed and is no longer accepting purchases. pleases select a different session time.");
					redirect("ViewSession.php?SessionID=$SessionID");
				}
				
				if($TicketsSold >= $SeatsAvailable)
				{
					setCookieMessage("Sorry, This session is full, please pick another session.");
					redirect("ViewSession.php?SessionID=$SessionID");
				}
				
				// on-line purchases get 10% off.
				$PricePaid = round($NormalPrice * 0.9, 2);
			
				// now we want to add a Ticket to the Tickets table.
				// NOTE: that we do NOT specify the ticketID, it is automatically generated for us.
				$statement3 = $dbh->prepare('INSERT INTO Tickets (TimeStamp, SessionID, PricePaid) VALUES (?,?,?); ');
				$statement3->bindValue(1,time());
				$statement3->bindValue(2,$SessionID);
				$statement3->bindValue(3,$PricePaid);
				$statement3->execute();			
				
				// get the TicketID of the ticket we just added to the database.
				$TicketID = $dbh->lastInsertId();
				
				// also add a row to the member tickets table.
				$statement4 = $dbh->prepare('INSERT INTO MemberTickets (MemberID, TicketID, OnlinePurchase) VALUES (?,?,1); ');
				$statement4->bindValue(1,$MemberID);
				$statement4->bindValue(2,$TicketID);
				$statement4->execute();
				
				// confirm the changes to the database.
				$dbh->commit();
				
				setCookieMessage("Order Success!!");
				redirect("ViewTicket.php?TicketID=$TicketID");
			
			}
			else 
			{
				echo "System Error: the specified SessionID does not exist";
			}
		}
		else 
		{
			// send them back to ViewSession.php
			setCookieMessage("User name Not found!");
			redirect("ViewSession.php?SessionID=$SessionID");
		}	
	}
