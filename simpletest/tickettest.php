<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("../user.php");
	require_once("../ticket.php");
	
	class TicketTest extends UnitTestCase
	{
		private $mysqli;
		
		// variable to hold the mysql user
		private $sqlTicket;
		private $ticket;
		
		// constant variables to reuse
		private $userId = 293;
		private $flightId = 1;
		private $seat = "13B";
		private $cost = 20.20;
		
		public function setUp()
		{
			mysqli_report(MYSQLI_REPORT_STRICT);
			try
			{			
				$this->mysqli = new mysqli("localhost", "____User______", "_____Password_____",  "____Database____");
				$this->ticket = new Ticket ($this->userId, $this->flightId, $this->seat, $this->cost);			
				$this->ticket->insert($this->mysqli);
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "Unable to connect to mySQL: " . $exception->getMessage();
			}
		}
		
		public function testGetTicketByUserId()
		{
			$this->sqlTicket = Ticket::getTicketsByUserId($this->mysqli, $this->userId);
			$this->assertIdentical($this->ticket->getSeat(), $this->sqlTicket[0]->getSeat());
		}
		
		public function testGetTicketByUserIdInvalid()
		{
			$this->expectException("Exception");
			@Ticket::getTicketsByUserId($this->mysqli, -2);
		}
		
		public function testGetTicketByFlightId()
		{
			$this->sqlTicket = Ticket::getTicketsByFlightId($this->mysqli, $this->flightId);
			$this->assertIdentical($this->ticket->getSeat(), $this->sqlTicket[0]->getSeat());
		}
		
		public function testGetTicketByFlightIdInvalid()
		{
			$this->expectException("Exception");
			@Ticket::getTicketsByFlightId($this->mysqli, -2);
		}
		
		public function createInvalidTicket()
		{
		    $this->expectException("Exception");
		    @new Ticket (-1, $this->flightId, $this->seat, $this->cost);
		}
		
		public function testUpdateTicket()
		{
		    
		    $newSeat = "55D";
		    $this->ticket->setSeat($newSeat);
		    $this->ticket->update($this->mysqli);
		    $this->sqlTicket = Ticket::getTicketsByUserId($this->mysqli, $this->userId);
		    $this->assertIdentical($newSeat, $this->sqlTicket[0]->getSeat());
		}
		
		// teardown
		public function tearDown()
		{
			$this->sqlTicket[0]->delete($this->mysqli);
			$this->mysqli->close();
		}
	}
?>