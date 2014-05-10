<?php
	// start session
	session_start();
	require_once("user.php");
        
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Dive Air - Fly to some places on a plane.</title>
            <link type="text/css" rel="stylesheet" href="stylesheet.css" />
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	    <script src="http://malsup.github.com/min/jquery.form.min.js"></script>
	    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	    <script src="login_validation.js" type="text/javascript"></script>
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
	    <h1>Welcome to Dive Air!</h1>
	    <div id="loginText">
                Login:<br />
                Email:<br />
                Password: <br />
            </div>
            <form id="login" method="post" action="login.php">
                <br />
                <input type="email" id="email" name="email" /><br />
                <input type="password" id="password" name="password" /><br />
                <button type="submit">Login</button><br />
            </form>
	    </section>
        </body>
    </html>