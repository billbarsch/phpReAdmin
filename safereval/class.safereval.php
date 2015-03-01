<?php                             

/*
* Class: Safer eval()
* Version: 0.2 (Alpha)
* Web: http://evileval.sourceforge.net/
* License: GPL
*
*/

class SaferEval {
	var $source, $allowedCalls, $allowedTokens, $allowedVariables, $disallowedExpressions;
   
	function SaferEval() {
		global $allowedCalls;
		global $allowedTokens;
		global $globalVariables;
		global $allowedVariables;
		global $disallowedExpressions;
		$this->allowedCalls = $allowedCalls;
		$this->allowedTokens = $allowedTokens;
		$this->globalVariables = $globalVariables;
		$this->allowedVariables = $allowedVariables;
		$this->disallowedExpressions = $disallowedExpressions;
	}

	function evalSyntax($code) { // Separate function for checking syntax without breaking the script
		ob_start(); // Catch potential parse error messages
		$code = eval('if(0){' . "\n" . $code . "\n" . '}'); // Put $code in a dead code sandbox to prevent its execution
		ob_end_clean();
		return $code !== false;
	}

	function checkScript($code, $execute) {
		$this->execute = $execute;
		$this->code = $code;
		$this->tokens = token_get_all('<?php '.$this->code.' ?>');
		$this->errors = array();
		$this->braces = 0;

		// STEP 1: SYNTAX - Check if braces are balanced
		foreach ($this->tokens as $token) {
			if ($token == '{') $this->braces = $this->braces + 1;
			else if ($token == '}') $this->braces = $this->braces - 1;
			if ($this->braces < 0) { // Closing brace before one is open
				$this->errors[0]['name'] = 'Syntax error.';
				break;
			}
		}

		if (empty($this->errors)) {
			if ($this->braces) $this->errors[0]['name'] = 'Unbalanced braces.';
		}

		// STEP 2: SYNTAX - Check if syntax is valid
		else if (!$this->evalSyntax($this->code)) {
			$this->errors[0]['name'] = 'Syntax error.';
		}

		// STEP 3: EXPRESSIONS - Check against various insecure elements
		if (empty($this->errors)) foreach ($this->disallowedExpressions as $disallowedExpression) {
			unset($matches);
			preg_match($disallowedExpression, $this->code, $matches);
			if($matches) {
				$this->errors[0]['name'] = 'Execution operator / variable function name / variable variable name detected.';
				break;
			}	
		}

		// STEP 4: TOKENS
		if(empty($this->errors)) {
                	unset($this->tokens[0]);
                	unset($this->tokens[0]);
			array_pop($this->tokens);
			array_pop($this->tokens);

			$i = 0;
			foreach ($this->tokens as $key => $token) {
				$i++;
                        	if (is_array($token)) {
					$id = token_name($token[0]);
					switch ($id) {
						case('T_VARIABLE'): 
							if (in_array($token[1], $this->allowedVariables) === false) {
                        					$this->errors[$i]['name'] = 'Illegal variable: ' . $token[1];
                        					$this->errors[$i]['line'] = $token[2];
							}
							break;
						case('T_STRING'):
							if (in_array($token[1], $this->allowedCalls) === false) {
                        					$this->errors[$i]['name'] = 'Illegal function: ' . $token[1];
                        					$this->errors[$i]['line'] = $token[2];
							}
							break;
						default:
							if (in_array($id, $this->allowedTokens) === false) {
                        					$this->errors[$i]['name'] = 'Illegal token: ' . $token[1];
                        					$this->errors[$i]['line'] = $token[2];
							}
							break;
					}
        			}
			}
		}

		if(!empty($this->errors)) {
			return $this->errors;
		} else if ($this->execute) {
			foreach ($this->globalVariables as $globalVariable) {
				global $$globalVariable;
			}
			eval($this->code);
		}
	}
	
	function htmlErrors ($errors = null) {
		if ($errors) {
			$this->errors = $errors;
			$this->errorsHTML = '<h2>Errors:</h2>';
			$this->errorsHTML .= '<dl>';
			foreach ($this->errors as $error) {
				if ($error['line']) {
					$this->errorsHTML .= '<dt>Line '.$error['line'].'</dt>';
				}
				$this->errorsHTML .= '<dd>'.$error['name'].'</dd>';
			}
			$this->errorsHTML .= '</dl>';
			return($this->errorsHTML);
		}
	}
}

?>