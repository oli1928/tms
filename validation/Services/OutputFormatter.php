<?php

class OutputFormatter
{
    function __construct()
    {
    }

    public function formatOutput($allStates, $startState, $endStates, $symbols, $blankSymbol, $rules, $tapes){

        $output = array(8);

        // Dealing with the states -------------------------------------------------------------------------------------
        $arrayOfStateNames = array(sizeof($allStates));
        // Getting the name of every state.
        for ($index = 0; $index < sizeof($allStates); $index++)
            $arrayOfStateNames[$index] = $allStates[$index]->getName();
        $output[0] = $arrayOfStateNames;


        // Dealing with start state ------------------------------------------------------------------------------------
        $output[1] = $startState->getName();


        // Dealing with end states -------------------------------------------------------------------------------------
        $arrayOfEndStateNames = array(sizeof($endStates));
        // Getting the name of every end state.
        for ($index = 0; $index < sizeof($endStates); $index++)
            $arrayOfEndStateNames[$index] = $endStates[$index]->getName();
        $output[2] = $arrayOfEndStateNames;


        // Dealing with symbols ----------------------------------------------------------------------------------------
        $arrayOfSymbols = array(sizeof($symbols));
        // Getting the name of every end state.
        for ($index = 0; $index < sizeof($symbols); $index++)
            $arrayOfSymbols[$index] = $symbols[$index]->getChar();
        $output[3] = $arrayOfSymbols;


        // Dealing with the blank symbol -------------------------------------------------------------------------------
        $output[4] = $blankSymbol->getChar();


        // Dealing with rules ------------------------------------------------------------------------------------------
        $arrayOfRules = array(sizeOf($rules));
        for ($index = 0; $index < sizeof($rules); $index++)
            $arrayOfRules[$index] = $rules[$index]->toStringArray();
        $output[5] = $arrayOfRules;


        // Dealing with tape names. ------------------------------------------------------------------------------------
        $arrayOfTapeNames = array(sizeof($tapes));
        // Getting the name of every tape.
        for ($index = 0; $index < sizeof($tapes); $index++)
            $arrayOfTapeNames[$index] = $tapes[$index]->getName();
        $output[6] = $arrayOfTapeNames;


        // Dealing with tape data. -------------------------------------------------------------------------------------
        $arrayOfTapesAsStrings = array();
        $index = 0;
        foreach($tapes as $tape){
            $arrayOfTapesAsStrings[$index] = $tape->toStringArray();
            $index++;
        } // foreach
        $output[7] = $arrayOfTapesAsStrings;


        return $output;
    } // formatOutput()
} // OutputFormatter

