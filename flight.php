<?php
	class Flight
	
	{
		// state variables
		private $id;
		private $flightNumber;
		private $origin;
		private $destination;
		private $numberSeats;
		private $departureTime;
		
	
		/* constructor for a flight object
		* input: (integer) new Id
		* input: (integer) new flight number
		* input: (string) new origin
		* input: (string) new destination
		* input: (integer) number of seats
		* input: (string) date time
		* throws: when invalid input detected */
		public function __construct($newId, $newFlightNumber, $newOrigin, $newDestination, $newNumberSeats, $newDepartureTime)
		{
			try
			{
				// use the mutator methods since they have all input sanitization
				$this->setId($newId);
				$this->setFlightNumber($newFlightNumber);
				$this->setOrigin($newOrigin);
				$this->setDestination($newDestination);
				$this->setNumberSeats($newNumberSeats);
				$this->setDepartureTime($newDepartureTime);
			}
			catch(Exception $exception)
			{
				// rethrow the exception to the caller
				throw(new Exception("Unable to build flight", 0, $exception));
			}
		}
		
		// accessors functions
		public function getId()
		{
			return($this->id);
		}
		
		public function getFlightNumber()
		{
			return($this->flightNumber);
		}
		
		public function getOrigin()
		{
			return($this->origin);
		}
		
		public function getDestination()
		{
			return($this->destination);
		}
		
		public function getNumberSeats()
		{
			return($this->numberSeats);
		}
		
		public function getDepartureTime()
		{
			return($this->departureTime);
		}
		
		// mutator functions
		
		/* for id
		* input: (integer) new id
		* output: n/a
		* throws: invalid input detected */
		public function setId($newId)
		{
			if(is_numeric($newId) === false)
			{
				throw(new Exception("Invalid id detected: $newId"));
			}
			
			// convert the id to an integer
			$newId = intval($newId);
			
			// throw out negative ids except -1, which is our placeholder
			if($newId < -1)
			{
				throw(new Exception("Invalid id detected: $newId"));
			}
			
			// sanitized; assign value
			$this->id = $newId;
		}
		
		/* for flight number
		* input: (integer) new flight number
		* output: n/a
		* throws: invalid input detected */
		public function setFlightNumber($newFlightNumber)
		{
			if(is_numeric($newFlightNumber) === false)
			{
				throw(new Exception("Invalid flight number detected: $newFlightNumber"));
			}
			
			// convert the id to an integer
			$newFlightNumber = intval($newFlightNumber);
			
			// throw out negative ids except -1, which is our placeholder
			if($newFlightNumber < -1)
			{
				throw(new Exception("Invalid flight number detected: $newFlightNumber"));
			}
			
			// sanitized; assign value
			$this->flightNumber = $newFlightNumber;
		}
		
		/* for origin
		* input: (string) new origin
		* output: n/a
		* throws: invalid origin */
		public function setOrigin($newOrigin)
		{
			// trim the email
			$newOrigin = trim($newOrigin);
			$newOrigin = strtoupper($newOrigin);
			
			// enforce 128 hexadecimal bytes
			$regexp = "/^[A-Z]{3}$/";
			if(preg_match($regexp, $newOrigin) !== 1)
			{
				throw(new Exception("Invalid origin detected: $newOrigin"));
			}
			
			// sanitized; assign the value
			$this->origin = $newOrigin;
		}
		
		/* for destination
		* input: (string) new destination
		* output: n/a
		* throws: invalid origin */
		public function setDestination($newDestination)
		{
			// trim the email
			$newDestination = trim($newDestination);
			$newDestination = strtoupper($newDestination);
			
			// enforce 128 hexadecimal bytes
			$regexp = "/^[A-Z]{3}$/";
			if(preg_match($regexp, $newDestination) !== 1)
			{
				throw(new Exception("Invalid destination detected: $newDestination"));
			}
			
			// sanitized; assign the value
			$this->destination = $newDestination;
		}
		
		/* for number of seats
		* input: (integer) new number of seats
		* output: n/a
		* throws: invalid input detected */
		public function setNumberSeats($newNumberSeats)
		{
			if(is_numeric($newNumberSeats) === false)
			{
				throw(new Exception("Invalid number of seats detected: $newNumberSeats"));
			}
			
			// convert the id to an integer
			$newNumberSeats = intval($newNumberSeats);
			
			// throw out negative ids except -1, which is our placeholder
			if($newNumberSeats < -1)
			{
				throw(new Exception("Invalid number of seats detected: $newNumberSeats"));
			}
			
			// sanitized; assign value
			$this->numberSeats = $newNumberSeats;
		}
		
		/* for departure time
		* input: (string) new departure time
		* output: n/a
		* throws: invalid departure time */
		public function setDepartureTime($newDepartureTime)
		{
			// trim the password
			$newDepartureTime = trim($newDepartureTime);
			
			// enforce the right time
			$regexp = "/^(20[\d]{2})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][\d]|3[01])[\s]([0-1][\d]|2[0-3])[:]([0-5][\d])[:]([0-5][\d])$/";
			if(preg_match($regexp, $newDepartureTime) !== 1)
			{
				throw(new Exception("Invalid time detected: $newDepartureTime"));
			}
			
			// sanitized; assign the value
			$this->departureTime = $newDepartureTime;
		}
		
		
		
		// mySQL mutator methods
		
		/* inserts a new object into mySQL
		* input: (pointer) mySQL connection, by reference
		* output: n/a
		* throws: if the object could not be inserted */
		public function insert(&$mysqli)
		{
			// handle degenerate cases
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// verify the id is -1 (i.e., a new flight)
			if($this->id !== -1)
			{
				throw(new Exception("Non new id detected."));
			}
			
			// a create a query template
			$query = "INSERT INTO flight (flightNumber, origin, destination, numberSeats, departureTime) VALUES (?, ?, ?, ?, ?)";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare the statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("issis", $this->flightNumber, $this->origin, $this->destination, $this->numberSeats, $this->departureTime);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				var_dump($statement);
				throw(new Exception("Unable to execute the statement."));
			}
			
			$statement->close();
			
			// set the flight id from the database
			try
			{
				$this->setId($mysqli->insert_id);
			}
			catch(Exception $exception)
			{
				throw(new Exception("Unable to determine flight id", 0, $exception));
			}
			
		}
		
		/* function to delete
		 * input: (pointer) mySQL connection, by reference
		 * output: N/A
		 * throws: if the object could not be deleted */
		public function delete(&$mysqli)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// verify the id is not -1 (which would be a new flight)
			if($this->id === -1)
			{
				throw(new Exception("New id detected"));
			}
			
			// create the query template
			$query = "DELETE FROM flight WHERE id = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $this->id);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			$statement->close();
			
		}
		
		/* update function
		 * input: (pointer) mysql connection
		 * output: n/a
		 * throws: when the object was not updated */
		public function update(&$mysqli)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// verify the id is not -1 (which would be a new user)
			if($this->id === -1)
			{
				throw(new Exception("New id detected"));
			}
			
			// create the query template
			$query = "UPDATE flight SET flightNumber = ?, origin = ?, destination = ?, numberSeats = ?, departureTime = ? WHERE id = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("issisi", $this->flightNumber, $this->origin, $this->destination, $this->numberSeats, $this->departureTime, $this->id);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			$statement->close();
		}
		
		//static methods ***************************************************************************************************************
		
		/* static method to get flight by id
		 * input: (pointer) to mysql
		 * input: (string) id to search by
		 * output: (object) flight */
		public static function getFlightById(&$mysqli, $id)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT id, flightNumber, origin, destination, numberSeats, departureTime FROM flight WHERE id = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $id);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			// get the result and make a new object
			$result = $statement->get_result();
			if($result === false || $result->num_rows !== 1)
			{
				throw(new Exception("Unable to determine user: id not found."));
			}
			
			// get the row and create the object
			$row = $result->fetch_assoc();
			$flight = new Flight($row["id"], $row["flightNumber"], $row["origin"], $row["destination"], $row["numberSeats"], $row["departureTime"]);
			return($flight);
			
			$statement->close();
		}
		
		/* static method to get flight by flight number
		 * input: (pointer) to mysql
		 * input: (string) id to search by
		 * output: (object) flight */
		public static function getFlightByFlightNumber(&$mysqli, $flightNumber)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT id, flightNumber, origin, destination, numberSeats, departureTime FROM flight WHERE flightNumber = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $flightNumber);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			// get the result and make a new object
			$result = $statement->get_result();
			if($result === false || $result->num_rows !== 1)
			{
				throw(new Exception("Unable to determine user: id not found."));
			}
			
			// get the row and create the object
			$row = $result->fetch_assoc();
			$flight = new Flight($row["id"], $row["flightNumber"], $row["origin"], $row["destination"], $row["numberSeats"], $row["departureTime"]);
			return($flight);
			
			$statement->close();
		}
		
	/* static method to get flight by origin and destination
		 * input: (pointer) to mysql
		 * input: (string) id to search by
		 * output: (array of objects) flights */
		public static function getFlightByOriginAndDestination(&$mysqli, $origin, $destination)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT id, flightNumber, origin, destination, numberSeats, departureTime FROM flight WHERE origin = ? AND destination = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("ss", $origin, $destination);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			// get the result and make a new object
			$result = $statement->get_result();
			if($result === false || $result->num_rows < 1)
			{
				throw(new Exception("Unable to determine flight: flight not found."));
			}
			
			// get the rows and make an array of objects
			$flight = array();
			while($row = $result->fetch_assoc())
			{
				$flight[] = new Flight($row["id"], $row["flightNumber"], $row["origin"], $row["destination"], $row["numberSeats"], $row["departureTime"]);
			}
			$statement->close();
			return($flight);
		}
		/* static method to get flight by origin and destination and day
		 * input: (pointer) to mysql
		 * input: (string) id to search by
		 * output: (array of objects) flights */
		public static function getFlightByOriginAndDestinationAndDay(&$mysqli, $origin, $destination, $day)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT id, flightNumber, origin, destination, numberSeats, departureTime FROM flight WHERE origin = ? AND destination = ? AND DATE(departureTime) = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("sss", $origin, $destination, $day);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			// get the result and make a new object
			$result = $statement->get_result();
			if($result === false || $result->num_rows < 1)
			{
				throw(new Exception("Unable to determine flight: flight not found."));
			}
			
			// get the rows and make an array of objects
			$flight = array();
			while($row = $result->fetch_assoc())
			{
				$flight[] = new Flight($row["id"], $row["flightNumber"], $row["origin"], $row["destination"], $row["numberSeats"], $row["departureTime"]);
			}
			$statement->close();
			return($flight);
		}
	}
?>