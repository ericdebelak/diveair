<?php

	class Ticket
	{
		// state variables
		private $userId;
		private $flightId;
		private $seat;
		private $cost;
	
		/* constructor for a ticket object
		* input: (integer) userId
		* input: (integer) flightId
		* input: (string) seat
		* input: (double) cost
		* throws: when invalid input detected */
		public function __construct($newUserId, $newFlightId, $newSeat, $newCost)
		{
			try
			{
				// use the mutator methods since they have all input sanitization
				$this->setUserId($newUserId);
				$this->setFlightId($newFlightId);
				$this->setSeat($newSeat);
				$this->setCost($newCost);
			}
			catch(Exception $exception)
			{
				// rethrow the exception to the caller
				throw(new Exception("Unable to build ticket", 0, $exception));
			}
		}
		
		// accessors functions
		public function getUserId()
		{
			return($this->userId);
		}
		
		public function getFlightId()
		{
			return($this->flightId);
		}
		
		public function getSeat()
		{
			return($this->seat);
		}
		
		public function getCost()
		{
			return($this->cost);
		}
		
		// mutator functions
		
		/* for userId
		* input: (integer) new id
		* output: n/a
		* throws: invalid input detected */
		public function setUserId($newUserId)
		{
			if(is_numeric($newUserId) === false)
			{
				throw(new Exception("Invalid user id detected: $newId"));
			}
			
			// convert the id to an integer
			$newUserId = intval($newUserId);
			
			// throw out negative ids
			if($newUserId < 0)
			{
				throw(new Exception("Invalid user id detected: $newId"));
			}
			
			// sanitized; assign value
			$this->userId = $newUserId;
		}
		
		/* for flight id
		* input: (integer) new flight id
		* output: n/a
		* throws: invalid input detected */
		public function setFlightId($newFlightId)
		{
			if(is_numeric($newFlightId) === false)
			{
				throw(new Exception("Invalid user id detected: $newFlightId"));
			}
			
			// convert the id to an integer
			$newFlightId = intval($newFlightId);
			
			// throw out negative ids
			if($newFlightId < 0)
			{
				throw(new Exception("Invalid user id detected: $newFlightId"));
			}
			
			// sanitized; assign value
			$this->flightId = $newFlightId;
		}
		
		/* for seat
		* input: (string) seat, 2 digits and a letter
		* output: n/a
		* throws: invalid seat */
		public function setSeat($newSeat)
		{
			// trim the name
			$newSeat = trim($newSeat);
			$newSeat = strtoupper($newSeat);
			
			// require characters only
			$regexp = "/^[\d]{1,2}[A-F]{1}$/";
			if(preg_match($regexp, $newSeat) === false)
			{
				throw(new Exception("Invalid name detected: $newSeat"));
			}
			
			// sanitized; assign the value
			$this->seat = $newSeat;
		}
		
		/* for cost
		* input: (double) cost
		* output: n/a
		* throws: invalid cost */
		public function setCost($newCost)
		{
			// trim the name
			$newCost = trim($newCost);
			
			if(is_numeric($newCost) === false)
			{
				throw(new Exception("Invalid cost detected: $newCost"));
			}
			
			$newCost = number_format($newCost, 2, ".", "");
			$newCost = floatval($newCost);
			
			// sanitized; assign the value
			$this->cost = $newCost;
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
			
			// a create a query template
			$query = "INSERT INTO ticket (userId, flightId, seat, cost) VALUES(?, ?, ?, ?)";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare the statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("iisd", $this->userId, $this->flightId, $this->seat, $this->cost);
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
			
			// create the query template
			$query = "DELETE FROM ticket WHERE userId = ? AND flightId = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("ii", $this->userId, $this->flightId);
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
			
			// create the query template
			$query = "UPDATE ticket SET userId = ?, flightId = ?, seat = ?, cost = ? WHERE userId = ? AND flightId = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("iisdii", $this->userId, $this->flightId, $this->seat, $this->cost, $this->userId, $this->flightId);
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
		
		//static methods
		
		/* static method to get tickets by user is
		 * input: (pointer) to mysql
		 * input: (integer) user id to search by
		 * output: (object) user */
		public static function getTicketsByUserId(&$mysqli, $userId)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			// create the query template
			$query = "SELECT userId, flightId, seat, cost FROM ticket WHERE userId = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $userId);
			if($wasClean === false)
			{
				throw(new Exception("Unable to bind paramenters."));
			}
			
			// ok, let's rock!
			if($statement->execute() === false)
			{
				throw(new Exception("Unable to execute the statement."));
			}
			
			// get the result 
			$result = $statement->get_result();
			if($result === false || $result->num_rows < 1)
			{
				throw(new Exception("Unable to determine tickets: id not found."));
			}
			
			// get the row and make an array of objects
			$ticket = array();
			while($row = $result->fetch_assoc())
			{
				$ticket[] = new Ticket($row["userId"], $row["flightId"], $row["seat"], $row["cost"]);
			}
			
			
			$statement->close();
			
			return($ticket);
		}
		
		/* static method to get tickets by flight id
		 * input: (pointer) to mysql
		 * input: (integer) flight id to search by
		 * output: (object) user */
		public static function getTicketsByFlightId(&$mysqli, $flightId)
		{
			// check for a good mySQL pointer
			if(is_object($mysqli) === false || get_class($mysqli) !== "mysqli")
			{
				throw(new Exception("Non mySQL pointer detected."));
			}
			
			
			// create the query template
			$query = "SELECT userId, flightId, seat, cost FROM ticket WHERE flightId = ?";
			
			// prepare the query statement
			$statement = $mysqli->prepare($query);
			if($statement === false)
			{
				throw(new Exception("Unable to prepare statement."));
			}
			
			// bind parameters to the query template
			$wasClean = $statement->bind_param("i", $flightId);
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
				throw(new Exception("Unable to determine tickets: id not found."));
			}
			
			// get the row and set the id
			$ticket = array();
			while($row = $result->fetch_assoc())
			{
				$ticket[] = new Ticket($row["userId"], $row["flightId"], $row["seat"], $row["cost"]);
			}
			
			
			$statement->close();
			
			return($ticket);
		}
	}
?>