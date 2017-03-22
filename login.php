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
  $result = $connection->query("SELECT Name FROM Users WHERE
                            Name = '$unameStore'");
  if ($result->num_rows > 0) {
    // If user does indeed exist

    $hashedPSWD_Query = "SELECT Hash, Id FROM Users WHERE Name = '$unameStore'";
    $hashedPSWD = $connection->query($hashedPSWD_Query);
    if($hashedPSWD->num_rows > 0){
      $row = $hashedPSWD->fetch_assoc();
      echo $row["Hash"] . $row["Id"]. "<br>";
      if(password_verify($passwordStore, $row["Hash"]))
      {
        echo "Worked" . "<br>";
         echo $_SESSION["uname"] . "<br>";
        $_SESSION["uname"] = $unameStore;
        echo $_SESSION["uname"] . "<br>";
        
      } // if
      else {
       echo "Incorrect username or password" . "<br>";
       } // else
    } // if
    $connection -> close();
  } // if
  else {
     echo "Incorrect Username or Password." . "<br>";
     $connection -> close();
   } // else
<<<<<<< HEAD
=======


>>>>>>> Session
?>
