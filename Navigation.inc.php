<?php 
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

	echo "<navbar>
			<div class='brand'>
                    <a href='Homepage.php'><img src='images/logo.png' /></a>
                </div>
                <div class='search-bar'>
                    <form action='MovieList.php' method='GET'><input name='search' type='text' placeholder='Search' value='$safeSearchString'/><button type='submit'><i class='fa fa-search'></i></button></form>
                </div>
                <ul class='navigation'>				 
                    <li><a href='Homepage.php'>Home</a></li>
                    <li><a href='MovieList.php'>Movies</a></li>
                    <li><a href='SessionList.php'>Session List</a></li>
                    <li><a href='Report.php'>Report</a></li> 
                    <li><a href='SignUp.php'>Sign Up</a></li> 
                </ul>
		    </navbar>";			
		
?>