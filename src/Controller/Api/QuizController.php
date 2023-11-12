<?php

namespace App\Controller\Api;

use App\Factory\QuizFactory;
use App\Service\PerformanceTracker;
use App\Util\StringUtils;
use App\Validator\PermutationValidator;
use OpenApi\Annotations as OA;
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
	 * @Route("/api/unique-permutations", name="api_unique_permutations", methods={"GET"})
	 *
	 * @OA\Get(
	 *     path="/api/unique-permutations",
	 *     summary="Get all unique permutations of the input digits",
	 *     @OA\Response(
	 *         response=200,
	 *         description="Returns all unique permutations of the input digits",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(
	 *                 property="inputRecognized",
	 *                 type="array",
	 *                 @OA\Items(type="string"),
	 *                 example={"1", "1", "2"}
	 *             ),
	 *             @OA\Property(
	 *                 property="performanceInfo",
	 *                 type="string",
	 *                 example="Execution time: 0.01192 ms\nMemory usage: 1504 bytes\nPeak memory usage new record difference: 360 bytes\n"
	 *             ),
	 *             @OA\Property(
	 *                 property="answer",
	 *                 type="array",
	 *                 @OA\Items(
	 *                     type="array",
	 *                     @OA\Items(type="string")
	 *                 ),
	 *                 example={{"1", "1", "2"}, {"1", "2", "1"}, {"2", "1", "1"}}
	 *             ),
	 *             @OA\Property(
	 *                 property="readableStringAnswer",
	 *                 type="string",
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid input"
	 *     ),
	 *     @OA\Parameter(
	 *         name="input",
	 *         in="query",
	 *         required=true,
	 *         description="The string of digits for which permutations are needed",
	 *         @OA\Schema(type="string")
	 *     )
	 * )
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
	 * @Route("/api/balanced-parentheses", name="api_balanced_parenthesis", methods={"GET"})
	 *
	 * @OA\Get(
	 *     path="/api/balanced-parentheses",
	 *     summary="Check if the input string of parentheses is balanced",
	 *     @OA\Response(
	 *         response=200,
	 *         description="Returns whether the input parentheses are balanced",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(
	 *                 property="inputRecognized",
	 *                 type="array",
	 *                 @OA\Items(type="string"),
	 *                 example={"{", "{", "}", "}"}
	 *             ),
	 *             @OA\Property(
	 *                 property="performanceInfo",
	 *                 type="string",
	 *                 example="Execution time: 0.00691 ms\nMemory usage: 0 bytes\nPeak memory usage new record difference: 0 bytes\n"
	 *             ),
	 *             @OA\Property(
	 *                 property="answer",
	 *                 type="boolean",
	 *                 example=true
	 *             ),
	 *             @OA\Property(
	 *                 property="readableStringAnswer",
	 *                 type="string",
	 *                 example="true"
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid input"
	 *     ),
	 *     @OA\Parameter(
	 *         name="input",
	 *         in="query",
	 *         required=true,
	 *         description="The string of parentheses to be checked for balance",
	 *         @OA\Schema(type="string")
	 *     )
	 * )
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
