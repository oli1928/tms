<?php 

/**
* Parse the user code and set the error handler
* Gets the Token and matches it to the corresponding command,
* in order to get the related regular expression to parse the right 
* hand operand.
*/

require_once __DIR__ . '/../Require.php';

class Parser
{

  // All the models are declared here.
  // done
  private $rulesArrayModel; 
  private $statesArrayModel;
  private $startStateModel;
  private $endStatesArrayModel;
  private $symbolsArrayModel;
  private $blankSymbolModel;
  private $tapesArrayModel;

  // The users code for turing machine.
  private $code;

  // Object used to handle 
  private $errorHandler;
  
  function __construct($code)
  {
    $this->code = $code;
    $this->errorHandler = new ErrorHandler($code);
  }

  // Parse Code and get each command and call the function matchCommand()
  // to get correct regular expression to parse each right hand operand.
  public function parseCode() {
    $lexer = new Lexer($this->code);
    while($lexer->hasNextToken()) {
      $token = $lexer->getToken();
      $this->matchCommand($token);
    } // while
  } // parseCode

  // Recognise the command and invoke the relative function.
  public function matchCommand($token) {
    $command = $token['command']; 
    $commandName = key($command);
    $commandValue = $command[$commandName];

    // Error array, used to ouput errors in this phase and later ones.
    $error = array('line' => $token['line'], 'offset' => $token['offset']);

    // Make all the lettere to lower because switch is case sensitive.
    $commandName = strtolower($commandName);

    switch ($commandName) {
      case 'states':
        $this->parseStates($token);
        break;
      case 'tapes':
        $this->parseTapes($token);
        break;
      case 'startstate':
        $this->parseStartState($token);
        break;
      case 'endstates':
        $this->parseEndStates($token);
        break;
      case 'rules':
        $this->parseRules($token);
        break;
      case 'symbols':
        $this->parseSymbols($token);
        break;
      case 'blanksymbol':
        $this->parseBlankSymbol($token);
        break;
      case 'tapedata':
        $this->parseTapeData($token);
        break;
      default:
        // @ErrorHandler
      $this->errorHandler->errHandle("No command found matching to the given one: " . $commandName,
                                     $error);
        // echo 'No command found matching to the given one.' . $commandName;
        break;
    } // switch
  }

  // Parses any statement of type bidimensional array,
  // Returns:
  // - bidimensional Array if parsing was succesfull.
  // - -2 if something went wrong during parsing.
  // - -1 if the syntax is not correct.
  // @$value: string to parse (in array syntax)
  // @$valueTypeRegex: the type of each element of the array. (in terms of Regex)
  private function parse2dArray($value, $valueTypes) {
    $arraySyntax = array(
      'whitespace' => '(?:\s)',
      'any_whitespace' => '(?:\s*)',
      'left_external_bracket' => '\[',
      'right_external_bracket' => '\]',
      'left_bracket' => '\(',
      'right_bracket' => '\)',
      'comma' => ',',
      'state' => '('. $valueTypes["state"] .')',
      'symbol' => '('. $valueTypes["symbol"] .')',
      'direction' => '('. $valueTypes["direction"] .')'
    );
    
    // Used to see if the states are defined in the correct syntax
    // State, symbol, state, symbol, (L,R, Nothing)
    // /^ \[ (?:\s*)(ValueTypeRegex)((?:\s*),(?:\s*)(ValueTypeRegex))*(?:\s*)\]/
    $arrayPattern = '/^' . $arraySyntax["left_external_bracket"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["left_bracket"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["state"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["symbol"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["state"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["symbol"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["direction"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["right_bracket"]
                      . '(' . $arraySyntax["any_whitespace"]
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["left_bracket"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["state"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["symbol"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["state"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["symbol"]
                      . $arraySyntax["any_whitespace"]
                      . '(' . $arraySyntax["comma"] . '?'
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["direction"] . ')'
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["right_bracket"]
                      . $arraySyntax["any_whitespace"] .')*'
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["right_external_bracket"] . '/i';

    // (\w+)\s*,\s*([^,;\s]+)\s*,\s*(\w+)\s*,\s*([^,;\s]+)\s*,?\s*(L|R|)
    // Pattern used to extract elements of the array
    $arrayExtractElementsPattern = '/^' . $arraySyntax["any_whitespace"]
                                   . $arraySyntax["state"]
                                   . $arraySyntax["any_whitespace"]
                                   . $arraySyntax["comma"]
                                   . $arraySyntax["any_whitespace"]
                                   . $arraySyntax["symbol"]
                                   . $arraySyntax["any_whitespace"]
                                   . $arraySyntax["comma"]
                                   . $arraySyntax["any_whitespace"]
                                   . $arraySyntax["state"]
                                   . $arraySyntax["any_whitespace"]
                                   . $arraySyntax["comma"]
                                   . $arraySyntax["any_whitespace"]
                                   . $arraySyntax["symbol"]
                                   . $arraySyntax["any_whitespace"]
                                   . $arraySyntax["comma"] . '?'
                                   . $arraySyntax["any_whitespace"]
                                   . '('. $valueTypes["direction"] .')' . '/i';

    // Array containing all the different elements of the Array.
    $rules = array();

    if(preg_match_all($arrayPattern, $value, $matches)) {
      // If the syntax is valid, then extract the elements.
      if(preg_match_all("/\((.*?)\)/s", $value, $matches)) {

        foreach ($matches[1] as $match) {
          if(preg_match_all($arrayExtractElementsPattern, $match, $matches2)) {
            $rules[] = array_slice($matches2, 1);
          } // if
          else 
            return -2;
        } // foreach
      } // if
      else 
        return -2;
    } // if
    else 
      return -1;
    return $rules;
  } // parse2dArray

  // Parses any statement of type array,
  // Returns:
  // - Array if parsing was succesfull.
  // - -2 if something went wrong during parsing.
  // - -1 if the syntax is not correct.
  // @$value: string to parse (in array syntax)
  // @$valueTypeRegex: the type of each element of the array. (in terms of Regex)
  private function parseArray($value, $valueTypeRegex) {
    $arraySyntax = array(
      'whitespace' => '(?:\s)',
      'any_whitespace' => '(?:\s*)',
      'left_bracket' => '\[',
      'right_bracket' => '\]',
      'comma' => ',',
      'value_type_regex' => '('. $valueTypeRegex .')',
    );

    // Used to see if the states are defined in the correct syntax
    // /^\[(?:\s*)(ValueTypeRegex)((?:\s*),(?:\s*)(ValueTypeRegex))*(?:\s*)\]/
    $arrayPattern = '/^' . $arraySyntax["left_bracket"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["value_type_regex"]
                      . '(' . $arraySyntax["any_whitespace"] 
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["value_type_regex"] . ')*' 
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["right_bracket"] . '/';

    // Pattern used to extract elements of the array
    $arrayExtractElementsPattern = $arraySyntax['value_type_regex'];

    // Array containing all the different elements of the Array.
    $elements = array();

    if(preg_match_all($arrayPattern, $value, $matches)) {
      // If the syntax is valid, then extract the elements.
      if(preg_match_all($arrayExtractElementsPattern, $value, $matches)) {
        return $elements = $matches[0];
      } // if
      else 
        return -2;
    } // if
    else 
      return -1;
  } // parseArray

  // Parse single element statements.
  // Returns:
  // - One element if parsing was succesfull.
  // - -1 if the syntax is not correct. (in this only the value type is not correct)
  // @$value: string to parse (in array syntax)
  // @$valueTypeRegex: the type of each element of the array. (in terms of Regex)
  private function parseSingleStatement($value, $valueTypeRegex) {
    $singleStatementSyntax = array(
      'whitespace' => '(?:\s)',
      'any_whitespace' => '(?:\s*)',
      'value_type_regex' => '('. $valueTypeRegex .')',
    );

    $element; 

    $singleStatementPattern = '/^' . $singleStatementSyntax["any_whitespace"]
                      . $singleStatementSyntax["value_type_regex"]
                      . $singleStatementSyntax["any_whitespace"] . '/';
    // Execute the pattern to get the matches.
    if(preg_match_all($singleStatementPattern, $value, $matches)) {
      return $element = $matches[1][0];
    } // if
    else {
      return -1;
    } // else
                      
  } // parseSingleStatement

  private function parseStates($token) {
    $command = $token['command']; 
    $commandName = key($command);
    $commandValue = $command[$commandName];

    // Error array, used to ouput errors in this phase and later ones.
    $error = array('line' => $token['line'], 'offset' => $token['offset']);

    // @debug
    // The states array.
    $states = array();

    $stateTypeRegex = "\w+";

    $states = $this->parseArray($commandValue, $stateTypeRegex);  
    // print_r($states);
    // Execute the pattern to get the matches.
    if($states == -1) {
      // @ErrorHandler
       $this->errorHandler->errHandle("States are not defined correctly.",
                                     $error);
      // echo "States are not defined correctly."
      } // if

    // If the syntax is valid, then extract the states.
    if($states == -2) {
      // @ErrorHandler
      $this->errorHandler->errHandle("Something unexpected happened during the parsing of 'states'",
                                     $error);
      // echo "Something unexpected happened during the parsing of 'states'
    } // if
    $this->statesArrayModel = array();
    foreach ($states as $index=>$state) {
      $this->statesArrayModel[$index] = new State($state);
      $this->statesArrayModel[$index]->setError($error);
    }
    // print_r($this->statesArrayModel);
  } // parseStates

  private function parseTapes($token) {
    $command = $token['command']; 
    $commandName = key($command);
    $commandValue = $command[$commandName];

    // Error array, used to ouput errors in this phase and later ones.
    $error = array('line' => $token['line'], 'offset' => $token['offset']);

    // The states array.
    $tapes = array();


    $tapeTypeRegex = "\w+";

    $tapes = $this->parseArray($commandValue, $tapeTypeRegex);  

    // print_r($tapes);

    // Execute the pattern to get the matches.
    if($tapes == -1) {
      // @ErrorHandler
       $this->errorHandler->errHandle("tapes are not defined correctly",
                                     $error);
      // echo "tapes are not defined correctly."
      } // if

    // If the syntax is valid, then extract the tapes.
    if($tapes == -2) {
      // @ErrorHandler
      $this->errorHandler->errHandle("Something unexpected happened during the parsing of 'tapes'",
                                     $error);
      // echo "Something unexpected happened during the parsing of 'tapes'
    } // if

    $this->tapesArrayModel = array();

    // @debug: Ask Matthew too add tapes class model.
    foreach ($tapes as $index=>$tape) {
      $this->tapesArrayModel[$index] = new Tape($tape);
      $this->tapesArrayModel[$index]->setError($error);

    }
    // echo "\nPrinting tapes: ";
    // print_r($this->tapesArrayModel);
    // echo "\n";
  } // parseTapes

  private function parseStartState($token) {
    $command = $token['command']; 
    $commandName = key($command);
    $commandValue = $command[$commandName];

    // Error array, used to ouput errors in this phase and later ones.
    $error = array('line' => $token['line'], 'offset' => $token['offset']);
    // The startState.
    $startState;
    
    $startTypeRegex = "\w+";

    $startState = $this->parseSingleStatement($commandValue, $startTypeRegex);
    // print_r($startState);
    // printf("\n");
    // Execute the pattern to get the matches.
    if($startState == -1) {
      // @ErrorHandler
      $this->errorHandler->errHandle("Start state non declared correctly.",
                                     $error);
      // echo "Start state non declared correctly. "
    }
    $this->startStateModel = new State($startState);
    $this->startStateModel->setError($error);
    // print_r($this->startStateModel);
  } // parseStartState

  // endStates
  // Syntax very similar to states one.
  private function parseEndStates($token) {
    $command = $token['command']; 
    $commandName = key($command);
    $commandValue = $command[$commandName];

    // Error array, used to ouput errors in this phase and later ones.
    $error = array('line' => $token['line'], 'offset' => $token['offset']);

    // The end states array.
    $endStates = array();
    
    $endStateTypeRegex = "\w+";
    
    $endStates = $this->parseArray($commandValue, $endStateTypeRegex);  

    // print_r($endStates);
    // Execute the pattern to get the matches.
    if($endStates == -1) {
      // @ErrorHandler
       $this->errorHandler->errHandle("End States are not defined correctly.",
                                     $error);
      // echo "End States are not defined correctly."
      } // if

    // If the syntax is valid, then extract the states.
    if($endStates == -2) {
      // @ErrorHandler
      $this->errorHandler->errHandle("Something unexpected happened during the parsing of 'end states'",
                                     $error);
      // echo "Something unexpected happened during the parsing of 'end states'
    } // if
    $this->endStatesArrayModel = array();
    foreach ($endStates as $index=>$endState) {
      $this->endStatesArrayModel[$index] = new State($endState);
      $this->endStatesArrayModel[$index]->setError($error);
    }
    // print_r($this->endStatesArrayModel);
  } // parseEndStates

  // Get the symbols used for the Turing machine.
  // Same syntax of EndState and state
  private function parseSymbols($token) {
    $command = $token['command']; 
    $commandName = key($command);
    $commandValue = $command[$commandName];

    // Error array, used to ouput errors in this phase and later ones.
    $error = array('line' => $token['line'], 'offset' => $token['offset']);

    // The symbols array.
    $symbols = array();
    
    $symbolTypeRegex = "[^,;\s()\[\]=@]+";
    
    $symbols = $this->parseArray($commandValue, $symbolTypeRegex);  

    // print_r($symbols);
    // Execute the pattern to get the matches.
    if($symbols == -1) {
      // @ErrorHandler
      $this->errorHandler->errHandle("Symbols are not defined correctly.",
                                     $error);
      // echo "Symbols are not defined correctly."
      } // if

    // If the syntax is valid, then extract the states.
    if($symbols == -2) {
      // @ErrorHandler
      $this->errorHandler->errHandle("Something unexpected happened during the parsing of 'symbols'",
                                     $error);
      // echo "Something unexpected happened during the parsing of 'symbols'
    } // if
    $this->symbolsArrayModel = array();
    foreach ($symbols as $index=>$symbol) {
      $this->symbolsArrayModel[$index] = new Symbol($symbol);
      $this->symbolsArrayModel[$index]->setError($error);
    }
    // print_r($this->symbolsArrayModel);
  } // parseSymbols

  // parse blankSymbol
  // @debug: suppose is a single element
  private function parseBlankSymbol($token) {
    $command = $token['command']; 
    $commandName = key($command);
    $commandValue = $command[$commandName];

    // Error array, used to ouput errors in this phase and later ones.
    $error = array('line' => $token['line'], 'offset' => $token['offset']);

    // The blankSymbol.
    $blankSymbol = array();
    
    $startTypeRegex = "[^,;\s()\[\]=@]+";

    $blankSymbol = $this->parseSingleStatement($commandValue, $startTypeRegex);
    
    // print_r($blankSymbol);
    // printf("\n");
    // Execute the pattern to get the matches.
    if($blankSymbol == -1) {
      // @ErrorHandler
      $this->errorHandler->errHandle("BlankSymbol state non declared correctly.",
                                     $error);
      // echo "BlankSymbol state non declared correctly. "
    }
    $this->blankSymbolModel = new Symbol($blankSymbol);
    $this->blankSymbolModel->setError($error);
    // print_r($this->blankSymbolModel);
  } // parseBlankSymbol

  


  // Parse the rules 
  // Get all the rules from the rules statement string.
  private function parseRules($token){
    $command = $token['command']; 
    $commandName = key($command);
    $commandValue = $command[$commandName];

    // Error array, used to ouput errors in this phase and later ones.
    $error = array('line' => $token['line'], 'offset' => $token['offset']);

    $valueTypesRegex = array(
      'state' => '\w+',
      'symbol' => "[^,;\s()\[\]=@]+"."@\w+",
      'direction' => "(L|R)@\w+"
      );

    $rules = $this->parse2dArray($commandValue, $valueTypesRegex);

    // var_dump($rules);
    /*
      \[(?:\s*) \((?:\s*) (\w+) (?:\s*) , (?:\s*) ([^;,\s]+) (?:\s*) , (?:\s*) (\w+) (?:\s*) , (?:\s*) ([^,;]) (?:\s*) (?|(,? (?:\s*) (L|R))|) (?:\s*) \)((?:\s*) , (?:\s*) \((?:\s*) (\w+) (?:\s*) , (?:\s*) ([^;,]) (?:\s*) , (?:\s*) (\w+) (?:\s*) , (?:\s*) ([^,;]) (?:\s*) (?|(,? (?:\s*) (L|R))|) (?:\s*) \) (?:\s*) )* (?:\s*) \]
    */
    // Execute the pattern to get the matches.
    if($rules == -1) {
      // @ErrorHandler
      $this->errorHandler->errHandle("Rules are not defined correctly.",
                                     $error);
      // echo "Symbols are not defined correctly."
      } // if

    // If the syntax is valid, then extract the states.
    if($rules == -2) {
      // @ErrorHandler
      $this->errorHandler->errHandle("Something unexpected happened during the parsing of 'rules'", $error); 
      // echo "Something unexpected happened during the parsing of 'symbols'
    } // if
    
    $this->rulesArrayModel = array();

    foreach ($rules as $index=>$rule) {

      $currentState = new State($rule[0][0]);
        $currentState->setError($error);
      $currentSymbol = new Symbol($rule[1][0]);
        $currentSymbol->setError($error);
      $nextState = new State($rule[2][0]);
        $nextState->setError($error);
      $nextSymbol = new Symbol($rule[3][0]);
        $nextSymbol->setError($error);
      $nextDirection = new MoveInstruction($rule[4][0]);
        $nextDirection->setError($error);
          
      $this->rulesArrayModel[$index] = new Rule($currentState, $currentSymbol, $nextState, $nextSymbol, $nextDirection);
      $this->rulesArrayModel[$index]->setError($error);
    } // foreach
    // print_r($this->rulesArrayModel);
  } // parseRules



  private function parseTape2dArray($value, $valueTypes) {
    $arraySyntax = array(
      'whitespace' => '(?:\s)',
      'any_whitespace' => '(?:\s*)',
      'left_external_bracket' => '\[',
      'right_external_bracket' => '\]',
      'left_bracket' => '\(',
      'right_bracket' => '\)',
      'comma' => ',',
      'tape' => '('. $valueTypes["tape"] .')',
      'symbol' => '('. $valueTypes["symbol"] .')'
    );
    
    // Used to see if the states are defined in the correct syntax
    // State, symbol, state, symbol, (L,R, Nothing)
    // ^\[(?:\s*)(\w+)(?:\s*)\((?:\s*)([^,;\s()\[\]=@]+)((?:\s*),(?:\s*)
    // ([^,;\s()\[\]=@]+))*(?:\s*)\)((?:\s*),(?:\s*)(\w+)(?:\s*)\((?:\s*)
    // ([^,;\s()\[\]=@]+)((?:\s*),(?:\s*)([^,;\s()\[\]=@]+))*(?:\s*)\)(?:\s*))*(?:\s*)\]
    $arrayPattern = '/^' . $arraySyntax["left_external_bracket"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["tape"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["left_bracket"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["symbol"]
                      . '(' . $arraySyntax["any_whitespace"] 
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["symbol"] . ')*' 
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["right_bracket"]
                      . '(' . $arraySyntax["any_whitespace"]
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["tape"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["left_bracket"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["symbol"]
                      . '(' . $arraySyntax["any_whitespace"] 
                      . $arraySyntax["comma"]
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["symbol"] . ')*' 
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["right_bracket"]
                      . $arraySyntax["any_whitespace"] .')*'
                      . $arraySyntax["any_whitespace"]
                      . $arraySyntax["right_external_bracket"] . '/i';

    // 
    // Pattern used to extract elements of the array
    $arrayExtractTapesPattern = '/' . '(' .  $arraySyntax["tape"] . ')'
                                   . $arraySyntax["any_whitespace"]
                                   . $arraySyntax["left_bracket"] . '/';

    $arrayExtractTapeDataPattern = '/' . '(' . $arraySyntax["symbol"] . ')' . '/';

    // Array containing all the different elements of the Array.
    $tapeData = array();

    if(preg_match_all($arrayPattern, $value, $matches)) {
      // If the syntax is valid, then extract the elements.
      // First get the tapes and form an array.
      if(preg_match_all($arrayExtractTapesPattern, $value, $matches)) {
        $tapes = $matches[1];
        // print_r($tapes);
        // Extract the tapes data 
        if(preg_match_all('/\((.*?)\)/s', $value, $matches)) {
              $raw_symbols = $matches[1];
          for ($i=0; $i < sizeof($raw_symbols); $i++) { 
            if(preg_match_all($arrayExtractTapeDataPattern, $raw_symbols[$i], $matches2)) {
              $tapeData[$tapes[$i]] = $matches2[1];
            } // if
            else 
              return -2;
          }
        } // if
        else 
          return -2;
      } // if
      else 
        return -2;
    } // if
    else 
      return -1;
    return $tapeData;
  }

  // parse tapeData
  // @debug: suppose is a single element
  private function parseTapeData($token) {
    $command = $token['command']; 
    $commandName = key($command);
    $commandValue = $command[$commandName];
    
    // The the array is complex.
    $tapesData;

    $tapeDataTypesRegex = array(
      'tape' => '\w+',
      'symbol' => "[^,;\s()\[\]=@]+",
      );

    $tapesData = $this->parseTape2dArray($commandValue, $tapeDataTypesRegex);

    // Error array, used to ouput errors in this phase and later ones.
    $error = array('line' => $token['line'], 'offset' => $token['offset']);
    
    // print_r($tapesData);
    // printf("\n");
    // Execute the pattern to get the matches.
    if($tapesData == -1) {
      // @ErrorHandler
      $this->errorHandler->errHandle("tapeData is not defined correctly.",
                                     $error);
      // echo "tapesData state non declared correctly. "
    }
    if($tapesData == -2) {
      // @ErrorHandler
      $this->errorHandler->errHandle("tapeData unexpected error during parsing.",
                                     $error);
      // echo "tapesData unexpected error during parsing.. "      
    }

    // Create the model object.
    foreach ($this->tapesArrayModel as $index => $tapesModel) {
      // Check if there exits such tape.
      if(isset($tapesData[$tapesModel->getName()])) {
        $symbolArrayModel = array();
        for ($i=0; $i < sizeof($tapesData[$tapesModel->getName()]); $i++) { 
          $symbolArrayModel[$i] = new Symbol($tapesData[$tapesModel->getName()][$i]);
            $symbolArrayModel[$i]->setError($error);
        } // for
        // Set the new symbol array in tapesModel and the error
        $tapesModel->setTapeData($symbolArrayModel);
          $tapesModel->setError($error);
      } // if
    } // foreach

    // print_r($tapesData);
    // print_r($this->tapesArrayModel);
  } // parseTapeData


  // Getters for every model variable.

  public function getRules() {
    return $this->rulesArrayModel;
  } // getRules
  public function getStates() {
    return $this->statesArrayModel; 
  } // getStates
  public function getStartState() {
    return $this->startStateModel; 
  } // getStartState
  public function getEndState() {
    return $this->endStatesArrayModel; 
  } // getEndState
  public function getSymbols() {
    return $this->symbolsArrayModel; 
  } // getSymbols
  public function getBlankSymbol() {
    return $this->blankSymbolModel; 
  } // getBlankSymbol
  public function getTapeData() {
    return $this->tapeDataModel; 
  } // getTapeData
  public function getTapes() {
    return $this->tapesArrayModel; 
  } // getTapes
} // Parser
$code = "tapes = 
[  
tape1 , tape2  ]  ;

states=[ s0  , s1 , s2 , HALT ] ;
startState = s0 ; endStates = [ s1 , s2 ] ;
symbols= [ 1 , 0 , # ] ;
blankSymbol= 0 ;
rules= [ ( s0 , 1@tape1 , s2   ,  1@tap3 , R@tape4 ) , ( s1, 0@tape4, s3, 1@tape4, L@tape4 ) ] ;
tapeData=[ tape1(1 , 0 , 1 , 1 , 1 , 0 , 1 , 1),tape2(1,2) ] ;";
// $parse = new Parser($code);
// $parse->parseCode();



 ?>