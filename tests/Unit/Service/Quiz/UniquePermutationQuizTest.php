<?php

namespace App\Tests\Unit\Service\Quiz;

use App\Service\Quiz\UniquePermutationQuiz;
use PHPUnit\Framework\TestCase;

class UniquePermutationQuizTest extends TestCase
{
	private UniquePermutationQuiz $quiz;

	protected function setUp(): void
	{
		$this->quiz = new UniquePermutationQuiz();
	}

	public function testAnswerWithNoDigits()
	{
		$this->assertEquals([], $this->quiz->answer([]), 'should return empty array for empty input');
	}

	public function testAnswerWithSingleDigit()
	{
		$expected = [[1]];
		$this->assertEquals($expected, $this->quiz->answer([1]), 'should return expected permutation');
	}

	public function testAnswerWithMultipleDigits()
	{
		$expected = [
			[1, 2],
			[2, 1]
		];
		$result = $this->quiz->answer([1, 2]);

		$this->assertCount(2, $result, 'should return 2 permutations');
		foreach ($expected as $expectedPermutation) {
			$this->assertContains($expectedPermutation, $result, 'should contain expected permutation');
		}
	}

	public function testAnswerWithDuplicateDigits()
	{
		$expected = [[1, 1]];
		$result = $this->quiz->answer([1, 1]);

		$this->assertCount(1, $result, 'should return 1 permutation');
		$this->assertEquals($expected, $result, 'should contain expected permutation');
	}

	public function testAnswerWithNegativeDigits()
	{
		$expected = [
			[-1, 2],
			[2, -1]
		];
		$result = $this->quiz->answer([-1, 2]);

		$this->assertCount(2, $result, 'should return 2 permutations');
		foreach ($expected as $expectedPermutation) {
			$this->assertContains($expectedPermutation, $result, 'should contain expected permutation');
		}
	}
}
