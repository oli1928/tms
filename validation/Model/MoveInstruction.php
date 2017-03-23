<?php 
include_once('TuringModel.php');

class MoveInstruction extends TuringModel{

    private $instruction;

    function __construct($requiredInstruction)
    {
        $this->instruction = $requiredInstruction;
    } // Constructor

    public function getInstruction(){
        return $this->instruction;
    } // getInstruction()

                          /*  public function isValidMoveInstruction(){
                                if ($this->instruction == "L" || $this->instruction == "R")
                                    return true;
                                else
                                    return false;
                         } // isValidMoveInstruction()   */
} // MoveInstruction

