<?php
session_start();
?>
<html>
<head>
<title>Results from database</title>
<link rel="stylesheet"type="text/css"href="mainstyle.css">
</head>
<body>
<?php
  // Load the config file containg my database details
  require_once('config.inc.php');
  $user_id = $_SESSION['Id'];
  // Connect to the database

  $mysqli = new  mysqli($database_host, $database_user, $database_pass, $database_name);

  //Check for errors before doing anything else
  if($mysqli -> connect_error)  {
    die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
  }

  $sql = "SELECT Id, Name, Email  FROM Users";
  $result = $mysqli->query($sql);
  if($result->num_rows > 0) {
    while($row = $result->fetch_assoc())  {
      if($row["Id"] == $user_id) {
	echo "----------------------------------";
	echo "<br>Welcome ". $row["Name"];
	
      } // if
    } // while
  } // if
   


  echo "<br> <br>";
  //method for outputting the TM
  $sql = "SELECT Users.Name, TM.*, TM.ID
          FROM Users, TM
          WHERE Users.Id = TM.AuthourId";
  $result = $mysqli->query($sql);

  if($result->num_rows > 0) {
    while($row = $result->fetch_assoc())  {
      if($row["IsPublic"] == 1)
      {
        echo "----------------------------------------";
        echo"<br>Id: ".$row["ID"];
        echo"<br>Title: ". $row["Title"];
        echo"<br>Description: ".$row["Description"];
        echo"<br>Authour: ".$row["Name"];
        echo"<br>TM Code: ".$row["TMCode"];
        echo"<br>Authour Id: ".$row["AuthourId"];
        echo"<br>Is public?:".$row["IsPublic"];
        echo "<br>----------------------------------------";
      }
    echo "<br>";
    }//
  }

  //Joining the Users and TM tables
  $sql = " Select TM.AuthourId, TM.Title, Users.Id FROM TM INNER JOIN Users ON TM.AuthourId = Users.Id";
  $result = $mysqli->query($sql);
  


  //Select all the things
 # if($result = $mysqli -> query("SELECT Id, Name, Email  FROM Students"))  {
 #   printf("Select returned %d rows.\n",$result->num_rows);
 #
 #   $result -> close(); //Remember to release the result set
 # }
  $mysqli -> close();
echo ' <a href="choice.html"> go back</a>';
?>
</body>
</html>

