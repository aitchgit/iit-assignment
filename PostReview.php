<?php // <--- do NOT put anything before this PHP tag
	
	// this page will not be seen by the user and MUST NOT contain any HTML or CSS.
	// if the review is posted successfully redirect them to ViewMovie.php 
	include('functions.php');
	$dbh = connectToDatabase();
	
	// Did the user provide a UserName and other info via POST?
	if(isset($_POST['ReviewText'], $_POST['UserName'], $_POST['StarRating'], $_GET['MovieID']))
	{
		$MovieID = $_GET['MovieID']; // this is not a typo.
		
		// TODO get the other values: ReviewText and StarRating.
		$UserName = trim($_POST['UserName']);
		$ReviewText = trim($_POST['ReviewText']); 
		$StarRating = trim($_POST['StarRating']);
		$TimeStamp = time();
		
		// lets check to see if the user exists in the database.
		$statement = $dbh->prepare('SELECT MemberID FROM Members WHERE UserName = ? COLLATE NOCASE; ');
		$statement->bindValue(1,  $UserName  );
		$statement->execute();
			
		// we found a match, we should allow the post.
		if($row = $statement->fetch(PDO::FETCH_ASSOC))
		{
			$MemberID = $row['MemberID'];
			
			// TODO Insert the ReviewText, MemberID, StarRating, and TimeStamp into the Reviews table.
			$statement = $dbh->prepare("INSERT INTO 'Reviews' (ReviewText, MemberID, StarRating, TimeStamp, MovieID) VALUES (?,?,?,?,?);");
			$statement->bindValue(1,$ReviewText);  
			$statement->bindValue(2,$MemberID);  
			$statement->bindValue(3,$StarRating);  
			$statement->bindValue(4,$TimeStamp);  
			$statement->bindValue(5,$MovieID); 
			$statement->execute();
			
			// get the ID of the review.
			$ReviewID = $dbh->lastInsertId();
			
			setCookieMessage("Your review has been posted.");

			redirect("ViewMovie.php?MovieID=$MovieID");
		}
		else 
		{
			// if the user name was not found in the database send them back to the view movie page.
			setCookieMessage("Bad UserName, Please try again, if you do not have an account please sign up.");
			redirect("ViewMovie.php?MovieID=$MovieID");
		}
	}
	else 
	{
		echo "System Error: incorrect data.";
	}