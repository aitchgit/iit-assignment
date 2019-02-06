<?php // <--- do NOT put anything before this PHP tag

// this php file will have no HTML

include('functions.php');

if(isset($_POST['MovieID'],$_POST['Day'],$_POST['Month'],$_POST['Year'],$_POST['Hour'], $_POST['Minute'], $_POST['RoomID'], $_POST['SeatsAvailable'], $_POST['NormalPrice']))
{
	$dbh = connectToDatabase();
	
	$MovieID = trim($_POST['MovieID']);	
	$Day = trim($_POST['Day']);;
	$Month = trim($_POST['Month']);;
	$Year = trim($_POST['Year']);;
	$Hour = trim($_POST['Hour']);
	$Minute = trim($_POST['Minute']);
	$RoomID = trim($_POST['RoomID']);
	$SeatsAvailable =trim($_POST['SeatsAvailable']);
	$NormalPrice = trim($_POST['NormalPrice']); 	
	
	
	if(is_numeric($NormalPrice) == false)
	{
		setCookieMessage("Invalid Price provided.");
		redirect("AddNewSession.php");	
	}
	
	if(is_numeric($SeatsAvailable) == false)
	{
		setCookieMessage("Invalid SeatsAvailable provided.");
		redirect("AddNewSession.php");	
	}
	
	
	// TODO make a timestamp using mktime. pay close attention to the order of the inputs
	// see http://php.net/manual/en/function.mktime.php
	$SessionTime = mktime($Hour,$Minute,0,$Month,$Day,$Year);
	
	if($SessionTime === false)
	{
		setCookieMessage("Invalid date provided.");
		redirect("AddNewSession.php");	
	}

	// INSERT the new session, you do not need to provide the session id, it will be automatically generated.
	$statement = $dbh->prepare("INSERT INTO 'Sessions' (MovieID, SessionTime, RoomID, SeatsAvailable, NormalPrice) VALUES (?,?,?,?,?);");
				
	// TODO bind the 5 values.
	$statement->bindValue(1,$MovieID);
	$statement->bindValue(2,$SessionTime);  
	$statement->bindValue(3,$RoomID);  
	$statement->bindValue(4,$SeatsAvailable);  
	$statement->bindValue(5,$NormalPrice); 
	$statement->execute();
	
	// get the ID of the session we just inserted.
	$NewSessionID = $dbh->lastInsertId();
	
	// redirect them to the new session page.
	setCookieMessage("New Session Added!");
	redirect("ViewSession.php?SessionID=$NewSessionID&details=1");	
	
}
else 
{
	echo "System Error: invalid data provided, do your form names match the ones in PHP?";
}