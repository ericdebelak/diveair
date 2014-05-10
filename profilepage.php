<?php
	// start session
	session_start();
	require_once("profile.php");
	require_once("flight.php");
        require_once("ticket.php");
	if(!isset($_SESSION["id"]))
	   {
		header("location: landing.php");
	   }
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{			
	    $mysqli = new mysqli("localhost", "____User______", "_____Password_____",  "____Database____");
	}
	catch(mysqli_sql_exception $exception)
	{
	    echo "Unable to connect to mySQL: " . $exception->getMessage();
	}
	
	// grab the user id from the session and create the profile
	$id = intval($_SESSION["id"]);
	$profile = Profile::getProfileByUserId($mysqli, $id);
	
	// if the user has tickets, display them
	try
	{
	    $tickets = Ticket::getTicketsByUserId($mysqli, $id);
	}
	catch(Exception $exception)
	{
	    $tickets = array();
	}
	
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
	    <h1>Your Profile - plus buy tickets!</h1>
	    <?php
	    
	    // grab the user's info and display it
	    $firstName = $profile->getFirstName();
	    $lastName = $profile->getLastName();
	    $bday = $profile->getBirthday();
	    $bday = explode("-", $bday);
	    $bday = "$bday[1]/$bday[2]/$bday[0]";
	    echo "Welcome $firstName $lastName!<br />";
	    echo "Birthday: $bday";
	    echo "<h2>Your Tickets</h2>";
	    
	    // if there are tickets, display them
	    try
	    {
		foreach($tickets as $ticket)
		{
		    $flightId = $ticket->getFlightId();
		    
			$flight = Flight::getFlightById($mysqli, $flightId);
			$flightNumber = $flight->getFlightNumber();
			$flightOrigin = $flight->getOrigin();
			$flightDestination = $flight->getDestination();
			$flightDeparture = $flight->getDepartureTime();
			echo "Flight $flightNumber leaves from $flightOrigin to $flightDestination at $flightDeparture.<br />";
		}
	    }
	    catch(Exception $exception)
	    {
		echo "You have no tickets yet.";
	    }
	    
	    ?>
	    <h2>Where would you like to fly today?</h2>
	    <form id="flight" method="post" action="searchprocess.php">
		Origin: <select name="origin">
		<option value="Origin">Origin</option>
		<?php
		
		// go to mySQL and grab the origins from the available flights
		$query = "SELECT origin FROM flight WHERE id > 0 ORDER BY origin";
		$statement = $mysqli->prepare($query);
		if($statement === false)
		{
			throw(new Exception("Unable to prepare statement."));
		}
		if($statement->execute() === false)
		{
			throw(new Exception("Unable to execute the statement."));
		}
		
		// get the result and make an array
		$result = $statement->get_result();
		$originArray = array();
		while($row = $result->fetch_assoc())
		{
		    $row = $row["origin"];
		    
		    // make sure there are no duplicates and format the origins for html
		    if(!in_array($row, $originArray))
		    {
			$originArray[] = $row;
			echo "<option value='$row'>$row</option>";
		    }
		    else
		    {
			continue;
		    }
		}
		?>
		</select><br />
		Destination: <select name="destination">
		<option value="na">Destination</option>
		<?php
		
		// go to mySQL and grab the destination of available flights
		$query = "SELECT destination FROM flight WHERE id > 0 ORDER BY destination";
		$statement = $mysqli->prepare($query);
		if($statement === false)
		{
			throw(new Exception("Unable to prepare statement."));
		}
		if($statement->execute() === false)
		{
			throw(new Exception("Unable to execute the statement."));
		}
		// get the result and make an array
		$result = $statement->get_result();
		$destinationArray = array();
		while($row = $result->fetch_assoc())
		{
		    $row = $row["destination"];
		    
		    // make sure there are no duplicates and format for html
		    if(!in_array($row, $destinationArray))
		    {
			$destinationArray[] = $row;
			echo "<option value='$row'>$row</option>";
		    }
		    else
		    {
			continue;
		    }
		}
		?>
		</select><br />
		Day: <select name="day">
		<option value="na">Day</option>
		<?php
		
		// get the available departure days from the database
		$query = "SELECT DATE(departureTime) FROM flight WHERE id > 0 ORDER BY departureTime";
		$statement = $mysqli->prepare($query);
		if($statement === false)
		{
			throw(new Exception("Unable to prepare statement."));
		}
		if($statement->execute() === false)
		{
			throw(new Exception("Unable to execute the statement."));
		}
		// get the result and make an array
		$result = $statement->get_result();
		$dateArray = array();
		while($row = $result->fetch_assoc())
		{
		    $row = $row["DATE(departureTime)"];
		    
		    // get rid of duplicates and format for html
		    if(!in_array($row, $dateArray))
		    {
			$dateArray[] = $row;
			echo "<option value='$row'>$row</option>";
		    }
		    else
		    {
			continue;
		    }
		}
		$mysqli->close();
		?>
		</select><br />
		<button id="search" type="submit">Search</button><br />
		<br />
	    </form>
	    <div id="info"></div>
	    <script> 
        $(document).ready(function() 
        { 
            var options = 
            {
            	target:        	"#info",
        	success:       	showResponse,
            	url:		'searchprocess.php',
            	type: 		'post',
            };
            
            $("#flight").submit(function()
            { 
                $(this).ajaxSubmit(options);
                return false; 
            }); 
        
	    function showResponse(responseText, statusText, xhr, $form)  
	    { 
  			
  	    } 
	});
	    
    	</script>
	</section>    
        </body>
    </html>