<html>
<head>
<title>Results from database</title>
<link rel="stylesheet"type="text/css"href="mainstyle.css">
</head>
<body>
<?php
  session_start();
  // Load the config file containg my database details
  require_once('config.inc.php');

  // Connect to the database

  $mysqli = new  mysqli($database_host, $database_user, $database_pass, $database_name);
  $user_Id = $_SESSION['Id'];
  //Check for errors before doing anything else
  if($mysqli -> connect_error)  {
    die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
  }



  echo "<br> <br>". $user_Id ;
  //Method for uptung the info on the user that is signed in
  $sql = "SELECT * FROM USERS WHERE Id == $user_Id";
  $result = $mysqli->query($sql);
  if($result->num_rows>0)
    while($row = $result->fetch_assoc())  {
      echo "------------------------------";
      echo "Name: ". $row['Name']."<br>";
      echo "Email: " . $row['Email']."<br><br>";
   } // while
  //method for outputting the TM
  $sql = "SELECT Users.Name, TM.*, TM.ID
          FROM Users, TM
          WHERE Users.Id = TM.AuthourId";
  $result = $mysqli->query($sql);

  if($result->num_rows > 0) {
    while($row = $result->fetch_assoc())  {
      if($row["AuthourId"] == $user_Id)
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
      } // if
    echo "<br>";
    } // while
  } // if
/*
  //Joining the Users and TM tables
  $sql = " Select TM.AuthourId, TM.Title, Users.Id FROM TM INNER JOIN Users ON TM.AuthourId = Users.Id";
  $result = $mysqli->query($sql);
  


  //Select all the things
 # if($result = $mysqli -> query("SELECT Id, Name, Email  FROM Students"))  {
 #   printf("Select returned %d rows.\n",$result->num_rows);
 #
 #   $result -> close(); //Remember to release the result set
 # }
*/
  $mysqli -> close();
echo ' <a href="index.html"> Homepage</a>';
?>
</body>
</html>

