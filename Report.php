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
		$statement = $dbh->prepare('SELECT MovieID, Plot, Title, Classification, Runtime, Released FROM Movies WHERE Title LIKE ? ORDER BY Released DESC LIMIT 6 OFFSET ?;');

		$statement->bindValue(1,$SqlSearchString);
		
		// Creating pages
		if(isset($_GET['page']))
		{
			$currentPage = intval($_GET['page']);
		}
		else {
			$currentPage = 0;
		}

		$statement->bindValue(2, $currentPage * 10);

		//execute the SQL.
		$statement->execute();
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>CineMoose - Report</title>
	<link rel="stylesheet" type="text/css" href="TheaterStyle.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>	
<?php 
		include "Navigation.inc.php";
	?>
		
	<section class="questions">
		<div class="questions-heading-container">
			<h1 class="questions-heading">Movie Report</h1>
		</div>
	<div class="questions-container">

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 1</h2>					
					<p>HTML stands for Hyper Text Markup Language, it's the standard language for creating web pages. It's the structure of every web page, which creates the building blocks needed for each web page. 
					</p>
					<a href="https://www.w3schools.com/html/html_intro.asp" target="_blank">Reference</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 2</h2>					
					<p>CSS stands for Cascading Style Sheets, it makes HTML more fancy by styling the web pages, including the design, layout and for different devices and screen sizes. </p>
					<a href="https://www.w3schools.com/css/css_intro.asp" target="_blank">Reference</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 3</h2>					
					<p>Hotlinking or Hyperlinking is linking a page or image to another page. You can hotlink a standard &lt;img&gt; or &lt;a&gt; and the user can click on the &lt;img&gt; or &lt;a&gt; tags if they want to. Hotlinking isn't a bad thing, only should be used for a user friendly experience.</p>
					<a href="#">Reference</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 4</h2>					
					<p>PHP is an acronym for "PHP: Hypertext Preprocessor". It is used to generate dynamic content, to make HTML even more cool. It can create, read, write, delte and close files on the server. Collect form data and use it for functions to the webpage or server.</p>
					<a href="https://www.w3schools.com/php/php_intro.asp" target="_blank">Reference</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 5</h2>					
					<p>HTML and PHP relate to each other due to the nature of how PHP works. As it can contain HTML markup within the php file and generate dynamic content on top of what HTML files can already achieve. Also when the php page is generated on the browser, in the source file it shows only HTML markup and no php scripts at all.</p>
					<a href="https://www.w3schools.com/php/php_intro.asp" target="_blank">Reference</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 6</h2>					
					<p>GET and POST requests are both methods in sending data from forms. GET requests can be cached and remain in the browser history meaning it can be bookmarked. A good example of this, is bookmarking a item from a online store. GET requests should never be used to deal with "sensitive" data, where as POST can be used to store "sensitive" data. As POST requests do not cache it's data in the url and only sends it to the server.</p>
					<a href="https://www.w3schools.com/tags/ref_httpmethods.asp" target="_blank">Reference</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 7</h2>					
					<p>Trusted data is the programs code or data the developer has placed into the database, webpage and scripts. You can have trusted user data, you will have to have it parsed through a validation process through the programs code. Untrusted data is data outside of the programs code or database, which is input from a user. Sometimes it can be malicious data which could cause a lot of harm to the program and database.</p>
					<a href="#">Reference - Web Security Slide from Week 8</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 8</h2>					
					<p>SQL Injection is a name for someone being able to cause harm to your database and website. This is occurs from when user input on a input tag on the webpage. An example of this is when you want a user to log into the website, someone who knows how to hack into the database via SQL Injection. One of the techniques is typing " ' OR 1=1 -- " into the username input, seems the database does what it's told. Reads the input from the hacker and it's a valid command it will grant the hacker administrator access because 1=1 means the administrator account on the database.</p>
					<a href="https://www.w3schools.com/sql/sql_injection.asp" target="_blank">Reference - w3schools and Web Security Slide from Week 8</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 9</h2>					
					<p>The website should be considered "safe" from SQL Injection because I'm using prepared statements within each input form on the website. Which should send any untrusted data to the database to interpret and execute if the data provided in the forms is correct.</p>
					<a href="#">Reference - Web Security Slide from Week 8 </a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 10</h2>					
					<p>XSS (Cross site scripting) is when a malicious can use code from forms. Which then is printed on the website and can cause damage to the page. The common practice of this type of attack is through a comment section, which can have html code within the comments or even JavaScript which will do even more harm to anyone who access the webpage.</p>
					<a href="#">Reference - Web Security Slide from Week 8</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 11</h2>					
					<p>The website should be considered "safe" from XSS because any untrusted data is filtered through "htmlspecialchars()" before being displayed on the website and neutralize any threat.</p>
					<a href="#">Reference - Web Security Slide from Week 8</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 12</h2>					
					<p>Cookies is data that is stored within a web browser, it's issued by the HTTP from any website. It's used to do a number of things on a website, being able to log in to a website, add items to a shopping cart and store website preferences the user would like to see.</p>
					<a href="#">Reference - PHP Part 3 Slide from Week 7</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 13</h2>					
					<p>Cookies aren't bad, but they are considered untrusted data as they can be tampered with. So never store any sensitive information such as username or password within the cookie. If you disable cookies a website, it won't be able to save your preferences and streamline the log in process.</p>
					<a href="#">Reference - PHP Part 3 Slide from Week 7</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 14</h2>					
					<p>There isn't any real limitations on how much data a website needs, but it's recommended that the file size should be relatively small, as the user doesn't want to load a 50mb page everytime they access the website. Which caused performance issues for clients who have a low bandwidth connection and data limits.</p>
					<a href="#" target="_blank">Reference</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 15</h2>					
					<p>HTTP response status codes indicate whether a specific HTTP request has been successfully completed. Responses are grouped in five classes. Status codes are defined by section 10 of RFC 2616.</p>
					<a href="#" target="_blank">Reference</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 16</h2>					
					<p><b>200 Ok</b> - The request has succeeded. Meaning the webpage will be loaded or request via script.<br><b>302 Found</b> - This response code means that the URI of requested resource has been changed temporarily.<br><b>400 Bad Request</b> - This response means that server could not understand the request due to invalid syntax.<br><b>403 Forbidden</b> - The client does not have access rights to the content they requested from the server.<br><b>404 Not Found</b> - The server couldn't fetch the content the user requested as the server doesn't recongise any file under a certain name.<br><b>500 Internal Server Error</b> - The server has encountered a situation it doesn't know how to handle. </p>
					<a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Status" target="_blank">Reference</a>
				</div>
		</div>

		<div class='questions-card'>
				<div class='questions-card-container'>
					<h2>Question 17</h2>					
					<p></p>
					<a href="#" target="_blank">Reference</a>
				</div>
		</div>

	</div>
	</section>
	<?php
		// display any cookie messages. TODO style this message so that it is noticeable.
		echo $cookieMessage;
	?>
	
		<!-- 
		
			// TODO put a search box here and a submit button.
			
			// TODO the rest of this page is your choice, but you must not leave it blank.
			// TODO make a nice logo for the website.
			
			Possible ideas:
			•	List the 10 most recently purchased movies.
			•	Use a CSS Animated Slider.
			•	Have buttons for each genre (max 6) so that the customers can narrow their search. 
				(that is to say, clicking on "Action" should send them to MovieLst.php?genre=Action)
			•	Display any sales or promotions 

		-->

	
</body>
</html>