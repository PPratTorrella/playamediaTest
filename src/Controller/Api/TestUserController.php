<?php

namespace App\Controller\Api;

use App\Service\TestUserService;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestUserController extends AbstractController
{

	/**
	 * @Route("/api/test_users", name="api_test_users", methods="GET")
	 *
	 * @OA\Get(
	 *     path="/api/test_users",
	 *     summary="Retrieve a list of test users based on various criteria",
	 *     @OA\Response(
	 *         response=200,
	 *         description="Returns a list of test users matching the specified criteria",
	 *         @OA\JsonContent(
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid query parameters"
	 *     ),
	 *     @OA\Parameter(
	 *         name="userName",
	 *         in="query",
	 *         description="Username to filter by. Can include an operation like 'username__like'.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Parameter(
	 *         name="emailAddress",
	 *         in="query",
	 *         description="Email to filter by. Can include an operation like 'email__like'.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Parameter(
	 *         name="isMember",
	 *         in="query",
	 *         description="Filter by membership status. Can include operations like 'isMember__lt' or 'isMember__gt'.",
	 *         @OA\Schema(type="boolean")
	 *     ),
	 *     @OA\Parameter(
	 *         name="isActive",
	 *         in="query",
	 *         description="Filter byis activbe. Can include operations too.",
	 *         @OA\Schema(type="boolean")
	 *     ),
	 *     @OA\Parameter(
	 *         name="lastLoginAt",
	 *         in="query",
	 *         description="Filter byis lastLoginAt. Can include operations too.",
	 *         @OA\Schema(type="string", format="datetime")
	 *     ),
	 *     @OA\Parameter(
	 *         name="userType",
	 *         in="query",
	 *         description="Filter byis userType. Can include operations too.",
	 *         @OA\Schema(type="integer")
	 *     ),
	 *     @OA\Parameter(
	 * 			name="operations",
	 * 			in="query",
	 * 			description="Apply operations like '__like', '__lt', '__gt', '__range' to query parameters for advanced filtering.",
	 * 			example="userName__like=JohnD%",
	 * 			@OA\Schema(type="string")
	 * 		)
	 * )
	 *
	 * // can improve api doc, explain operations better, maybe link to entity schema, explain response better
	 *
	 * //@todo implememt error handling and test KO cases in Unit
	 */
	public function findUsers(Request $request, TestUserService $service): JsonResponse
	{
		$criteria = $request->query->all();

		$users = $service->findByCriteria($criteria);

		return $this->json($users);
	}

}