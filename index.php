<?php
ob_start();
session_start();
$SignedIn;
?>
<!DOCTYPE html>
<!-- Used w3Schools -->
<html>
<title>
  Turing Machine Simulator
</title>

<head>
  <link rel="stylesheet" type="text/css" href="mainstyle.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
  <span class="title-tms">TURING MACHINE SIMULATOR</span>
  <h1>
  <br><br>
  <?php
  if(isset($_SESSION['Id'])){
    echo "Hello id: ". $_SESSION['Id'];
    $SignedIn = True;
  } // if
  else {
    $_SESSION["uname"] = "default";
    echo "Not currently signed in";
    $SignedIn = False;
  }
  ?>
  </h1>
  <div class="nav-bar">
    <ul>
      <li><a class="active" href="index.php">Home</a></li>
      <li><a href="Discover.php">Discover</a></li>
      <li><a href="simulator.php">Simulator</a></li>
      <li><a href="myMachines.php">my Machines</a></li>
      <?php if(!(isset($_SESSION['Id']))) { ?>
      <span class="loginbutton"><li><button onclick="document.getElementById('id01').style.display='block'" class="loginbutton">Login</button></li></span>
      <span class="signupbutton"><li><button onclick="document.getElementById('id02').style.display='block'" style="width:auto;">Sign Up</button></li></span>
 <?php } ?>
 <?php if(isset($_SESSION['Id'])) { ?>
  <form action="LogOut.php" method="post">
  <span class="logoutbutton"><li><button class="logoutbutton" type="submit" >Logout</button></li></span>
  </form>
<?php } ?>
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
      <label><b>Name</b></label>
      <input type="text" placeholder="Enter your name" name="name" required>

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

</body>
</html>
