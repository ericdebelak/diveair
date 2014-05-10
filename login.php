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
<?php
session_start();
require_once("user.php");
function login()
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {			
        $mysqli = new mysqli("localhost", "____User______", "_____Password_____",  "____Database____");
    }
    catch(mysqli_sql_exception $exception)
    {
        echo "Unable to connect to mySQL: " . $exception->getMessage();
    }
    
    // grab email from form and trim it, then try to find the email in the database
    $email = $_POST["email"];
    $email = trim($email);
    try
    {
                $user = User::getUserByEmail($mysqli, $email);
    }
    catch(Exception $exception)
    {
                echo "<p style='color: red'>Email or password do not match our records.</p>";
                return;
    }
    
    // add salt, hash the password and check against database
    $salt = $user->getSalt();
    $password = $_POST["password"] . $salt;
    $password = hash("sha512", $password, false);
    
    // if password is right, set the session with the user id and redirect
    if($user->getPassword() == $password)
    {
	$id = $user->getId();
        $_SESSION["id"] = $id;
        $location = $_SERVER['HTTP_REFERER'];
        if($location == "http://students.deepdivecoders.com/~ericd/PHP/assignment4/landing.php")
        {
                header("location: profilepage.php");
        }
        else
        {
                header("location: $location");
        }
    }
    else
    {
        echo "<p style='color: red'>Email or password do not match our records.</p>";
    }
    $mysqli->close();
    
}

// call the function
login();
?>
</section>    
        </body>
    </html>