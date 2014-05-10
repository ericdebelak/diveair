<?php
session_start();

// remove the user id from the session
unset($_SESSION["id"]);
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Dive Air - Fly to some places on a plane.</title>
            <link type="text/css" rel="stylesheet" href="stylesheet.css" />
        </head>
        <body>
            <nav>
		<h1>Dive Air - Fly to some places on a plane.</h1>
		<div id="links"><a href="landing.php">Home</a>
		<a href="registration.php">Registration</a>
		<a href="profilepage.php">Profile</a>
		<a href="profilepage.php">Buy/Search Tickets</a>
                <a href="logout.php">Logout</a></div>
	    </nav>
            <section>
                <h1>Thank you for visiting!</h1>
</section>    
        </body>
    </html>