<html>
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

  $unameStore = $_POST["uname"];
  $emailStore = $_POST["email"];
  $passwordStore = $_POST["password"];
  $sql = "INSERT IGNORE INTO Users (uname, email, password)
          VALUES ('$unameStore', '$emailStore', md5('$passwordStore'))";
          // Change to use bcrypt later

  // Check to make sure not duplicate entry
  $result = $connection->query("SELECT uname, email FROM Users WHERE
                            uname = '$unameStore' OR
                            email = '$emailStore'");
  if ($result->num_rows > 0) {
    echo "That user already exists." . "<br>";
  } // if
  else {
    if ($connection->query($sql) === TRUE) {
      echo "Profile created and stored successfully." . "<br>";
      echo "Welcome " . $_POST["uname"] . "<br>";
      echo "Your email is: " . $_POST["email"] . "<br>";
    } // if
    else {
      echo "Error: " . $sql . "<br>" . $connection->error;
    } // else
  } // else

  // Close connection to database_host
  $connection -> close();
} // if
else {
  echo "Invalid email" . "<br>";
}
?>
