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
$connection = new mysqli($database_host, $database_user, $database_pass, $database_name);

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
      echo $row["Hash"] . $row["Id"]. "<br>";
      if(password_verify($passwordStore, $row["Hash"]))
      {
        $_SESSION["uname"] = $unameStore;
        

	$_SESSION["Id"] = $row['Id'];
	echo $_SESSION['Id'];

	      $_SESSION["Id"] = $row['Id'];
       // echo '   <a href="myMachines.php">Click here to See your machines</a>';

        //Inspired by php.net
        //header("refresh:5; url=myMachines.php");
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $myMachinesPHP = 'myMachines.php';
        header("Location: http://$host$uri/$myMachines");
        exit;
        
      } // if
      else {
         // User puts wrong username/password so send them back to index.php
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
     $host  = $_SERVER['HTTP_HOST'];
     $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
     $indexPHP = 'index.php';
     header("Location: http://$host$uri/$indexPHP");
     exit;
     $connection -> close();
   } // else
?>
