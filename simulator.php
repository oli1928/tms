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
        <li><a href="Simulator.php">Simulator</a></li>
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
</script>

  <?php

  class Tape
  {
      private $positive_tape = array();
      private $negative_tape = array();
      private $position = 0;
      private $blank_symbol;
      private $max_right = 0;
      private $max_left = 0;

      function __construct($required_blank_symbol)
      {
          $this->blank_symbol = $required_blank_symbol;
          array_push($this->positive_tape, $this->blank_symbol);
          array_push($this->negative_tape, $this->blank_symbol);
      }

      function move_left()
      {
          $this->position -= 1;
          if ($this->position < $this->max_left)
          {
              $this->max_left -= 1;
          }
          if ($this->position == $this->max_left)
          {
              array_push($this->negative_tape, $this->blank_symbol);
          }
      }

      function move_right()
      {
          $this->position += 1;
          if ($this->position > $this->max_right)
          {
              $this->max_right += 1;
          }
          if ($this->position == $this->max_right)
          {
              array_push($this->positive_tape, $this->blank_symbol);
          }
      }

      function write($input)
      {
          if ($this->position == 0)
          {
              $this->positive_tape[0] = $input;
              $this->negative_tape[0] = $input;
          }
          elseif ($this->position > 0)
          {
              $this->positive_tape[$this->position] = $input;
          }
          elseif ($this->position < 0)
          {
              $this->negative_tape[$this->position] = $input;
          }
      }

      function read()
      {
          if ($this->position == 0)
          {
              return $this->positive_tape[0];
          }
          elseif ($this->position > 0)
          {
              return $this->positive_tape[$this->position];
          }
          elseif ($this->position < 0)
          {
              return $this->negative_tape[$this->position];
          }
      }

      function return_string($length)
      {
          $string = "";

          if ($this->position >= 0)
          {
              $string = "".$this->positive_tape[$this->position]."";
          }
          else
          {
              $string = "".$this->negative_tape[$this->position*-1]."";
          }



          for ($count = 1; $count <= round($length/2); $count++)
          {
              if (($this->position + $count) > $this->max_right)
              {
                  $string = $string. $this->blank_symbol;
              }
              else
              {
                  if (($this->position + $count) >= 0)
                  {
                      $string = $string. $this->positive_tape[$this->position + $count];
                  }
                  else
                  {
                      $string = $string. $this->negative_tape[($this->position + $count)*-1];
                  }
              }

              if (($this->position - $count) < $this->max_left)
              {
                  $string = $this->blank_symbol. $string;
              }
              else
              {
                  if (($this->position - $count) >= 0)
                  {
                      $string = $this->positive_tape[$this->position - $count]. $string;
                  }
                  else
                  {
                      $string = $this->negative_tape[($this->position -+ $count)*-1] . $string;
                  }
              }
          }
          return $string;
      }
  }

  class Machine
  {
      private $ticker_tapes = array();
      private $ticker_tapes_index = array();
      private $end_states = array();
      private $states = array();
      private $start_state;
      private $rules = array();
      private $current_state;
      private $symbols;
      private $step_count;
      private $blank_symbol;
      private $step_limit;

      function __construct($required_symbols, $required_blank_symbol)
      {
          $this->blank_symbol = $required_blank_symbol;
          $this->symbols = $required_symbols;
      }

      function add_tape($required_tape_name)
      {
          array_push($this->ticker_tapes_index, $required_tape_name);
          array_push($this->ticker_tapes, new Tape($this->blank_symbol));
      }

      function set_step_limit($required_step_limit)
      {
          $this->step_limit = $required_step_limit;
      }

      function index_tape($required_tape_name)
      {
          return array_search($required_tape_name, $this->ticker_tapes_index);
      }

      function add_state($required_state)
      {
          array_push($this->states, $required_state);
          array_push($this->rules, array());
      }

      function select_end_states($required_end_states)
      {
          $this->end_states = $required_end_states;
      }

      function select_start_state($required_start_state)
      {
          $this->start_state = $required_start_state;
          $this->current_state = $required_start_state;
      }

      function add_rule($required_rule)
      {
          array_push($this->rules[array_search($required_rule[0], $this->states)], $required_rule);
      }

      function add_string($required_string, $required_tape)
      {
          foreach ($required_string as $symbol)
          {
              $this->ticker_tapes[$this->index_tape($required_tape)]->write($symbol);
              $this->ticker_tapes[$this->index_tape($required_tape)]->move_right();
          }
          foreach ($required_string as $symbol)
          {
              $this->ticker_tapes[$this->index_tape($required_tape)]->move_left();
          }
      }

      function reset_step_count()
      {
          $this->step_count = 0;
      }

      function return_current_state()
      {
          $tape_output = array();
          $tape_names = array();
          foreach ($this->ticker_tapes_index as $tape)
          {
              array_push($tape_output, $this->ticker_tapes[array_search($tape, $this->ticker_tapes_index)]->return_string(13));
              array_push($tape_names, $tape);
          }
          return array($tape_output, $tape_names, $this->step_count, $this->current_state);
      }

      function run()
      {
          $this->reset_step_count();
          $return_array = array($this->return_current_state());
          while ((!(in_array($this->current_state, $this->end_states))) and $this->step_count < $this->step_limit)
          {
              foreach ($this->rules[array_search($this->current_state, $this->states)] as $rule)
              {
                  if (explode("@", $rule[1])[0] == $this->ticker_tapes[$this->index_tape(explode("@", $rule[1])[1])]->read())
                  {
                      $this->step_count += 1;
                      $this->ticker_tapes[$this->index_tape(explode("@", $rule[2])[1])]->write(explode("@", $rule[2])[0]);
                      {
                          $direction = explode("@", $rule[3])[0];
                          if ($direction == "l")
                          {
                              $this->ticker_tapes[$this->index_tape(explode("@", $rule[3])[1])]->move_left();
                          }
                          else
                          {
                              $this->ticker_tapes[$this->index_tape(explode("@", $rule[3])[1])]->move_right();
                          }
                          $this->current_state = $rule[4];

                          $x = $this->return_current_state();
                          array_push($return_array, array_merge($x, array($rule[3], explode("@", $rule[2])[1])));
                      }
                  }
              }
          }
          return json_encode($return_array);

      }
  }

  function convert_input_string($required_input_string)
  {
      return array(array("tape", "tape2", "tape3"), array("R", "S", "E"), "S", array("E"), array("1", "0", "#"), "0", array(array("S", "#@tape", "#@tape", "r@tape", "R"),
          array("S", "0@tape", "1@tape", "r@tape", "R"), array("S", "1@tape", "0@tape", "r@tape", "R"), array("R", "0@tape", "1@tape", "r@tape", "R"),
          array("R", "1@tape", "0@tape", "r@tape", "R"), array("R", "#@tape", "#@tape", "r@tape2", "E")), array(array("#", "1", "0", "1", "1", "0", "1", "0", "#"), array("1", "1")), 200);
  }

  function create_machine($required_input_array)
  {
      $required_input_array = convert_input_string($required_input_array);
      $error = false;
      if ($error == false) {
          $_SESSION['machine'] = new Machine($required_input_array[4], $required_input_array[5]);
          foreach ($required_input_array[0] as $tape)
          {
              $_SESSION['machine']->add_tape($tape);
          }
          foreach ($required_input_array[1] as $state)
          {
              $_SESSION['machine']->add_state($state);
          }
          $_SESSION['machine']->select_start_state($required_input_array[2]);
          $_SESSION['machine']->select_end_states($required_input_array[3]);
          foreach ($required_input_array[6] as $rule)
          {
              $_SESSION['machine']->add_rule($rule);
          }
          foreach ($required_input_array[7] as $data)
          {

              $_SESSION['machine']->add_string($data, $required_input_array[0][array_search($data, $required_input_array[7])]);
          }
          $_SESSION['machine']->set_step_limit($required_input_array[8]);
      } else {
          var_dump($error);
      }
  }

  if (isset($_POST['compile']))
  {
      create_machine("train");
      $_SESSION['array'] = $_SESSION['machine']->run();
      $_SESSION['code'] = $_POST['input'];

  }

  if (isset($_POST['save'])){
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
      $AuthourId = $_SESSION['Id'];
      $AuthourId_valid = true;
//We only need to check to see if they have entered a number
      if(strlen($AuthourId) == 0){
          $authourId_valid = false;
      }

//Variables to store the TMCode and later will put in the compiler to check the validity of the code
      $TMCode = $_SESSION['code'];
      $TMCode_valid = false;
//checks for an empty string
      if($TMCode == " "){
          echo"You haven't entered your TM code";
      }
//assuming all is good TMCode is passed on
      else{
          echo"Your TM code to be entered into the database is"."<br>".": ";
          echo$_SESSION['code'];
          echo "<br>";
          $TMCode_valid = true;
      }



      require_once('config.inc.php');

// Create a connection to the database
      $connection = new mysqli($database_host, $database_user, $database_pass, "2016_comp10120_m4");

//then check the connection
      if($connection  ->connect_error) {
          die("Connection failed: " . $connection->connect_error);
      }

//make a variable the sql commands to pass into the database
      $input_data = "INSERT INTO TM(Title, Description, isPublic, TMCode, AuthourId)
VALUES('$Title','$Description','$isPublic','$TMCode', $AuthourId)";
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

      echo '<script type="text/javascript">',
      'updateTMList();',
      '</script>'
      ;

  }


  function get_user_machines()
  {
      require_once('config.inc.php');

      // Connect to the database

      $mysqli = new  mysqli($database_host, $database_user, $database_pass, "2016_comp10120_m4");
      $user_id = $_SESSION['Id'];
      //Check for errors before doing anything else
      if ($mysqli->connect_error) {
          die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
      }

      $sql = "SELECT Users.Name, TM.*, TM.ID
          FROM Users, TM
          WHERE Users.Id = TM.AuthourId";
      $result = $mysqli->query($sql);

      $_SESSION['Tms'] = array();

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              if ($row["AuthourId"] == $user_id) {

                  array_push($_SESSION["Tms"], $row["Title"]);

              } // if
          } // while
      }
      $mysqli->close();

      $_SESSION["Tms"] = json_encode($_SESSION["Tms"]);
  }




  ?>

  <link rel="stylesheet" type="text/css" href="simulatorstyle.css">
  <script type='text/javascript' src="behave.js"></script>
  <script type="text/javascript">

      window.onload = function(){

          /*
           * This hook adds autosizing functionality
           * to your textarea
           */
          BehaveHooks.add(['keydown'], function(data){
              var numLines = data.lines.total,
                  fontSize = parseInt( getComputedStyle(data.editor.element)['font-size'] ),
                  padding = parseInt( getComputedStyle(data.editor.element)['padding'] );
              data.editor.element.style.height = (((numLines*fontSize)+padding))+'px';
          });

          /*
           * This hook adds Line Number Functionality
           */
          BehaveHooks.add(['keydown'], function(data){
              var numLines = data.lines.total,
                  house = document.getElementsByClassName('line-nums')[0],
                  html = '',
                  i;
              for(i=0; i<numLines; i++){
                  html += '<div>'+(i+1)+'</div>';
              }
              house.innerHTML = html;
          });

          var editor = new Behave({

              textarea: 		document.getElementById('input'),
              replaceTab: 	true,
              softTabs: 		true,
              tabSize: 		4,
              autoOpen: 		true,
              overwrite: 		true,
              autoStrip: 		true,
              autoIndent: 	true
          });

      };
  </script>

  <script>
      var output_array;
      var running = false;

      function add_tape(tape_name) {

          var tape_div = "<div id=\""+tape_name+"\" class=\"tape_div\"></div>";
          $("#tapes").append(tape_div);
          $('#'+tape_name).append("<div id=\"description@"+tape_name+"\" class=\"tape_description\">"+tape_name+"<br><div id=\"direction@"+tape_name+"\" class=\"tape_direction\"><></div>");
          for (var i = 0; i < 15; i++)
          {
              if (i != 7) {
                  $("#" + tape_name).append("<div id=\"" + i + "@" + tape_name + "\" class=\"tape_pos\"></div>");
              }
              else{
                  $("#" + tape_name).append("<div id=\"" + i + "@" + tape_name + "\" class=\"tape_pos\" style='background-color: dimgray'></div>");
              }
          }


      }

      function set_string(tape_name, tape_data) {




          tape_data = tape_data+'';
          tape_data = tape_data.split("");
          for (var i = 0; i < 15; i++)
          {
              $(document.getElementById(i+"@"+tape_name)).text(tape_data[i]);
          }

      }

      function init_machine(start_state) {
          for (var i = 0; i < start_state[1].length; i++)
          {
              add_tape(start_state[1][i]);
              set_string(start_state[1][i], start_state[0][i]);
          }
      }

      function run_machine() {
          if (running == false) {
              running = true;
              output_array = <?php echo $_SESSION['array']?>;
              init_machine(output_array[0]);
              $("#tapes-title").text("Step: 0 - Tapes - State: "+output_array[0][3]);

              setTimeout(function(){set_string_timer(1, 0)}, 2000);
          }
      }

      function set_string_timer(ix, tape) {




          $("#tapes-title").text("Step: "+output_array[ix][2]+" - Tapes - State: "+output_array[ix][3]);


          for (itape = 0; itape<output_array[0][1].length; itape++) {
              move = output_array[ix][4].split("@");



              if (move[1] == output_array[0][1][itape]){
                  if (move[0]== "l") {
                    $(document.getElementById("direction@" + output_array[0][1][itape])).text("<");
                }
                else if (move[0] == "r") {
                    $(document.getElementById("direction@" + output_array[0][1][itape])).text(">");
                }
              }



              for (i = 0; i < 15; i++) {
                  tape_data = output_array[ix][0][itape];

                  tape_data = tape_data + '';
                  tape_data = tape_data.split("");



                  $(document.getElementById(i + "@" + output_array[0][1][itape])).text(tape_data[i]);
              }
          }

          if(output_array[ix][2]<output_array.length-1) {
              setTimeout(function () {
                  (function (ix, tape) {
                      if (ix < output_array.length && tape >= output_array[0][1].length) {
                          set_string_timer(ix + 1, 0);
                      }
                      else if (ix < output_array.length && tape < output_array[0][1].length) {
                          set_string_timer(ix, tape + 1);
                      }
                  })(ix, tape);
              }, 500);
          }
      }
  </script>

  <div id="simulator-main-div">
      <div id="tapes"><div id="tapes-title">Step: 0 - Tapes - State: </div></div>

          <div id="control-button-box">
              <div id="control-button">
          <button onclick="run_machine()">Run</button>
              </div>
          </div>
          <form method="post">
              <input type="submit" value="Compile" name="compile">
              <div id="text-input-div" style="overflow-y: scroll; height:600px;">
                  <div class="container">
                      <div class="line-nums"><span id="line-numbers"></span></div>
                      <textarea wrap="hard" id="input" name="input"></textarea>
                  </div>
              </div>
          </form>
  </div>

  <div id="save-load-form">
      SAVE/LOAD
      <div class="save-load">
      <form method="post" id="slform">
          <div id="select-div">
          <select id="select">
          </select>
          </div>
          <input type="submit" value="Load" name="load">

          <input type="submit" value="Save" name="save">
          <input type="text" name="Title">
          <input type="radio" name="isPublic" value="private">Private<br>
          <input type="radio" name="isPublic" value="public" checked>Public<br>
          <div id="ab">
          <textarea rows="10" name="Description" id="description"></textarea>
          </div>
      </form>
      </div>
  </div>

<script>
    function setKeywordText(id, text) {
        var el = document.getElementById(id);
        el.value = text;
        var evt = document.createEvent("Events");
        evt.initEvent("change", true, true);
        el.dispatchEvent(evt);
    }



    function setCode(code) {
        console.log(code);
        code_array = code.split("\n");
        length = code_array.length;
        line_nums = "";
        for (i = 0; i < code_array.length; i++)
        {
            line_nums += (i+1)+"<br>";
        }
        setKeywordText("input", code);
        document.getElementById("input").setAttribute("rows", length);
        $(document.getElementById("line-numbers")).append(line_nums);
        updateTMList();

    }

    stuff = ""+<?php echo json_encode($_SESSION['code'])?>;

    setCode(stuff);

    function updateTMList(){
        <?php get_user_machines()?>
        tm = <?php echo $_SESSION['Tms']?>;

        $(document.getElementById("select")).remove();
        $(document.getElementById("select-div")).append("<select id='select'></select>");

        for (i=0; i<tm.length; i++)
        {
            $(document.getElementById("select")).append("<option value='"+tm[i]+"'>"+tm[i]+"</option>");
        }
    }

    updateTMList();


</script>

</body>
</html>
