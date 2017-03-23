<?php 
include_once('TuringModel.php');
class Rule extends TuringModel
{
    private $ruleState;
    private $currentSymbol;
    private $nextState;
    private $nextSymbol;
    private $moveInstruction;

    function __construct(State$requiredRuleState, Symbol$requiredCurrentSymbol, State$requiredNewState,
                         Symbol$requiredNewSymbol, MoveInstruction$requiredMoveInstruction)
    {
        $this->ruleState = $requiredRuleState;

        $this->currentSymbol = $requiredCurrentSymbol;
        $this->nextState = $requiredNewState;
        $this->nextSymbol = $requiredNewSymbol;
        $this->moveInstruction = $requiredMoveInstruction;
    } // Constructor

    public function toStringArray(){

        $arr = array(5);
        $arr[0] = $this->ruleState->getName();
        $arr[1] = $this->currentSymbol->getChar();
        $arr[2] = $this->nextState->getName();
        $arr[3] = $this->nextSymbol->getChar();
        $arr[4] = $this->moveInstruction->getInstruction();

        return $arr;
    } // toStringArray()

    public function getRuleState(){
        return $this->ruleState;
    } // getRuleState

    public function getCurrentSymbol(){
        return $this->currentSymbol;
    } // getCurrentSymbol

    public function getNextState(){
        return $this->nextState;
    } // getNextState

    public function getNextSymbol(){
        return $this->nextSymbol;
    } // getNextSymbol

    public function getMoveInstruction(){
        return $this->moveInstruction;
    } // getMoveInstruction


} // Rule



