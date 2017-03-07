<!DOCTYPE html>
<head>
<title>Inputted data</title>
<link rel="stylesheet"type="text/css"href="mainstyle.css">

<body>

<?php

 $username_valid = false;
 $Username = $_POST["uname"];
 $Password = $_POST["psw"];

 //checks to see if the string is empty
 if(strlen($Username) == 0){
  echo"You haven't entered your username.";
  echo "<br>";
 }
 $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'),0,22);

  $hash = crypt($Password ,'$2y$12$'. $salt);
  

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

  echo "What is this?";
  echo crypt($Password, $hash);

  echo "<br>";
  echo "<br>";
  echo "<br>";
  var_dump($hash == crypt('Password',$hash));
  var_dump($hash == crypt('password',$hash));
?>

</body>
</html>
