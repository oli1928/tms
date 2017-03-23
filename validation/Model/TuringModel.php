<?php 
/**
* The root class for tms models. Every model will extend this class.
*/
class TuringModel 
{
  
  private $error;
  
  public function setError($error) {
    $this->error = $error;
  } // setError

  public function getError() {
    return $this->error;
  } // getError
}


 ?>