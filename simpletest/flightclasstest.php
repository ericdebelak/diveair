<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("../flight.php");
	
	class FlightTest extends UnitTestCase
	{
		private $mysqli;
		
		// variable to hold the mysql user
		private $sqlFlight;
		private $flight;
		
		// constant variables to reuse
		private $flightNumber = 123;
		
		private $origin = "ABQ";
		private $destination = "MSP";
		private $numberSeats = 123;
		private $departureTime = "2014-05-14 12:45:34";
		private $day = "2014-05-14";
		
		public function setUp()
		{
			mysqli_report(MYSQLI_REPORT_STRICT);
			try
			{			
				$this->mysqli = new mysqli("localhost", "____User______", "_____Password_____",  "____Database____");
				$this->flight = new Flight (-1, $this->flightNumber, $this->origin, $this->destination, $this->numberSeats, $this->departureTime);			
				$this->flight->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "Unable to connect to mySQL: " . $exception->getMessage();
			}
		}
		
		public function testFlightByFlightNumber()
		{
			$this->sqlFlight = Flight::getFlightByFlightNumber($this->mysqli, $this->flightNumber);
			$this->assertIdentical($this->flight, $this->sqlFlight);
		}
		
		public function testFlightByFlightNumberInvalid()
		{
			$this->expectException("Exception");
			@Flight::getFlightByFlightNumber($this->mysqli, 11);
		}
		
		public function testFlightById()
		{
			$this->sqlFlight = Flight::getFlightById($this->mysqli, $this->flight->getId());
			$this->assertIdentical($this->flight, $this->sqlFlight);
		}
		
		public function testFlightByIdInvalid()
		{
			$this->expectException("Exception");
			@Flight::getFlightById($this->mysqli, -6);
		}
		
		public function testValidUpdateValidFlight()
		{	
			$newOrigin = "LAX";
			$this->flight->setOrigin($newOrigin);
			$this->flight->update($this->mysqli);
			
			//select the user from mySQL and assert it was inserted properly
			$this->sqlFlight = Flight::getFlightByFlightNumber($this->mysqli, 123);
			
			// verify the email changed
			$this->assertIdentical($this->sqlFlight->getOrigin(), $newOrigin);
		}
		
		public function testgetFlightByDestinationAndOrigin()
		{	
			//select the all flights from mySQL 
			$this->sqlFlight = Flight::getFlightByOriginAndDestination($this->mysqli, $this->origin, $this->destination);
			
			// verify the email changed
			$this->assertTrue(count($this->sqlFlight) > 1);
		}
		
		public function testgetFlightByDestinationAndOriginAndDay()
		{	
			//select the all flights from mySQL 
			$this->sqlFlight = Flight::getFlightByOriginAndDestinationAndDay($this->mysqli, $this->origin, $this->destination, $this->day, $this->day);
			
			// verify the email changed
			$this->assertTrue(count($this->sqlFlight) == 1);
		}
		
		// teardown
		public function tearDown()
		{
			$this->flight->delete($this->mysqli);
			$this->mysqli->close();
		}
	}
?>