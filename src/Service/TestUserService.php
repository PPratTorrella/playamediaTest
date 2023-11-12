<?php

namespace App\Service;

use App\Repository\TestUserRepository;

class TestUserService
{
	// there are different ways we can do this, inject it (config/services.yaml), inject it by setter, move to repo, depends on how much this will change per API
	const ALLOWED_FILTER_FIELDS = ['userName', 'emailAddress', 'isActive', 'isMember', 'lastLoginAt', 'userType'];
	const ALLOWED_API_SELECT_FIELDS = ['userName', 'emailAddress', 'isMember', 'isActive', 'userType', 'lastLoginAt', 'createdAt', 'updatedAt'];

	private readonly TestUserRepository $repository;

	public function __construct(TestUserRepository $repository, private readonly array $allowedSpecialResultKeys)
	{
		$this->repository = $repository->setAllowedSelectFields(self::ALLOWED_API_SELECT_FIELDS)
			->setAllowedApiFilterFields(self::ALLOWED_FILTER_FIELDS);
	}

	/**
	 * @param array $rawCriteria
	 * @return array
	 */
	public function findByCriteria(array $rawCriteria): array
	{
		$criteria = $this->validateAndFilterCriteria($rawCriteria);

		return $this->repository->findByCriteria($criteria);
	}

	/**
	 * @param array $rawCriteria
	 * @return array
	 */
	private function validateAndFilterCriteria(array $rawCriteria): array
	{
		// we could add some extra validation here
		$criteria = [];
		foreach ($rawCriteria as $key => $value) {
			[$field, $operation] = explode('__', $key . '__');

			if (in_array($field, self::ALLOWED_FILTER_FIELDS) || in_array($field, $this->allowedSpecialResultKeys)) {
				$criteria[$key] = $value;
			}
		}
		return $criteria;
	}
}
