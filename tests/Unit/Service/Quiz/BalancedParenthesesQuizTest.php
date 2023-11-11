<?php

namespace App\Tests\Unit\Service\Quiz;

use App\Service\Quiz\BalancedParenthesesQuiz;
use PHPUnit\Framework\TestCase;

class BalancedParenthesesQuizTest extends TestCase
{
	private BalancedParenthesesQuiz $quiz;

	protected function setUp(): void
	{
		$this->quiz = new BalancedParenthesesQuiz();
	}

	public function testAnswerWithBalancedParentheses()
	{
		$this->assertTrue($this->quiz->answer(['(', ')']), 'should return true for balanced parentheses');
		$this->assertTrue($this->quiz->answer(['[', '{', '}', ']']), 'should return true for balanced parentheses');
		$this->assertTrue($this->quiz->answer(['{', '(', '[', ']', ')', '}']), 'should return true for balanced parentheses');
	}

	public function testAnswerWithUnbalancedParentheses()
	{
		$this->assertFalse($this->quiz->answer(['(', '[', ']', ')', '{']), 'should return false for unbalanced parentheses');
		$this->assertFalse($this->quiz->answer(['(', '{', '}', '[']), 'should return false for unbalanced parentheses');
		$this->assertFalse($this->quiz->answer(['}', '{']), 'should return false for unbalanced parentheses');
	}

	public function testAnswerWithEmptyInput()
	{
		$this->assertTrue($this->quiz->answer([]), 'should return true for empty input');
	}

	public function testAnswerWithNoParentheses()
	{
		$this->assertFalse($this->quiz->answer(['a', 'b', 'c']), 'should return false for input with no parentheses only other characters');
	}
}
