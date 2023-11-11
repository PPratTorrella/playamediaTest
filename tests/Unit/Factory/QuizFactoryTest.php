<?php

namespace App\Tests\Unit\Factory;

use App\Factory\QuizFactory;
use App\Interface\QuizInterface;
use App\Service\Quiz\BalancedParenthesesQuiz;
use App\Service\Quiz\UniquePermutationQuiz;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class QuizFactoryTest extends TestCase
{
	private $balancedParenthesisQuizMock;
	private $uniquePermutationQuizMock;
	private $quizFactory;

	protected function setUp(): void
	{
		$this->balancedParenthesisQuizMock = $this->createMock(BalancedParenthesesQuiz::class);
		$this->uniquePermutationQuizMock = $this->createMock(UniquePermutationQuiz::class);
		$this->quizFactory = new QuizFactory($this->balancedParenthesisQuizMock, $this->uniquePermutationQuizMock);
	}

	public function testCreateQuizWithBalancedParentheses()
	{
		$quiz = $this->quizFactory->createQuiz(QuizFactory::BALANCED_PARENTHESES);
		$this->assertInstanceOf(BalancedParenthesesQuiz::class, $quiz, 'QuizFactory::createQuiz() should return a BalancedParenthesisQuiz instance');
		$this->assertInstanceOf(QuizInterface::class, $quiz, 'QuizFactory::createQuiz() should return a QuizInterface instance');
	}

	public function testCreateQuizWithUniquePermutations()
	{
		$quiz = $this->quizFactory->createQuiz(QuizFactory::UNIQUE_PERMUTATIONS);
		$this->assertInstanceOf(UniquePermutationQuiz::class, $quiz, 'QuizFactory::createQuiz() should return a UniquePermutationQuiz instance');
		$this->assertInstanceOf(QuizInterface::class, $quiz, 'QuizFactory::createQuiz() should return a QuizInterface instance');
	}

	public function testCreateQuizWithUnknownType()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->quizFactory->createQuiz('unknown_type');
	}
}
