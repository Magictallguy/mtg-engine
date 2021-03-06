<?php
namespace JBBCode;
/**
 * This Tokenizer is used while constructing the parse tree. The tokenizer
 * handles splitting the input into brackets and miscellaneous text. The
 * parser is then built as a FSM ontop of these possible inputs.
 *
 * @author jbowens
 */
class Tokenizer {
	protected $tokens = array();
	protected $i = -1;
	/**
	 * Constructs a tokenizer from the given string. The string will be tokenized
	 * upon construction.
	 *
	 * @param $str  the string to tokenize
	 */
	public function __construct($str) {
		$strStart = 0;
		for($index = 0; $index < strlen($str); ++$index) {
			if(']' == $str[$index] || '[' == $str[$index]) {
				if($strStart < $index) { /* Are there characters in the buffer from a previous string? */
					array_push($this->tokens, substr($str, $strStart, $index - $strStart));
					$strStart = $index;
				}
				array_push($this->tokens, $str[$index]); /* Add the [ or ] to the tokens array. */
				$strStart = $index + 1;
			}
		}
		if($strStart < strlen($str))
			array_push($this->tokens, substr($str, $strStart, strlen($str) - $strStart)); /* There are still characters in the buffer. Add them to the tokens. */
	}
	/**
	 * Returns true if there is another token in the token stream.
	 */
	public function hasNext() {
		return count($this->tokens) > 1 + $this->i;
	}
	/**
	 * Advances the token stream to the next token and returns the new token.
	 */
	public function next() {
		return !$this->hasNext() ? null : $this->tokens[++$this->i];
	}
	/**
	 * Retrieves the current token.
	 */
	public function current() {
		return $this->i < 0 ? null : $this->tokens[$this->i];
	}
	/**
	 * Moves the token stream back a token.
	 */
	public function stepBack() {
		if($this->i > -1)
			--$this->i;
	}
	/**
	 * Restarts the tokenizer, returning to the beginning of the token stream.
	 */
	public function restart() {
		$this->i = -1;
	}
	/**
	 * toString method that returns the entire string from the current index on.
	 */
	public function toString() {
		return implode('', array_slice($this->tokens, $this->i + 1));
	}
}