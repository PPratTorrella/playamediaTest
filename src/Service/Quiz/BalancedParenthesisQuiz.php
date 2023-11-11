<?php

namespace App\Service\Quiz;

use App\Interface\QuizInterface;

class BalancedParenthesisQuiz implements QuizInterface
{

	private const MAPPING = ['(' => ')', '[' => ']', '{' => '}'];

	/**
	 * @param array $input
	 * @return bool
	 */
	public function answer(array $input): bool
	{
		/** We use stack to keep track of open parenthesis, popping when we find the closing one.
		 * Time complexity is linear O(n) as we make one loop on input
		 * Space complexity is small too as we have just one stack max half of lenth of input
		 * */
		$stack = [];

		foreach ($input as $char) {
			if (array_key_exists($char, self::MAPPING)) { // we check the key because we only keep track of opened parenthesis in stack
				$stack[] = $char;
			} else {
				if (empty($stack)) { // still has closing parenthesis but no opened ones to pop for balance
					return false;
				}
				$last = array_pop($stack);
				if ( self::MAPPING[$last] !== $char) { // closed parenthesis does not match last opened one
					return false;
				}
			}
		}
		return empty($stack); // if no opened parenthesis left means is balanced
	}

}
