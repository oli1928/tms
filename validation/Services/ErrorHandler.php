<?php 


/**
* Handles all sort of errors by printing them out.
*/

class ErrorHandler 
{
  // An array having each element corresponding 
  // to a line of the code.
  private $codeArray;
  function __construct($code)
  {
    $this->codeArray = explode("\n", $code); 
  } // construct
  public function errHandle($message, $token) {
    try {
      throw new Exception($message);
    } catch (Exception $e) {
      $this->printException($message, $token);
      // exit(0);
    }
  }
 


  // Print out the exception. This might be an external class object.
  // This might be embedded in html tags as well.
  private function printException($message, $token) {
    $errorString =  "Error Message: " . $message;
    if($token != null) {
      $errorString .= "\nOn line: " . ($token['line'] + 1);
      $errorString .= "\nError: " . substr($this->codeArray[$token['line']], $token['offset']);
      $errorString .= "\n";
    }
    throw new Exception($errorString);
  }
} // ErrorHandler 


 ?>