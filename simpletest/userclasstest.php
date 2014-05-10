<?php
	// grab the unit test framework
	require_once("/usr/lib/php5/simpletest/autorun.php");
	
	// grab the functions under scrutiny
	require_once("../user.php");
	
	class UserTest extends UnitTestCase
	{
		private $mysqli;
		
		// variable to hold the mysql user
		private $sqlUser;
		
		// constant variables to reuse
		private $email = "test@email.com";
		
		private $password = "47d80e3d06534ada8054f085b1e04d1eb9e0ecab0c1ca75bdcc701a37170b7fd38d6583eb89eadc380445da3ccbed0ee488b86a69d5db61caf967e0b4b6d7427";
		
		private $salt = "1b5cec8c46451b5375ea7e61f310fe831ad17f62098beb7a5bfce304821e3f78";
		
		
		public function setUp()
		{
			mysqli_report(MYSQLI_REPORT_STRICT);
			try
			{			
				$this->mysqli = new mysqli("localhost", "____User______", "_____Password_____",  "____Database____");
			}
			catch(mysqli_sql_exception $exception)
			{
				echo "Unable to connect to mySQL: " . $exception->getMessage();
			}
		}
		
		public function testGetByEmail()
		{
			
			$user = new User (-1, $this->email, $this->password, $this->salt);			
			$user->insert($this->mysqli);
			$this->sqlUser = User::getUserByEmail($this->mysqli, $this->email);
			$this->assertIdentical($user, $this->sqlUser);
		}
		
		public function testGetByEmailInvalid()
		{
			
			$this->sqlUser = new User (-1, $this->email, $this->password, $this->salt);			
			$this->sqlUser->insert($this->mysqli);
			$this->expectException("Exception");
			@User::getUserByEmail($this->mysqli, "steve@jobs.com");
		}
		
		public function testGetById()
		{
			
			$user = new User (-1, $this->email, $this->password, $this->salt);			
			$user->insert($this->mysqli);
			$this->sqlUser = User::getUserById($this->mysqli, $user->getId());
			$this->assertIdentical($user, $this->sqlUser);
		}
		
		public function testGetByIdInvalid()
		{
			
			$this->sqlUser = new User (-1, $this->email, $this->password, $this->salt);			
			$this->sqlUser->insert($this->mysqli);
			$this->expectException("Exception");
			@User::getUserById($this->mysqli, 1);
		}
		
		public function testCreateValidUser()
		{
			// create an insert the user
			$user = new User (-1, $this->email, $this->password, $this->salt);			
			$user->insert($this->mysqli);
			
			//select the user from mySQL and assert it was inserted properly
			$this->sqlUser = User::getUserByEmail($this->mysqli, $this->email);
			$this->assertIdentical($this->sqlUser->getEmail(), $this->email);
			$this->assertIdentical($this->sqlUser->getPassword(), $this->password);
			$this->assertIdentical($this->sqlUser->getSalt(), $this->salt);
			$this->assertTrue($this->sqlUser->getId() > 0);
		}
		
		public function testValidUpdateValidUser()
		{
			// create an insert the user
			$user = new User (-1, $this->email, $this->password, $this->salt);			
			$user->insert($this->mysqli);
			
			$newEmail = "this@is.not.correct.com";
			$user->setEmail($newEmail);
			$user->update($this->mysqli);
			
			//select the user from mySQL and assert it was inserted properly
			$this->sqlUser = User::getUserByEmail($this->mysqli, $newEmail);
			
			// verify the email changed
			$this->assertIdentical($this->sqlUser->getEmail(), $newEmail);
			$this->assertIdentical($this->sqlUser->getPassword(), $this->password);
			$this->assertIdentical($this->sqlUser->getSalt(), $this->salt);
			$this->assertTrue($this->sqlUser->getId() > 0);
		}
		
		// teardown
		public function tearDown()
		{
			$this->sqlUser->delete($this->mysqli);
			$this->mysqli->close();
		}
	}
?>