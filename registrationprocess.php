<?php
session_start();
require_once("user.php");
require_once("profile.php");
function register()
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
    
    // grab the info from the registration form and clean it up a little
    $email = $_POST["email"];
    $email = trim($email);
    $password = $_POST["password"];
    $firstName = $_POST["firstName"];
    $firstName = trim($firstName);
    $lastName = $_POST["lastName"];
    $lastName = trim($lastName);
    $year = $_POST["year"];
    $month = $_POST["month"];
    $day = $_POST["day"];
    $birthday = "$year-$month-$day";
    $specialNeeds = $_POST["specialNeeds"];
    if($specialNeeds == "")
    {
        $specialNeeds = 0;
    }
    
    
    // make sure the passwords match
    if($_POST["password"] !== $_POST["confirmPassword"])
    {
         echo "<p style='color: red'>Password do not match.</p>";
         return;
    }
    
    // salt and hash the password
    $bytes = openssl_random_pseudo_bytes(32, $cstrong);
    $salt = bin2hex($bytes);
    $passSalt = $password . $salt;
    $hash = hash("sha512", $passSalt, false);
    
    // create the user object and insert into mySQL
    $user = new User(-1, $email,$hash, $salt);
    try
    {
        $user->insert($mysqli);
    }
    catch(Exception $exception)
    {
        echo "<p style='color: red'>Email already in use.</p>";
        return;
    }
    
    // grab the user id and create the profile
    $id = $user->getId();
    $profile = new Profile(-1, $id, $firstName, $lastName, $birthday, $specialNeeds);
    $profile->insert($mysqli);
    
    // set the session cookie and redirect
    $_SESSION["id"] = $id;
    $location = $_SERVER['HTTP_REFERER'];
    if($location == "http://students.deepdivecoders.com/~ericd/PHP/assignment4/landing.php" || $location == "http://students.deepdivecoders.com/~ericd/PHP/assignment4/registration.php")
    {
            header("location: profilepage.php");
    }
    else
    {
            header("location: $location");
    }
    $mysqli->close();
}

// call the function
register();
?>