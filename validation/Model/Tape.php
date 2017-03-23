<?php 
include_once('TuringModel.php');
class Tape extends TuringModel
{
    private $tapeData = array();
    private $tapeName;

    function __construct($requiredName){
        $this->tapeName = $requiredName;
    } // Constructor

    public function setTapeData($requiredTapeData){
        $this->tapeData = $requiredTapeData;
    } // setTapeData()

    public function getName(){
        return $this->tapeName;
    } // getName()

    public function getTapeData(){
        return $this->tapeData;
    } // getTapeArray()

    public function toStringArray(){
        $stringArray = array();
        $index = 0;
        foreach($this->tapeData as $symbol) {
            $stringArray[$index] = $symbol->getChar();
            $index++;
        } // foreach
        return $stringArray;
    }
    
} // Tape

