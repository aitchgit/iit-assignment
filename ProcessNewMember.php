<?php // <--- do NOT put anything before this PHP tag

// this php file will have no HTML

include('functions.php');

if(isset($_POST['UserName'],$_POST['FirstName'],$_POST['LastName'],$_POST['Email'],$_POST['PostCode']))
{
	$dbh = connectToDatabase();
	
	//TODO trim all 5 inputs, to make sure they have no extra spaces.
	$UserName = trim($_POST['UserName']);
	$PostCode = trim($_POST['PostCode']);
	$FirstName = trim($_POST['FirstName']);
	$LastName = trim($_POST['LastName']);
	$Email = trim($_POST['Email']);
	
	// just check that the user really did submit the values.	
	if($UserName === "" || $FirstName === "" || $LastName === ""  || $Email === "" || $PostCode === "" )
	{
		setCookieMessage("Please do not leave any of the fields blank");
		redirect("SignUp.php");
	}
	
	// TODO it would also be a good idea to restrict what characters username can contain.
	// use ctype_alnum() to check that the username they requested is alphanumeric, if not, return an error.
	
	if(!ctype_alnum($UserName))
	{
		setCookieMessage("Usernames must be alphanumeric");
		redirect("SignUp.php");
	}
	
	// lets check to see if the user name is taken, COLLATE NOCASE tells SQLite to do a case insensitive match.
	$statement = $dbh->prepare('SELECT * FROM Members WHERE UserName = ? COLLATE NOCASE; ');
	$statement->bindValue(1,$UserName);
	$statement->execute();
		
	// we found a match, so inform the user that they cant use the user-name.
	if($row2 = $statement->fetch(PDO::FETCH_ASSOC))
	{
		setCookieMessage("The UserName: '$UserName' is Taken by someone else :(");
		redirect("SignUp.php");
	}
	else
	{		
		// add the new customer to the Members table.
		// TODO insert the new customer and their details into the Members table.
		// NOTE: you must NOT provide the MemberID, the database will generate one for you.
		$statement2 = $dbh->prepare("INSERT INTO 'Members' (UserName, FirstName, LastName, Email, PostCode) VALUES (?,?,?,?,?);");
		
		// TODO: bind the 5 variables to the question marks. the first one is done for you.
		$statement2->bindValue(1, $UserName );
		$statement2->bindValue(2, $FirstName );
		$statement2->bindValue(3, $LastName );
		$statement2->bindValue(4, $Email );
		$statement2->bindValue(5, $PostCode );
		
		
		$statement2->execute();
		
		$SafeFirstname = makeOutputSafe($FirstName);
		setCookieMessage("Welcome $SafeFirstname!, you can now buy tickets and leave reviews!");
		redirect("Homepage.php");		
	}
}
else 
{
	echo "System Error: invalid data provided, do your form names match the ones in PHP?";
}