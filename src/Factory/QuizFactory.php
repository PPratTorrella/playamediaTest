<?php

namespace App\Factory;

use App\Interface\QuizInterface;
use App\Service\Quiz\BalancedParenthesisQuiz;
use App\Service\Quiz\UniquePermutationQuiz;

class QuizFactory
{

	public const UNIQUE_PERMUTATIONS = 'unique_permutations';
	public const BALANCED_PARENTHESES = 'balanced_parentheses';

	private array $lazyLoadedQuizes; // services.yaml used for lazy loading configuration

	public function __construct(BalancedParenthesisQuiz $balancedParenthesisQuiz, UniquePermutationQuiz $uniquePermutationsQuiz)
	{
		$this->lazyLoadedQuizes = [
			self::BALANCED_PARENTHESES => $balancedParenthesisQuiz,
			self::UNIQUE_PERMUTATIONS => $uniquePermutationsQuiz,
		];
	}

	public function createQuiz(string $type): QuizInterface
	{
		if (!array_key_exists($type, $this->lazyLoadedQuizes)) {
			throw new \InvalidArgumentException("Unknown quiz type: $type");
		}

		return $this->lazyLoadedQuizes[$type];
	}
}
