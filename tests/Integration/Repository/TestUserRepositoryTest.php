<?php

namespace App\Tests\Integration\Repository;

use App\Entity\TestUser;
use App\Repository\TestUserRepository;
use App\Service\TestUserService;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestUserRepositoryTest extends KernelTestCase
{
	private ObjectManager|null $entityManager;
	private TestUserRepository $repository;

	protected function setUp(): void
	{
		$this->entityManager = static::getContainer()->get('doctrine')->getManager();
		$this->repository = $this->entityManager->getRepository(TestUser::class);
		$this->repository->setAllowedSelectFields(TestUserService::ALLOWED_API_SELECT_FIELDS);
		$this->repository->setAllowedApiFilterFields(TestUserService::ALLOWED_FILTER_FIELDS);
	}

	public function testFindByCriteriaWithEmailLike()
	{
		$likePattern = 'test_2%';
		$criteria = ['emailAddress__like' => $likePattern];

		$results = $this->repository->findByCriteria($criteria);

		foreach ($results as $user) {
			$this->assertStringContainsString('test_2', $user['emailAddress']);
		}
	}

	public function testFindByCriteriaWithExactUserName()
	{
		$expectedUserName = 'test_262_user';
		$criteria = ['userName' => $expectedUserName];

		$results = $this->repository->findByCriteria($criteria);

		foreach ($results as $user) {
			$this->assertEquals($expectedUserName, $user['userName']);
		}
	}

	public function testFindByCriteriaWithUserTypeInRange()
	{
		$min = 2;
		$max = 3;
		$criteria = ['userType__range' => $min . ',' . $max];

		$results = $this->repository->findByCriteria($criteria);

		foreach ($results as $user) {
			$this->assertGreaterThanOrEqual($min, $user['userType']);
			$this->assertLessThanOrEqual($max, $user['userType']);
		}
	}

	public function testFindByCriteriaWithUserTypeIn()
	{
		$userTypes = [2, 3];
		$criteria = ['userType__in' => implode(',', $userTypes)];

		$results = $this->repository->findByCriteria($criteria);

		foreach ($results as $user) {
			$this->assertContains($user['userType'], $userTypes);
		}
	}

	public function testFindByCriteriaWithMultipleParameters()
	{
		$likePattern = 'test_2%';
		$userTypeRange = '2,3';
		$sortParameter = '-userName';

		$criteria = [
			'emailAddress__like' => $likePattern,
			'userType__range' => $userTypeRange,
			'sort' => $sortParameter
		];

		$results = $this->repository->findByCriteria($criteria);

		$previousUserName = null; // for testing sorting

		foreach ($results as $user) {
			$this->assertStringContainsString('test_2', $user['emailAddress']);
			$this->assertContains($user['userType'], [2, 3]);

			if ($previousUserName !== null) {
				$this->assertGreaterThanOrEqual($user['userName'], $previousUserName);
			}
			$previousUserName = $user['userName'];
		}
	}


	public function testFindByCriteriaWithPaginationAndSort()
	{
		$criteria = [
			'page' => 1,
			'size' => 5,
			'sort' => 'createdAt'
		];

		$results = $this->repository->findByCriteria($criteria);

		$this->assertCount(5, $results); // Assuming 5 results per page
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		$this->entityManager->close();
		$this->entityManager = null;
	}
}
