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
require_once("ticket.php");
require_once("flight.php");
function buyTicket()
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
    
    // grab the flight id fro the hidden field and the user id from the session
    $flightId = $_POST["flightId"];
    $userId = $_SESSION["id"];
    
    // static seat and cost since the scope of this project was limited
    $seat = "12B";
    $cost = 150.00;
    
    // create a ticket, insert it into the database and remove one seat from the flight
    $ticket = new Ticket($userId, $flightId, $seat, $cost);
    try
    {
        $ticket->insert($mysqli);
        $flight = Flight::getFlightById($mysqli, $flightId);
        $newSeats = $flight->getNumberSeats() - 1;
        $flight->setNumberSeats($newSeats);
        $flight->update($mysqli);
    }
    catch(Exception $exception)
    {
        echo "<br />Error: ticket not purchased! Do you already have a ticket on that flight?";
        $mysqli->close();
        exit;
    }
    
    echo "<br />You bought the ticket!";
    
    
    $mysqli->close();
}

// call the function
buyTicket();
?>
</section>
        </body>
    </html>