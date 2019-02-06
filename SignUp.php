<?php // <--- do NOT put anything before this PHP tag
	include('functions.php');
	$cookieMessage = getCookieMessage();
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8" /> 
	<title>CineMoose - Sign Up</title>
	<link rel="stylesheet" type="text/css" href="TheaterStyle.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php 
		include "Navigation.inc.php";
	?>
	
	
	<div class="signup-form-container" >
		<div class="signup-form-row">
			
				<h1>Sign Up</h1>
				<p>To gain extra perks across our website!<br/>
				<p>Includes making reviews to our movies, discounts to tickets and more!</p>
			
		</div>
	<form action = 'ProcessNewMember.php' method = 'POST'>
		<!-- 
			TODO make a sign up <form>, don't forget to use <label> tags, <fieldset> tags and placeholder text. 
			all inputs are required.
			
			Make sure you <input> tag names match the names in AddNewCustomer.php
			
			your form tag should use the POST method. don't forget to specify the action attribute.
		-->
		<div class="signup-form-row">
			<div class="signup-form-text">
				<label for="UserName">Username:</label>
			</div>
			<div class="signup-form-input">
				<input type="text" name="UserName" id="UserName" placeholder="DaddyJeff"/>
			</div>
		</div>
		<div class="signup-form-row">
			<div class="signup-form-text">
				<label for="FirstName">First Name:</label>
			</div>
			<div class="signup-form-input">
				<input type="text" name="FirstName" id="FirstName" placeholder="Jeff" />
			</div>
		</div>
		<div class="signup-form-row">
			<div class="signup-form-text">
				<label for="LastName">Last Name:</label>
			</div>
			<div class="signup-form-input">
				<input type="text" name="LastName" id="LastName" placeholder="Kaplan"/>
			</div>
		</div>		
		<div class="signup-form-row">
			<div class="signup-form-text">
				<label for="Email">E-mail:</label>
			</div>
			<div class="signup-form-input">
				<input type="text" name="Email" id="Email" placeholder="daddyjeff@owteam.com"/>
			</div>
		</div>
		<div class="signup-form-row">
			<div class="signup-form-text">
				<label for="PostCode">Postcode:</label>
			</div>
			<div class="signup-form-input">
				<input type="text" name="PostCode" id="PostCode" placeholder="3000"/>
			</div>
		</div>
		<div class="signup-form-row">
			<div class="signup-form-text">
				
			</div>
			<div class="signup-form-input">
			<?php
		// display any error messages. TODO style this message so that it is noticeable.
		echo $cookieMessage;
	?>
			</div>
		</div>
		<div class="signup-form-row">
			<div class="signup-form-text">
				
			</div>
			<div class="signup-form-input">
				<input type="submit" value="Sign Up">
				<a href="#">Log In</a>
				
			</div>
		</div>		
	</form>
	</div>
</body>
</html>