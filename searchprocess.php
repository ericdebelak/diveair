<?php
session_start();
require_once("profile.php");
require_once("flight.php");
require_once("ticket.php");
function flightSearch()
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
    
    // grab the info from the form and check against default values
    $origin = $_POST["origin"];
    $destination = $_POST["destination"];
    if($origin == "na" || $destination == "na")
    {
        echo "Please enter an origin and destination";
        exit;
    }
    $day = $_POST["day"];
    
    // search either by origin and destination and day or just by origin and destination
    if($day == "na")
    {
        try
        {
            $flights = Flight::getFlightByOriginAndDestination($mysqli, $origin, $destination);
        }
        catch(Exception $exception)
        {
            echo "Flights not found";
            $mysqli->close();
            exit;
        }
    }
    else
    {
        try
        {
            $flights = Flight::getFlightByOriginAndDestinationAndDay($mysqli, $origin, $destination, $day);
        }
        catch(Exception $exception)
        {
            echo "Flights not found";
            $mysqli->close();
            exit;
        }
    }
    
    // go through the array of objects and format them for the search results
    foreach($flights as $flight)
    {
        $flightId = $flight->getId();
        $flightNumber = $flight->getFlightNumber();
        $flightOrigin = $flight->getOrigin();
        $flightDestination = $flight->getDestination();
        $flightDeparture = $flight->getDepartureTime();
        $flightSeats = $flight->getNumberSeats();
        if($flightSeats > 0)
        {
            echo "Flight $flightNumber leaves from $flightOrigin to $flightDestination at $flightDeparture.<br />$flightSeats seats are available at \$150 per seat.<br /><form method='post' action='buyticket.php'><input type='hidden' name='flightId' value='$flightId' /><button id='buyNow' type='submit'>Buy Now</button><br /><br /></form>";
        }
        else
        {
            echo "Flights from $flightOrigin to $flightDestination at $flightDeparture are full.";
        }
    }
    $mysqli->close();
}

// call the function
flightSearch();
?>