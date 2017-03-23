<?php 
include_once('TuringModel.php');
class Symbol extends TuringModel
{
    private $isHaltSymbol = false;
    private $character;

    function __construct($requiredCharacter)
    {
        $this->character = $requiredCharacter;
    } // Constructor

    public function setAsHaltSymbol(){
        $this->isHaltSymbol = true;
    } // setAsHaltSymbol()

    public function getChar(){
        return $this->character;
    } // getChar

    
} // Symbol

