<?php

/**
* Handles all the regex related operations.
*/

require_once(__DIR__.'/../Require.php');

class Lexer {
  // This is defined as final and global
  private $COMMAND_SEPARATOR = ';';

  // This array will be used for patterns and is written to be maintainable 
  // @debug: $COMMAD_SEPARATOR cannot be assigned to command_separator. Why?
  private $statementSyntax = array(
    'whitespace' => '(?:\s)',
    'any_whitespace' => '(?:\s*)',
    'left_operand' => '(\w+)',
    'statement_operator' => '(?:=)',
    'right_operand' => '(.*?)',
    'command_separator' => ';'
    );

  // Create the pattern for statement regex
  private $statementPattern;

  // The users code for turing machine.
  private $code;

  // used for error handler
  // @debug @unused
  private $codeArray = array();
  private $currentLine;
  private $currentOffsetOnLine;

  // The error handler 
  private $errorHandler;

  function __construct($code)
  { 
    $this->code = $code;
    $this->codeArray = explode("\n", $code); 
    $this->currentLine = 0;
    $this->currentOffsetOnLine = 0;

    $this->errorHandler = new ErrorHandler($code);

    $this->statementPattern = '/^' . $this->statementSyntax["any_whitespace"] 
                      . $this->statementSyntax["left_operand"]
                      . $this->statementSyntax["any_whitespace"]
                      . $this->statementSyntax["statement_operator"] 
                      . $this->statementSyntax["any_whitespace"]
                      . $this->statementSyntax["right_operand"]
                      . $this->statementSyntax["any_whitespace"]
                      . $this->statementSyntax["command_separator"] 
                      . $this->statementSyntax["any_whitespace"] . '/s';
    // @debug
    // echo $this->statementPattern;
  } // construct

  // Return a Token of type array.
  // The arrays structure is the following:
  // array(
  //  'line' -> type: int
  //  'offset' -> type: int
  //  'command' -> type: string # this is the token value e.g. 'state = [s1,s2]'
  //  );
  public function getToken() {
    if(preg_match($this->statementPattern, $this->code, $matches)) {
      
      // Remove the matching string from the original string and
      // start again.
      $this->code = substr($this->code, strlen($matches[0]));

      $token = array(
        'line' => $this->currentLine,
        'offset' => $this->currentOffsetOnLine,
        'command' => array($matches[1] => $matches[2])
        );

      $this->updateOffsetAndLinePosition($matches);

      return $token;
    } // if
    else {
      // @ErrorHandler
      $error = array('line' => $this->currentLine, 'offset' => $this->currentOffsetOnLine);
      $this->errorHandler->errHandle("Something unexpected was found. ", $error);
      // exit(0);
    } // else
  } // getToken

  public function hasNextToken() {
    if(strlen($this->code) > 0)
      return true;
    return false;
  }

  // Updates the position the line at which this command is and its
  // offset in this line.
  public function updateOffsetAndLinePosition($matches) {
    // Now increase the line number by checking for the number of 
    // matches of new lines found inside the matching string.
    // 
    // If there is a new line in the match:
    // initialise the offset of current line.
    if($noOfNewLines = preg_match_all('/\n/', $matches[0])) {
      $this->currentLine += $noOfNewLines;
      $matching_lines = explode("\n", $matches[0]);
      $this->currentOffsetOnLine = strlen($matching_lines[$noOfNewLines]);
    } // if
    else 
      $this->currentOffsetOnLine += strlen($matches[0]);
  }

  
} // lexer
// $str = "state = 

// sada;
// state = nabbo ;habbo = jdsk ; 
// asdf = [(zxv, asdf)][(adf,adfa)]dlka;ssa
// kjfa;lks j;lfkasjdf kjasdfkj asldkfjshdfl sakdj f";
// $lexer = new Lexer($str);

// print_r($lexer->getToken());
// print_r($lexer->getToken());
// print_r($lexer->getToken());
// print_r($lexer->getToken());
// print_r($lexer->getToken());






 ?>