<?php 
include_once('TuringModel.php');
class State extends TuringModel
{
    private $isStartState = false;
    private $isEndState = false;
    private $name;


    function __construct($requiredName)
    {
        $this->name = $requiredName;
    } // Constructor

    public function setAsStartState(){
        $this->isStartState = true;
    } // setAsStartState()

    public function setAsEndState(){
        $this->isEndState = true;
    } // setAsEndState()

    public function getName(){
        return $this->name;
    } // getName

    
} // State

