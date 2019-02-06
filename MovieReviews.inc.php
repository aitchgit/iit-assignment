<?php 
	echo "<section class='view-movie-container'>";
	echo "<h2>Reviews</h2>";
?>

<?php 
	
	// this file exists so that the code in ViewMovie.php is kept to a reasonable limit.
	
	// are we connected to the database and have they provided a MovieID?
	if(isset($dbh, $_GET['MovieID']))
	{
		if(isset($_GET['page'])) 
		{
			$currentPage = intval($_GET['page']); 
		}
		else 
		{
			$currentPage = 0; 
		}
		
		// TODO SELECT the UserName, TimeStamp, StarRating and ReviewText from the Reviews table joined to the Members table.
		// limited to 10 rows. like we did on MovieList.
		$statement3 = $dbh->prepare('SELECT UserName, Timestamp, StarRating, ReviewText
		FROM Reviews
		INNER JOIN Members ON Reviews.MemberID = Members.MemberID
		WHERE MovieID = ?
		ORDER BY Timestamp DESC
		LIMIT 5 OFFSET ?
		');
		$statement3->bindValue(1,$_GET['MovieID']);
		$statement3->bindValue(2,$currentPage * 5 );  
		
		$statement3->execute();
		
		while($row3 = $statement3->fetch())
		{
			// TODO make sure you use makeOutputSafe on all the variables to prevent XSS.
			$ReviewText = makeOutputSafe($row3['ReviewText']);
			$UserName = makeOutputSafe($row3['UserName']);
			$StarRating = makeOutputSafe($row3['StarRating']);
			$TimeStamp = date("j F, Y", makeOutputSafe($row3['TimeStamp']));
			$MovieID = $_GET['MovieID'];
			
			echo "
				<div class='view-movie-reviews-row'>					
					<div class='view-movie-reviews'>
						<h4>$UserName on $TimeStamp</h4>
						<img src='images/star-$StarRating.png'>
						<p>$ReviewText</p>
					</div>
				</div>";
			
		}
		
		// TODO provide links to the next and previous page.
		$previousPage = $currentPage - 1;
	echo "<a href='ViewMovie.php?MovieID=$MovieID&page=$previousPage'>Latest Reviews</a>";

	$nextPage = $currentPage + 1;
	echo " <a href='ViewMovie.php?MovieID=$MovieID&page=$nextPage'>Older Reviews</a>";
	echo "</section>"; 
	echo "<section class='view-movie-container'>";
	echo "<h2>Submit Review</h2>";
	echo "<div class='signup-form-row'>";
		echo "<form class = 'reviewForm' action = 'PostReview.php?MovieID=$MovieID' method = 'POST'>";
		echo "	<div class='signup-form-row'>
					<div class='signup-form-text'>
						<label for='UserName'>Username:</label>
					</div>
					<div class='signup-form-input'>
						<input type='text' name='UserName' id='UserName' placeholder='MovieBuff'/>
					</div>
				</div>"	;
		echo "	<div class='signup-form-row'>
				<div class='signup-form-text'>
					<label for='StarRating'>Rating:</label>
				</div>
				<div class='signup-form-input'>
				<select name='StarRating'>
				<option value='1'>1 Star</option>
				<option value='2'>2 Stars</option>
				<option value='3'>3 Stars</option>
				<option value='4'>4 Stars</option>
				<option value='5'>5 Stars</option>
			  </select>
				</div>
			</div>"	;
		echo "	<div class='signup-form-row'>
			<div class='signup-form-text'>
				<label for='ReviewText'>Message:</label>
			</div>
			<div class='signup-form-input'>
				<textarea type='text' name='ReviewText' id='ReviewText' /></textarea>
			</div>
		</div>
		<div class='signup-form-row'>
			<div class='signup-form-text'>				
			</div>
			<div class='signup-form-input'>
				<input type='submit' value='Submit'>				
			</div>
		</div>
		"	;			
		echo "</form>";
	}
	else 
	{
		echo "This file must be included by ViewMovie.php";
	}	
	echo "</div>";
	echo "</section>"; 
	?>
