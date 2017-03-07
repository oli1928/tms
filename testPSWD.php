<!DOCTYPE html>
<head>
<title>Inputted data</title>
<link rel="stylesheet"type="text/css"href="mainstyle.css">

<body>

<?php

 $username_valid = false;
 $Username = $_GET["uname"];
 $Password = $_GET["psw"];

 //checks to see if the string is empty
 if(strlen($Username) == 0){
  echo"You haven't entered your username.";
  echo "<br>";
 }
 $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'),0,22);

  //In later versions this will be querying the databse for the hash 
  // stored in the PSWD table
  $hash = crypt('password' ,'$2y$12$'. $salt);
  
  echo "Hello:  ";
  echo $Username;
  echo "<br><br>";
  echo "Your entered password was: " ;
  echo "<br>";
  echo  $Password;
  echo "<br>";
  echo "<br>";
  echo "<br>";
  echo "This is your password hashed: ";
  echo $hash;

  echo "<br>";
  echo "<br>";
  echo "<br>";
  echo "using test password for user: password ";

  echo "<br>";
  echo "<br>";
  echo "<br>";
  //In the future this 'password' will need to be replaced by the   
  if( $hash == crypt($Password,$hash))
  {
    echo"The password you have provided matches the one in the database";
  }
  else
    echo"Incorrect password";
/*
$input_data = "INSERT INTO PSWD(Title, Description, isPublic, TMCode, AuthourId)
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
*/
?>

</body>
</html>
