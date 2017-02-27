<html>
<head>
<title>Results from database</title>
<link rel="stylesheet"type="text/css"href="style.css">
</head>
<body>
<?php

  // Load the config file containg my database details
  require_once('config.inc.php');

  // Connect to the database

  $mysqli = new  mysqli($database_host, $database_user, $database_pass, $database_name);
  $members = array("Matti","Sam","Abdullah","Oli","mohammed","Robert","Izzuna");
  $arrayLength = count($members);


  //Check for errors before doing anything else
  if($mysqli -> connect_error)  {
    die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
  }

  $sql = "SELECT Id, Name, Email  FROM Users";
  $result = $mysqli->query($sql);

  if($result->num_rows > 0) {
    while($row = $result->fetch_assoc())  {
      echo "Id: ".$row["Id"]." - Name: ". $row["Name"]." - Email: ".
           $row["Email"];
    global $arrayLength;
    for($i = 0;$i < $arrayLength; $i++){
        global $members;
        if($row["Name"] == $members[$i]){
          echo", I notice that  you are part of the group M4.";}//check memebrs if
       }// if
    echo "<br>";
    }// for loop through memebers
  }
  else {
    echo "0 results";
  }

  echo "<br> <br>";
  //method for outputting the TM
  $sql = "SELECT t.*, u.Name, u.Id FROM Users AS u JOIN TM AS t ON t.AuthourId = u.Id ";
  $result = $mysqli->query($sql);

  if($result->num_rows > 0) {
    while($row = $result->fetch_assoc())  {
      echo "----------------------------------------";
      echo"<br>Id: ".$row["Id"];
      echo"<br>Title: ". $row["Title"];
      echo"<br>Description: ".$row["Description"];
      echo"<br>Authour: ".$row["Name"];
      echo"<br>TM Code: ".$row["TMCode"];
      echo"<br>Authour Id: ".$row["AuthourId"];
      echo"<br>Is public?:".$row["IsPublic"];
      echo "<br>----------------------------------------";
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

