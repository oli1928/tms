<html>
<head>
<link rel="stylesheet"type="text/css"href="mainstyle.css">
</head>
<body>



<?php
if (filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL)) {
  require_once('config.inc.php');

  // Create connection to database
  $connection = new mysqli($database_host, $database_user, $database_pass, "2016_comp10120_m4");
  
  // If connextion fails close connection and report error message
  if($connection -> connect_error) {
    die('Connect Error ('.$connection -> connect_errno.') '.$connection -> connect_error);
  } // if

  //Store email in variable
  $emailStore = $_POST["Email"];
  $validAttempt = False;
  //SQL query string
  $sql = "SELECT Uname, Email 
                  FROM Users WHERE Email = $emailStore";
  
  // Check to see if Email exists
  $result = $connection->query("SELECT Email FROM Users WHERE
                            Email = '$emailStore'");
 
  if ($result->num_rows > 0) {
    // Email does indeed exist
      $UnameQuery = "SELECT Uname, Id FROM Users WHERE Email = '$emailStore'";
      $UnameResult = $connection ->query($UnameQuery);
      if($UnameResult->num_rows > 0){
        $row = $UnameResult->fetch_assoc();
	$validAttempt = True;
        echo "Thank you user: ". $row["Uname"].", Id: ".$row["Id"]. ", your replacement password will shortly be sent to: ";
	echo $emailStore."<br>";
	
       } // if
       else {
         echo "No matching emails found<br>";
       } // else
  } // if
} // if


if($validAttempt) {
  function randomString($length = 6) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
  }

  $randomPassword = randomString();
  $hash = password_hash($randomPassword, PASSWORD_BCRYPT);

  echo "The replacement password and hash is as follows : <br>";
  echo "Password :". $randomPassword."<br>";
  echo "Hash: ".$hash;

  $to = $emailStore;
  $subject = "Password reset for Online TMS";
  $message = "The replacement password for your account is: ". $randomPassword;
  mail('sjenkyboy@gmail.com', $subject, $message);
  if(mail('sjenkyboy@gmail.com', $subject, $message))
	echo "succesfully sent the email";
} // if
  echo"<br><br><br>";
?>
</body>
</html>

