<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<!-- Used w3Schools -->
<html>
<title>
  Turing Machine Simulator
</title>
<?php
  if(isset($_SESSION['Id'])){
    echo "Hello id: ". $_SESSION['Id'];
  } // if
  else {
    $_SESSION["uname"] = "default";
    echo "Not currently signed in";
  }
  ?>
<head>
  <link rel="stylesheet" type="text/css" href="mainstyle.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
  <span class="title-tms">TURING MACHINE SIMULATOR</span>

  <div class="nav-bar">
    <ul>
      <li><a class="active" href="#home">Home</a></li>
      <li><a href="sqlCheck.php">Discover</a></li>
      <li><a href="#someLink">someLink</a></li>
      <li><a href="myMachines.php">my Machines</a></li>
      <span class="loginbutton"><li><button onclick="document.getElementById('id01').style.display='block'" class="loginbutton">Login</button></li></span>
      <span class="signupbutton"><li><button onclick="document.getElementById('id02').style.display='block'" style="width:auto;">Sign Up</button></li></span>
    </ul>
  </div>

<!-- Log in button -->
<div id="id01" class="modal">

  <form class="modal-content animate" action = "login.php" method = "post">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
      <img src="img_avatar2.png" alt="Avatar" class="avatar">
    </div>

    <div class="container">

      <label><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="uname" required>

      <label><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>

      <button type="submit">Login</button>
      <input type="checkbox" checked="checked"> Remember me
    </div>

    <div class="container" style="background-color:#f1f1f1">
      <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
      <span class="psw">Forgot <a href="forgotPSWD.php">password?</a></span>
    </div>
  </form>
</div>

<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<!-- Sign up button -->


<div id="id02" class="modal">
  <span onclick="document.getElementById('id02').style.display='none'" class="closesignup" title="Close Modal">×</span>
  <form class="modal-content animate" action="signup.php" method = "post">
    <div class="container">
      <label><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="uname" required>
      <label><b>Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" required>

      <label><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>

      <label><b>Repeat Password</b></label>
      <input type="password" placeholder="Repeat Password" name="psw-repeat" required>
      <input type="checkbox" checked="checked"> Remember me
      <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>

      <div class="clearfix">
        <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelsignupbtn">Cancel</button>
        <button type="submit" class="signupbtn">Sign Up</button>
      </div>
    </div>
  </form>
</div>

<script>
// Get the modal
var modal1 = document.getElementById('id01');
var modal2 = document.getElementById('id02');

// When the user clicks anywhere outside of the modal, close it

window.onclick = function(event) {
  if (event.target == modal2) {
    modal2.style.display = "none";
  }
}

window.onclick = function(event) {
  if (event.target == modal1) {
    modal1.style.display = "none";
  }
}
</script>

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
?>
</body>
</html>
