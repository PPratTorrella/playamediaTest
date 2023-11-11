<?php

namespace App\Controller;

use App\Service\PerformanceTracker;
use App\Service\PermutationService;
use App\Util\StringUtils;
use App\Validator\PermutationValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

	public function __construct(
		// lazy loading is used for when controllers and services grow
		private readonly PermutationService $permutationService,
		private readonly PermutationValidator $validator,
		private readonly PerformanceTracker $performanceTracker)
	{
	}

	/**
	 * @Route("/", name="app_home")
	 */
    public function index(): Response
	{
		return $this->render('home/index.html.twig');
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

		$this->performanceTracker->start();

		$permutations = $this->permutationService->getUniquePermutations($digits);

		$this->performanceTracker->stop();
		$performanceInfo = $this->performanceTracker->getInfo();

		return $this->json([
			'digitsRecognized' => $digits,
			'permutations' => $permutations,
			'performanceInfo' => $performanceInfo,
		]);
	}

}
