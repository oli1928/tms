<?php
session_start();
?>
<html>
<head>
<link rel="stylesheet"type="text/css"href="mainstyle.css">
</head>
<body>


</body>
</html>

<?php
require_once('config.inc.php');

// Create connection to database
$connection = new mysqli($database_host, $database_user, $database_pass, "2016_comp10120_m4");

if($connection -> connect_error) {
  die('Connect Error ('.$connection -> connect_errno.') '.$connection -> connect_error);
  } // if

if($result = $connection -> query("SELECT * FROM Things")) {
  printf("Select returned %d rows.\n", $result -> num_rows);

  $result -> close(); // Remember to relese the result set
} // if

  $unameStore = $_POST["uname"];
  $passwordStore = $_POST["psw"];
  $hash = password_hash($passwordStore, PASSWORD_BCRYPT);

if(preg_match("/^[a-zA-Z]*$/",$unameStore)){
  // Check to see if username exists
  $result = $connection->query("SELECT Uname FROM Users WHERE
                            Uname = '$unameStore'");
  if ($result->num_rows > 0) {
    // If user does indeed exist

    $hashedPSWD_Query = "SELECT Hash, Id FROM Users WHERE Uname = '$unameStore'";
    $hashedPSWD = $connection->query($hashedPSWD_Query);
    if($hashedPSWD->num_rows > 0){
      // Correct credentials hence log in

      $row = $hashedPSWD->fetch_assoc();
      if(password_verify($passwordStore, $row["Hash"]))
      {
        // Log in successful. Unset the error messages related to logging in
        // These error messages are used to make modal pop up again once user is linked back to index.php
        unset($_SESSION['errMSGlogin']);


        $_SESSION['uname'] = $unameStore;
	      $_SESSION["Id"] = $row['Id'];

        //Inspired by php.net
        $_SESSION['motd'] = "Log in successful.";
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $myMachinesPHP = 'myMachines.php';
        header("Location: http://$host$uri/$myMachines");
        exit;
        
      } // if
      else {
         // User puts wrong username/password so send them back to index.php
         $_SESSION['errMSGlogin'] = "Incorrect username or password. 1";

         $host  = $_SERVER['HTTP_HOST'];
         $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
         $indexPHP = 'index.php';
         header("Location: http://$host$uri/$indexPHP");
         exit;
       } // else
    } // if
    $connection -> close();
  } // if
  else {
     // Send user back to index.php
      $_SESSION['errMSGlogin'] = "Incorrect username or password. 2";

     $host  = $_SERVER['HTTP_HOST'];
     $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
     $indexPHP = 'index.php';
     header("Location: http://$host$uri/$indexPHP");
     exit;
     $connection -> close();
   } // else
  } // if
 else {
  // Send user back to homepage. Used annoying characters. This is for sanitisation
   $_SESSION['errMSGlogin'] = "Incorrect username or password. 3";
   $host  = $_SERVER['HTTP_HOST'];
   $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
   $indexPHP = 'index.php';
   header("Location: http://$host$uri/$indexPHP");
   exit;
   $connection -> close();
  } // else
?>
