<?php 

/**
* Binds all the model and Service classes together.
* Compile the code and get the code parsed, validated in form of 
* nested arrays.
*/
require_once __DIR__ . '/../Require.php';
class CodeCompiler
{
  
  // The code the be parsed and compiled.
  private $code;

  // Model classes
  private $rulesArrayModel; 
  private $statesArrayModel;
  private $startStateModel;
  private $endStatesArrayModel;
  private $symbolsArrayModel;
  private $blankSymbolModel;
  private $tapesArrayModel;
  // private $tapeDataModel;

  function __construct($code)
  {
      $this->code = $code;
      $this->parseCode();
      $this->validateConsistency();
  } // construct.

  // Parse code.
  private function parseCode() {
      $parser = new Parser($this->code);
      $parser->parseCode();

      $this->rulesArrayModel = $parser->getRules();
      $this->statesArrayModel = $parser->getStates();
      $this->startStateModel = $parser->getStartState();
      $this->endStatesArrayModel = $parser->getEndState();
      $this->symbolsArrayModel = $parser->getSymbols();
      $this->blankSymbolModel = $parser->getBlankSymbol();
      $this->tapesArrayModel = $parser->getTapes();
      // $this->tapeDataModel = $parser->getTapeData();
  }


  private function validateConsistency(){

      $consistencyValidator = new ConsistencyValidator($this->code);

      $consistencyValidator->validateStates($this->startStateModel, $this->endStatesArrayModel, $this->statesArrayModel);
      $consistencyValidator->validateSymbols($this->blankSymbolModel, $this->symbolsArrayModel);
      $consistencyValidator->validateRules($this->rulesArrayModel, $this->statesArrayModel, $this->symbolsArrayModel, $this->tapesArrayModel);
      // Accounting for multiple tapes.
      foreach ($this->tapesArrayModel as $tape)
        $consistencyValidator->validateTape($tape, $this->symbolsArrayModel);
  } // foreach

  public function getArrays(){

      $outputFormatter = new OutputFormatter();
      // echo 'Here';
      return $outputFormatter->formatOutput($this->statesArrayModel,
                                            $this->startStateModel,
                                            $this->endStatesArrayModel,
                                            $this->symbolsArrayModel,
                                            $this->blankSymbolModel,
                                            $this->rulesArrayModel,
                                            $this->tapesArrayModel);
  } // getArrays()

} // class

//$code = "tapes =
//[
//tape1 , tape2  ]  ;
//
//states=[ s0  , s1 , s2 , HALT ] ;
//startState = s0 ; endStates = [ s1 , s2 ] ;
//symbols= [ 1 , 0 , # ] ;
//blankSymbol= # ;
//rules= [ ( s2 , 1@tape2 , s1   ,  1@tape2 , R@tape2 ) , ( s1, 0@tape2, s2, 1@tape2, L@tape2 ) ] ;
//tapeData=[ tape1(1 , 0 , 1 , 1 , 1 , 0 , 1 , 1),tape2(1, 1) ] ;";
//$codeCompiler = new CodeCompiler($code);
//$codeCompiler->getArrays();
//print_r($codeCompiler->getArrays());

