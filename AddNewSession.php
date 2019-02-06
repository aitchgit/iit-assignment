<?php // <--- do NOT put anything before this PHP tag
	
	include('functions.php');

	// get the cookieMessage, this must be done before any HTML is sent to the browser.
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
		<?php echo $cookieMessage; ?>
		<section class='view-movie-container'>
			<h2>Add New Session</h2>
				<form action='ProcessNewSession.php' method='POST'>
				<div class='signup-form-row'>
							<div class='signup-form-text'>
								<label for='MovieID'>Movie:</label>
							</div>	
							<div class='signup-form-input'>
							<select size='15' name='MovieID' id='MovieID'>				
					<!-- TODO put a select tag around all of this PHP code. set the size attribute to 20 -->
					<?php 
						$dbh = connectToDatabase();
						
						// Select all the moviesIDs and Titles from the movies table.
						$statement = $dbh->prepare('SELECT MovieID, Title FROM Movies  -- TODO PUT YOUR SQL HERE   -- ');
						
						//execute the SQL.
						$statement->execute();
						
						

						// get the results
						while($row = $statement->fetch())
						{
							// what you need to do is echo an <option> tag for each movie.
							// hint: the value attribute of the option tag should be the MovieID.
							$MovieID = makeOutputSafe($row['MovieID']);
							$Title = makeOutputSafe($row['Title']);
							echo "<option value='$MovieID'>$Title</option>";
						}
					?>
					<!-- TODO close the select tag. -->
					</select>
					
					<!-- TODO put a select tag for the roomID. -->
					<div class='signup-form-row'>
						<div class='signup-form-text'>
							<label for='RoomID'>Room:</label>
						</div>
						<div class='signup-form-input'>
						<select name='RoomID' id='RoomID'>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
					  </select>
						</div>
					</div>
					<!-- TODO put an input tag for the SeatsAvailable. -->
					<div class='signup-form-row'>
						<div class='signup-form-text'>
							<label for='SeatsAvailable'>Seats Available:</label>
						</div>
						<div class='signup-form-input'>
							<input type='number' name='SeatsAvailable' min='0'/>
						</div>
					</div>
					<!-- TODO put some <select> tags for the day, month, year, hour, and minute -->
					<div class='signup-form-row'>
						<div class='signup-form-text'>
							<label>Date:</label>
						</div>
						<div class='signup-form-input'>
							<input type='number' name='Day' min='1' max='31' placeholder='Day' id='Day'/>
							<select name='Month' id='Month'>
							<option value='1'>January</option>
							<option value='2'>February</option>
							<option value='3'>March</option>
							<option value='4'>April</option>
							<option value='5'>May</option>
							<option value='6'>June</option>
							<option value='7'>July</option>
							<option value='8'>August</option>
							<option value='9'>September</option>
							<option value='10'>October</option>
							<option value='11'>November</option>
							<option value='12'>December</option>
						  </select>
							<input type='number' name='Year' min='2017' max='2050' placeholder='Year' id='Year'/>
							<input type='number' name='Hour' min='1' max='24' placeholder='Hour' id='Hour'/>
							<input type='number' name='Minute' min='0' max='59' placeholder='Minute' id='Minute'/>
							
						</div>
					</div>
					<div class='signup-form-row'>
						<div class='signup-form-text'>
							<label for='NormalPrice' >Price:</label>
						</div>
						<div class='signup-form-input'>
							<input type='number' name='NormalPrice' step='0.5' value='0.00' id='NormalPrice'/>
						</div>
					</div>
					
					<div class='signup-form-row'>
					<div class='signup-form-text'>				
					</div>
					<div class='signup-form-input'>
						<input type='submit' value='Submit'>				
					</div>
				</div>

				</form>
		</section>
	</body>
</html>
