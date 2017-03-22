<html>
<head>
<link rel="stylesheet"type="text/css"href="mainstyle.css">
</head>
<body>


</body>
</html>

<?php
if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
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

  $nameStore = $_POST["name"];
  $unameStore = $_POST["uname"];
  $emailStore = $_POST["email"];
  $passwordStore = $_POST["psw"];
/*
  // Hash password using BCRYPT
  $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'),0,22);
   //In later versions this will be querying the databse for the hash 
  // stored in the PSWD table
  $hash = crypt('password' ,'$2y$12$'. $salt);
*/
  $hash = password_hash($passwordStore, PASSWORD_BCRYPT);

  // Check to make sure not duplicate entry
  $result = $connection->query("SELECT Uname, Email FROM Users WHERE
                            Uname = '$unameStore' OR
                            Email = '$emailStore'");
  if ($result->num_rows > 0) {
    echo "That user already exists." . "<br>";
  } // if
  else {

    // Insert user into database
    $insert_user = "INSERT IGNORE INTO Users (Uname, Name, Email, Hash)
    VALUES ('$unameStore', '$nameStore', '$emailStore', '$hash')";

    if ($connection->query($insert_user) === TRUE) {
      echo "Profile created and stored successfully." . "<br>";
      echo "Welcome " . $_POST["uname"] . "<br>";
      echo "Your email is: " . $_POST["email"] . "<br>";
      $_SESSION["uname"] = $unameStore;
    } // if
    else {
      echo "Error: " . $insert_user . "<br>" . $connection->error;
    } // else
  } // else
  $host  = $_SERVER['HTTP_HOST'];
  $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  $extra = 'index.php';
  header("Location: http://$host$uri/$extra");
  exit;

  // Close connection to database_host
  $connection -> close();
} // if
else {
  // Invalid email so send back to index.php
  $host  = $_SERVER['HTTP_HOST'];
  $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  $extra = 'index.php';
  header("Location: http://$host$uri/$extra");
  exit;
}
?>
