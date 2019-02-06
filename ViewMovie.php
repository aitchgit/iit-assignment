<?php // <--- do NOT put anything before this PHP tag
	include('functions.php');
	$cookieMessage = getCookieMessage();		
?>
<!doctype html>
<html>
<head> 
<meta charset="UTF-8" /> 
	<title>CineMoose - Viewing Movie</title>
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
		
		// include some functions from another file.
		if(isset($_GET['MovieID']))
		{		
			// connect to the database using our function (and enable errors, etc)
			$dbh = connectToDatabase();
			
			// select the movie with the specified ID.
			$statement = $dbh->prepare('SELECT Movies.MovieID, Title, Plot, Classification, Runtime, Released, group_concat(Genre) as GenreList 
			FROM Movies 
			INNER JOIN MovieGenre ON ? = MovieGenre.MovieID 
			WHERE Movies.MovieID = ?');			
			
			// TODO: bind the value here
			$statement -> bindValue(1, $_GET['MovieID']);
			$statement -> bindValue(2, $_GET['MovieID']);

			//execute the SQL.
			$statement->execute();

			// get the result, there will only ever be one Movie with a given ID (because MovieIDs must be unique as they are primary keys)
			// so we can just use an if() rather than a while()
			
			
			if($row = $statement->fetch())
			{
				// display the details here.
				$MovieID = makeOutputSafe($row['MovieID']); 
				$Plot = makeOutputSafe($row['Plot'] ); 
				$Title = makeOutputSafe($row['Title'] ); 
				$GenreList = makeOutputSafe($row['GenreList']);
				$Classification = makeOutputSafe($row['Classification'] ); 
				$Runtime = makeOutputSafe($row['Runtime'] ); 
				$Released = date("j F, Y", makeOutputSafe($row['Released'] )); 
				
				echo "
				<div class='view-movie-row'>
					<div class='view-movie-section-image'>
						<img src='../IIT_Assets/MoviePosters/$MovieID.jpg' />
					</div>
					<div class='view-movie-section-info'>
						<h2>$Title</h2>
							<p class='classifruntime'><span class='movie-label label-$Classification'>$Classification</span> $Runtime mins</p>
							<p>Genre: $GenreList</p>
							<p>$Plot</p>
							<a href='MovieList.php'>Go Back</a>
					</div>
				</div>";

			}
			else
			{
				echo "Unknown MovieID";
			}
		}
		else
		{
			echo "No MovieID provided!";
		}

	?>
	</section>
	<?php 
		
		include "MovieSessions.inc.php";
		include "MovieReviews.inc.php";
	?>
	
</body>
</html>