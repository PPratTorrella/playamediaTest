<?php

namespace App\Controller\Api;

use App\Factory\QuizFactory;
use App\Service\PerformanceTracker;
use App\Util\StringUtils;
use App\Validator\PermutationValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{

	public function __construct(
		// lazy loading is used for when controllers and services grow
		private readonly QuizFactory $quizFactory,
		private readonly PermutationValidator $validator,
		private readonly PerformanceTracker $performanceTracker)
	{
	}

	/**
	 * Gets all unique permutations (combinations) of the input signed digits.
	 *
	 * @Route("/api/unique-permutations", name="api_unique_permutations", methods={"GET"})
	 */
	public function getUniquePermutations(Request $request): JsonResponse
	{
		$input = $request->query->get('input');

		$digits = StringUtils::extractSingleDigits($input);
		
		$validationError = $this->validator->validate($digits);
		if ($validationError !== null) {
			return $this->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
		}

		// could move performance tracking inside the quizes with a Trait or Abstract class if we only want it for Quiz logic
		$this->performanceTracker->start();

		$quiz = $this->quizFactory->createQuiz(QuizFactory::UNIQUE_PERMUTATIONS);
		$permutations = $quiz->answer($digits);

		$this->performanceTracker->stop();

		return $this->json([
			'inputRecognized' => $digits,
			'performanceInfo' => $this->performanceTracker->getInfo(),
			'answer' => $permutations,
			'readableStringAnswer' => StringUtils::toReadableString($permutations)
		]);
	}

	/**
	 * Check if the input string is balanced parenthesis.
	 *
	 * @Route("/api/balanced-parentheses", name="api_balanced_parenthesis", methods={"GET"})
	 */
	public function getIsBalancedParenthesis(Request $request): JsonResponse
	{
		$input = $request->query->get('input');

		// wrong characters are technically not balanced parenthesis, but this is just a test, so I choose ease-of-use for the input
		$parenthesis = StringUtils::extractParentheses($input);

		$this->performanceTracker->start();

		$quiz = $this->quizFactory->createQuiz(QuizFactory::BALANCED_PARENTHESES);
		$isBalanced = $quiz->answer($parenthesis);

		$this->performanceTracker->stop();

		return $this->json([
			'inputRecognized' => $parenthesis,
			'performanceInfo' => $this->performanceTracker->getInfo(), // to see some memory usage have a lot of opening parenthesis only
			'answer' => $isBalanced,
			'readableStringAnswer' => StringUtils::toReadableString($isBalanced),
		]);
	}

}
