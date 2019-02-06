<?php // <--- do NOT put anything before this PHP tag
	include('functions.php');
	$cookieMessage = getCookieMessage();
	
		// if the user provided a search string.
		if(isset($_GET['search']))
		{
			$searchString = $_GET['search'];
		}
		// if the user did NOT provide a search string, assume an empty string
		else
		{
			$searchString = "";
		}
		$safeSearchString = makeOutputSafe($searchString);
	
			
		// connect to the database using our function (and enable errors, etc)
		$dbh = connectToDatabase();
		
		// select all the Movies.
		$SqlSearchString = "%$searchString%";
		$statement = $dbh->prepare('SELECT MovieID, Plot, Title, Classification, Runtime, Released 
		FROM Movies WHERE Title 
		LIKE ? ORDER BY Released 
		DESC LIMIT 6 OFFSET 3;');

		$statement->bindValue(1,$SqlSearchString);
		
		

		//execute the SQL.
		$statement->execute();
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>CineMoose - Home</title>
	<link rel="stylesheet" type="text/css" href="TheaterStyle.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<header class="hero-image">	
	<?php 
		include "Navigation.inc.php";
	?>	
			
		<h1 class="hero-text">STAR WARS</h1>
		<h2 class="hero-text">THE FORCE AWAKENS</h2>
		<h3 class="hero-text">IN CINEMAS NOW!</h3>
		<a href="ViewMovie.php?MovieID=2488496" class="buy-button">Buy Tickets</a>
		<div class="welcome-message">
				<?php
				// display any cookie messages. TODO style this message so that it is noticeable.
				echo $cookieMessage;
			?>
	 	</div>
	</header>
	<section class="f-movie">
		<div class="f-movie-heading">
			<h1 class="latest-movies">Now Showing</h1>
		</div>
	<div class="f-movie-container">
		<?php 
		while($row = $statement->fetch())
		{
			// Remember that the data in the database could be untrusted data. 
			// so we need to escape the data to make sure its free of evil XSS code.
			$MovieID = makeOutputSafe($row['MovieID']); 
			$Plot = makeOutputSafe($row['Plot'] ); 
			$Title = makeOutputSafe($row['Title'] ); 
			$Classification = makeOutputSafe($row['Classification'] ); 
			$Runtime = makeOutputSafe($row['Runtime'] ); 
			$Released = date("j F, Y", makeOutputSafe($row['Released'] )); 
			
			// TODO - get other relevant columns
			
			echo "<div class='f-movie-card'>
					<a href='ViewMovie.php?MovieID=$MovieID'><img src='../IIT_Assets/MoviePosters/$MovieID.jpg' /></a>
						<div class='f-movie-card-container'>
							<a href='ViewMovie.php?MovieID=$MovieID'><h2>$Title</h2></a>
							<p class='classifruntime'><span class='movie-label label-$Classification'>$Classification</span> $Runtime mins</p>
							<p>$Plot</p>
						</div>
				</div>";			
		}
		?>
	</div>
	</section>	
</body>
</html>
