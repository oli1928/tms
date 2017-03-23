<?php

require_once (__DIR__ . '/../Require.php');

class ConsistencyValidator
{
    private $errorHandler;


    function __construct($code){

        $this->errorHandler = new ErrorHandler($code);
    } // Constructor

    // %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    // %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    public function validateStates($startState, $endStates, $states){

        // Check for duplication. --------------------------------------------------------------------------------------
        for ($index1 = 0; $index1 < sizeof($states); $index1++){
            for ($index2 = 0; $index2 < sizeof($states); $index2++){
                if (($states[$index1]->getName() == $states[$index2]->getName()) && ($index1 != $index2)){
                    // Error handling.
                    $this->errorHandler->errHandle("A state is duplicated.", $states[$index1]->getError());
                } // if
            } // for
        } // for

        // Check that the start state exist. ---------------------------------------------------------------------------
        $startStateExists = false;
        for ($index = 0; $index < sizeof($states); $index++){
            if($startState->getName() == $states[$index]->getName())
                $startStateExists = true;
        } // for
        if (!$startStateExists){
            // Error handling.
            $this->errorHandler->errHandle("Invalid start state.", $startState->getError());
        } // if

        // Check that the end states exist. ----------------------------------------------------------------------------
        for ($indexOfEndStates = 0;$indexOfEndStates < sizeof($endStates);$indexOfEndStates++) {
            $currentEndStateExists = false;
            for ($indexOfStates = 0; $indexOfStates < sizeof($states); $indexOfStates++) {
                if ($endStates[$indexOfEndStates]->getName() == $states[$indexOfStates]->getName())
                    $currentEndStateExists = true;
            } // for
            if (!$currentEndStateExists){
                // Error handling.
                $this->errorHandler->errHandle("Invalid end state.", $endStates[$indexOfEndStates]->getError());

            } // if
        } // for
        // -------------------------------------------------------------------------------------------------------------

    } // validateStates()


    // %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    // %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    public function validateSymbols($blankSymbol, $symbols){

        // Check for duplication. --------------------------------------------------------------------------------------
        for ($index1 = 0; $index1 < sizeof($symbols); $index1++){
            for ($index2 = 0; $index2 < sizeof($symbols); $index2++){
                if (($symbols[$index1]->getChar() == $symbols[$index2]->getChar()) && ($index1 != $index2)){
                    // Error handling.
                    $this->errorHandler->errHandle("A symbol is duplicated.", $symbols[$index1]->getError());
                } // if
            } // for
        } // for

        // Checking the blank symbol exists. ---------------------------------------------------------------------------
        $blankSymbolExists = false;
        for ($index = 0; $index < sizeof($symbols); $index++){
            if ($blankSymbol->getChar() == $symbols[$index]->getChar())
                $blankSymbolExists = true;
        } // for
        if (!$blankSymbolExists) {
            // Error handling.
            $this->errorHandler->errHandle("Invalid blank symbol.", $blankSymbol->getError());
        } // if

    } // validateEndStates() -------------------------------------------------------------------------------------------


    // %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    // %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    public function validateRules($rules, $states, $symbols, $tapes){

        // Cycling through all rules.
        foreach ($rules as $rule){

            // Validate rule state. ------------------------------------------------------------------------------------
            $ruleStateExists = false;
            for ($index = 0; $index < sizeof($states); $index++) {
                if ($rule->getRuleState()->getName() == $states[$index]->getName())
                    $ruleStateExists = true;
            } // for
            if (!$ruleStateExists){
                // ErrorHandler
                // (" The rule state is not valid ")
                $this->errorHandler->errHandle("Invalid rule state of rule.", $rule->getRuleState()->getError());

            } // if

            // Validate symbol. ----------------------------------------------------------------------------------------
            $currentSymbolExists = false;
            $currentTapeExists = false;

            $symbolAndTape = explode('@', $rule->getCurrentSymbol()->getChar());
            $rawSymbol = $symbolAndTape[0];
            $whichTape = $symbolAndTape[1];

            for ($index = 0; $index < sizeof($symbols); $index++) {
                if ($rawSymbol == $symbols[$index]->getChar())
                    $currentSymbolExists = true;
            } // for
            for ($index = 0; $index < sizeof($tapes); $index++) {
                if ($whichTape == $tapes[$index]->getName())
                    $currentTapeExists = true;
            } // for

            if (!$currentSymbolExists) {
                $this->errorHandler->errHandle("Invalid current symbol of rule.", $rule->getCurrentSymbol()->getError());
            } // if
            if (!$currentTapeExists) {
                $this->errorHandler->errHandle("Invalid tape of current symbol.", $rule->getCurrentSymbol()->getError());
            } // if

            // validate next state. ------------------------------------------------------------------------------------
            $nextStateExists = false;
            for ($index = 0; $index < sizeof($states); $index++) {
                if ($rule->getNextState()->getName() == $states[$index]->getName())
                    $nextStateExists = true;
            } // for
            if (!$nextStateExists) {
                // ErrorHandler
                // (" The next state is not valid ")
                $this->errorHandler->errHandle("Invalid next state of rule.", $rule->getNextState()->getError());
            } // if

            // validate next symbol. -----------------------------------------------------------------------------------
            $nextSymbolExists = false;
            $nextTapeExists = false;

            $symbolAndTape = explode('@', $rule->getNextSymbol()->getChar());
            $rawSymbol = $symbolAndTape[0];
            $whichTape = $symbolAndTape[1];

            for ($index = 0; $index < sizeof($symbols); $index++) {
                if ($rawSymbol == $symbols[$index]->getChar())
                    $nextSymbolExists = true;
            } // for
            for ($index = 0; $index < sizeof($tapes); $index++) {
                if ($whichTape == $tapes[$index]->getName())
                    $nextTapeExists = true;
            } // for

            if (!$nextSymbolExists) {
                $this->errorHandler->errHandle("Invalid next symbol: ".$rawSymbol.": invalid symbol.", $rule->getNextSymbol()->getError());
            } // if
            if (!$nextTapeExists) {
                $this->errorHandler->errHandle("Invalid next symbol. ".$whichTape.": invalid tape.", $rule->getNextSymbol()->getError());
            } // if

            // validate move instruction tape. -------------------------------------------------------------------------
            $tapeExists = false;

            $moveInstructionAndTape = explode('@', $rule->getMoveInstruction()->getInstruction());
            $whichTape = $moveInstructionAndTape[1];

            for ($index = 0; $index < sizeof($tapes); $index++) {
                if ($whichTape == $tapes[$index]->getName())
                    $tapeExists = true;
            } // for

            if (!$tapeExists){
                    $this->errorHandler->errHandle("Invalid move instruction: ".$whichTape." invalid tape.", $rule->getMoveInstruction()->getError());
            } // if
        } // foreach
    } // validateRules()

    // %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    // %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    public function validateTape($tape, $symbols){

        // Checking each symbol in the tape exists.
        // For loop cycling through each symbol in the tape.
        for ($indexOfTape = 0; $indexOfTape < sizeof($tape->getTapeData()); $indexOfTape++){
            $symbolInTapeExists = false;
            // For loop cycling through each symbol of $symbols and comparing them with the current symbol in $tape
            for ($indexOfSymbols = 0; $indexOfSymbols < sizeof($symbols); $indexOfSymbols++){
                if ($tape->getTapeData()[$indexOfTape]->getChar() == $symbols[$indexOfSymbols]->getChar())
                    $symbolInTapeExists = true;
            }
            if (!$symbolInTapeExists){
                $this->errorHandler->errHandle("Invalid symbol in ".$tape->getName(), $tape->getTapeData()[$indexOfTape]->getError());
            } // if
        } // for


    } // validateTape()
} // ConsistencyValidator
