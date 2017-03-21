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

  <div class="nav-bar">
    <ul>
      <li><a class="active" href="#home">Home</a></li>
      <li><a href="sqlCheck.php">Discover</a></li>
      <li><a href="simulator.php">Simulator</a></li>
      <li><a href="#someLink2">someLink2</a></li>
      <span class="loginbutton"><li><button onclick="document.getElementById('id01').style.display='block'" class="loginbutton">Login</button></li></span>
      <span class="signupbutton"><li><button onclick="document.getElementById('id02').style.display='block'" style="width:auto;">Sign Up</button></li></span>
    </ul>
  </div>

<!-- Log in button -->
<div id="id01" class="modal">

  <form class="modal-content animate" action="/login.php">
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
      <span class="psw">Forgot <a href="#">password?</a></span>
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
  <form class="modal-content animate" action="/signup.php">
    <div class="container">
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
var modal = document.getElementById('id01');
var modal2 = document.getElementById('id02');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
window.onclick = function(event) {
    if (event.target == modal2) {
        modal2.style.display = "none";
    }
}
</script>

  <?php
  session_start();

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
                          if ($rule[2] == "l")
                          {
                              $this->ticker_tapes[$this->index_tape(explode("@", $rule[3])[1])]->move_left();
                          }
                          else
                          {
                              $this->ticker_tapes[$this->index_tape(explode("@", $rule[3])[1])]->move_right();
                          }
                          $this->current_state = $rule[4];
                          array_push($return_array, $this->return_current_state());
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
          array("R", "1@tape", "0@tape", "r@tape", "R"), array("R", "#@tape", "#@tape", "r@tape", "E")), array(array("#", "1", "0", "1", "1", "0", "1", "0", "#")), 200);
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
              $_SESSION['machine']->add_string($data, "tape");
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
  }
  ?>

  <link rel="stylesheet" type="text/css" href="simulatorstyle.css">
  <script type='text/javascript' src="../behave.js"></script>
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
      var paused = false;
      var running = false;

      function add_tape(tape_name) {

          var tape_div = "<div id=\""+tape_name+"\" class=\"tape_div\"></div>";
          $("#tapes").append(tape_div);
          $('#'+tape_name).append("<div id=\"description@"+tape_name+"\" class=\"tape_description\">"+tape_name+"</div>")
          for (var i = 0; i < 15; i++)
          {
              $("#"+tape_name).append("<div id=\""+i+"@"+tape_name+"\" class=\"tape_pos\"></div>");
          }
      }

      function set_string(tape_name, tape_data) {

          console.log(tape_data);



          tape_data = tape_data+'';
          tape_data = tape_data.split("");
          for (var i = 0; i < 15; i++)
          {
              console.log(i+"@"+tape_name+" = " + tape_data[i]);
              $(document.getElementById(i+"@"+tape_name)).text(tape_data[i]);
          }

      }

      function init_machine(start_state) {
          for (var i = 0; i < start_state[1].length; i++)
          {
              console.log(start_state[1][i])
              add_tape(start_state[1][i]);
              set_string(start_state[1][i], start_state[0][i]);
          }
      }

      function run_machine() {
          if (running == false) {
              running = true;
              output_array = <?php echo $_SESSION['array']?>;
              init_machine(output_array[0]);
              $("#tapes-title").text("Step: 0 - Tapes - State: "+output_array[0][3])

              set_string_timer(1, 0);
          }
      }

      function set_string_timer(ix, tape) {

          tape_data = output_array[ix][0];

          tape_data = tape_data+'';
          tape_data = tape_data.split("");


          $("#tapes-title").text("Step: "+output_array[ix-1][2]+" - Tapes - State: "+output_array[ix-1][3])

          for (i = 0; i < 15; i++)
          {
              console.log(i+"@tape = " + tape_data[i]);
              $(document.getElementById(i+"@tape")).text(tape_data[i]);
          }

          setTimeout(function() { (function(ix, tape){
              if (ix < output_array.length && tape >= output_array[0][1].length) {
                  set_string_timer(ix + 1, 0);
              }
              else if (ix < output_array.length && tape < output_array[0][1].length) {
                  console.log(ix+" sdfsdf "+tape)
                  set_string_timer(ix, tape + 1);
              }
          })(ix, tape);}, 500);
      }
  </script>

  <div id="simulator-main-div">
      <div id="tapes"><div id="tapes-title">Step: 0 - Tapes - State: </div></div>

          <div id="control-button-box">
              <div id="control-button">
          <button onclick="run_machine()">Run</button>
              </div>
              <div id="control-button">
          <button>Reset</button>
              </div>
          </div>
          <form method="post">
              <input type="submit" value="Compile" name="compile">
              <div id="text-input-div" style="overflow-y: scroll; height:600px;">
                  <div class="container">
                      <div class="line-nums"><span>1</span></div>
                      <textarea id="input"></textarea>
                  </div>
              </div>
          </form>

  </div>

</body>
</html>
