<!DOCTYPE html>
<head>
<title>Inputted data</title>
<link rel="stylesheet"type="text/css"href="style.css">

<body>

<?php
 //Hard coded members name
 $members = array("Matti","Samuel","Abdullah","Oli","mohammed","Robert","Izuna");
 $arrayLength = count($members);


 //Variables to store the actual title and a boolean to make sure it is in the right format
 $title_valid = false;
 $Title = $_POST["Title"];

 //checks to see if the string is empty
 if(strlen($Title) == 0){
  echo"You haven't entered your title.";
  echo "<br>";
 }
 //Copied code from w3school to check that the name is valid
 else if(!preg_match("/^[a-zA-Z]*$/",$Title)){
  echo"Your title appears to be invalid, only letters and white space allowed";
  echo"<br>";
 }
 //With the title checked and all clear make it valid
 else{
 echo"Title: ";
 echo$_POST["Title"];
 $title_valid = true;
 echo"<br>";
 }


 //Variables to store the actual description and a boolean to make sure it is in the right format
 $Description = $_POST["Description"];
 $description_valid = false; 


 //Checks to see if the description is empty, but as this isnt required it is still calid
 if($Description == " "){
  echo"You haven't entered your Description.";
  $description_valid = true;
 }
 //Assuming that the 
 //Copied code from w3school to check that the name is valid if not will not post to the DB
 else if(!preg_match("/^[a-zA-Z]*$/",$Title)){
  echo"Your description appears to be invalid, only letters and white space allowed";
  echo"<br>";
 }
 //The description is in the right format and so will be put in the DB
 else{
 echo "Your Turing Machine description is: ";
 echo$_POST["Description"];
 $description_valid = true;
 }
echo"<br>";

//Variables to store the value of whether the Tm is public or not
$isPublic = $_POST["isPublic"];
echo "<br>";
//If the user chose private
if($isPublic == "private")
{
  echo "Your turing machine will be made private only you will be able to see it";
  echo"<br>";
  $isPublic = 0;
}
//if the user chose public
else if($isPublic == "public"){
  echo "Your turing machine will be made public to other users";
  echo"<br>";
  $isPublic = 1;
}

//variables to get the info of the authour id, hopefully later on this will be auto filled by the 
//user having a login to pass their id to this form
$AuthourId = $_POST["AuthourId"];
$AuthourId_valid = true;
//We only need to check to see if they have entered a number
if(strlen($AuthourId) == 0){
  $authourId_valid = false;
}

//Variables to store the TMCode and later will put in the compiler to check the validity of the code
$TMCode = $_POST["TMCode"];
$TMCode_valid = false;
//checks for an empty string
if($TMCode == " "){
  echo"You haven't entered your TM code";
}
//assuming all is good TMCode is passed on
else{
  echo"Your TM code to be entered into the database is"."<br>".": ";
  echo$_POST["TMCode"];
  echo "<br>";
  $TMCode_valid = true;
}



require_once('config.inc.php');

// Create a connection to the database
$connection = new mysqli($database_host, $database_user, $database_pass, $database_name);

//then check the connection
if($connection  ->connect_error) {
  die("Connection failed: " . $connection->connect_error);
}

//make a variable the sql commands to pass into the database
$input_data = "INSERT INTO TM(Title, Description, isPublic, TMCode, AuthourId)
VALUES('$Title','$Description','$isPublic','$TMCode','$AuthourId')";
//All the valid variables nested so it will only try if all fields are valid
if($title_valid){
  if($description_valid){
    if($TMCode_valid){
      if($AuthourId_valid){
        if ($connection->query($input_data) === TRUE) {
          echo "New data successfully input into the database<br>";
        }
        else {
          echo "Error: ".$input_data."<br>".$connection->error;
        }
      }
    }
  }
}


$connection->close();
echo "<br>";
echo ' <a href="test.html"> go back</a>';
echo "<br>";
echo ' <a href="sqlCheck.php">See result in the database</a>';
?>

</body>
</html>
