<?php // <--- do NOT put anything before this PHP tag
	include('functions.php');
	$cookieMessage = getCookieMessage();		
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8" /> 
	<title>CineMoose - Movies</title>
	<link rel="stylesheet" type="text/css" href="TheaterStyle.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	
	<?php 
		include "Navigation.inc.php";
	?>
	<section class="f-movie">
		<div class="f-movie-heading">
			<h1 class="latest-movies">Movie List</h1>
		</div>
	<div class="f-movie-container">
	<?php 
	// connect to the database using our function (and enable errors, etc)
	$dbh = connectToDatabase();
		
	// select all the Movies.
	$SqlSearchString = "%$searchString%";
	$statement = $dbh->prepare('SELECT Movies.MovieID, Plot, Title, Classification, Runtime, Released, group_concat(Genre) as GenreList, EarliestSessionTime 
	FROM Movies
	INNER JOIN MovieGenre ON Movies.MovieID = MovieGenre.MovieID 
	INNER JOIN UpcommingMovies ON Movies.MovieID = UpcommingMovies.MovieID  
	WHERE Title LIKE ? OR Plot LIKE ? 
	GROUP BY Movies.MovieID, Plot, Title, Classification, Runtime 
	ORDER BY Released DESC, NoSessionScheduled, EarliestSessionTime
	LIMIT 12 OFFSET ?;');

	$statement->bindValue(1,$SqlSearchString);
	$statement->bindValue(2,$SqlSearchString);
	
	// Creating pages
	if(isset($_GET['page']))
	{
		$currentPage = intval($_GET['page']);
	}
	else {
		$currentPage = 0;
	}

	$statement->bindValue(3, $currentPage * 12);

	//execute the SQL.
	$statement->execute();
		// get the results
		while($row = $statement->fetch())
		{
			// Remember that the data in the database could be untrusted data. 
			// so we need to escape the data to make sure its free of evil XSS code.
			$MovieID = makeOutputSafe($row['MovieID']); 
			$Plot = makeOutputSafe($row['Plot'] ); 
			$Title = makeOutputSafe($row['Title'] ); 
			$GenreList = makeOutputSafe($row['GenreList']);
			$Classification = makeOutputSafe($row['Classification'] ); 
			$Runtime = makeOutputSafe($row['Runtime'] ); 
			$Released = date("j F, Y", makeOutputSafe($row['Released'] )); 
			
			// TODO - get other relevant columns
			

			// output the data in a div with a class of 'movieBox' so we can apply css to this class.
			echo "<div class='f-movie-card'>
			<a href='ViewMovie.php?MovieID=$MovieID'><img src='../IIT_Assets/MoviePosters/$MovieID.jpg' /></a>
				<div class='f-movie-card-container'>
				<a href='ViewMovie.php?MovieID=$MovieID'><h2>$Title</h2></a>
					<p class='classifruntime'><span class='movie-label label-$Classification'>$Classification</span> $Runtime mins</p>
					<p>$GenreList</p>
					<p>$Plot</p>
				</div>
		</div>";			
		}
		echo "</div>";
		echo "<div class='movie-current-page-container'>";
		echo "<div class='movie-current-page'>";

		$previousPage = $currentPage - 1;
		echo "<a href='MovieList.php?page=$previousPage&search=$safeSearchString'>Previous Page</a>";

		$nextPage = $currentPage + 1;
		echo "<a href='MovieList.php?page=$nextPage&search=$safeSearchString'>Next Page</a>";

		echo "</div>";
		echo "</div>";
	?>
	
	</section>
</body>
</html>