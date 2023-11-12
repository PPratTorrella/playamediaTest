<?php

namespace App\Repository;

use App\Entity\TestUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class TestUserRepository extends ServiceEntityRepository
{

	const TABLE_ALIAS = 'u';

	private $allowedSelectFields;
	private $allowedApiFilterFields;

	/**
	 * @param ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry, private readonly array $allowedSpecialResultKeys)
	{
		parent::__construct($registry, TestUser::class);
	}


	/**
	 * @param array $criteria
	 * @return mixed
	 */
	public function findByCriteria(array $criteria)
	{
		$qb = $this->createQueryBuilder(self::TABLE_ALIAS);

		$this->handleSelect($qb);

		foreach ($criteria as $key => $value) {

			// special keys handled later
			if (in_array($key, $this->allowedSpecialResultKeys)) {
				continue;
			}

			[$field, $operation] = explode('__', $key . '__'); // defaults to '' which is handled as '=' below

			if (!in_array($field, $this->allowedApiFilterFields)) {
				continue;
			}

			$this->handleOperations($operation, $qb, $field, $value);
		}

		$this->handleSorting($criteria, $qb);

		$this->handlePagination($criteria, $qb);

		return $qb->getQuery()->getResult();
	}

	/**
	 * @param array $criteria
	 * @param QueryBuilder $qb
	 */
	private function handleSorting(array $criteria, QueryBuilder $qb): void
	{
		if (isset($criteria['sort'])) {
			$sortParams = explode(',', $criteria['sort']);
			foreach ($sortParams as $param) {
				$direction = 'ASC';
				if (str_starts_with($param, '-')) {
					$direction = 'DESC';
					$param = substr($param, 1);
				}
				$qb->addOrderBy(self::TABLE_ALIAS . ".$param", $direction);
			}
		}
	}

	/**
	 * @param array $criteria
	 * @param QueryBuilder $qb
	 * @return void
	 */
	private function handlePagination(array $criteria, QueryBuilder $qb): void
	{
		if (isset($criteria['page']) && isset($criteria['size'])) {
			$offset = ($criteria['page'] - 1) * $criteria['size'];
			$qb->setFirstResult($offset)
				->setMaxResults($criteria['size']);
		}
	}


	/**
	 * @param string $operation
	 * @param QueryBuilder $qb
	 * @param string $field
	 * @param mixed $value
	 * @return void
	 */
	private function handleOperations(string $operation, QueryBuilder $qb, string $field, mixed $value): void
	{
		switch ($operation) {
			case 'like':
				$qb->andWhere(self::TABLE_ALIAS . ".$field LIKE :$field")
					->setParameter($field, '%' . $value . '%');
				break;
			case 'lt': // less than
				$qb->andWhere(self::TABLE_ALIAS . ".$field < :$field")
					->setParameter($field, $value);
				break;
			case 'gt': // greater than
				$qb->andWhere(self::TABLE_ALIAS . ".$field > :$field")
					->setParameter($field, $value);
				break;
			case 'range':
				$rangeValues = explode(',', $value);
				$min = $rangeValues[0];
				$max = $rangeValues[1] ?? $min;

				$qb->andWhere(self::TABLE_ALIAS . ".$field BETWEEN :min_$field AND :max_$field")
					->setParameter("min_$field", $min)
					->setParameter("max_$field", $max);
				break;
			case 'in':
				$values = explode(',', $value);

				$qb->andWhere($qb->expr()->in(self::TABLE_ALIAS . ".$field", ':field_values'))
					->setParameter('field_values', $values);
				break;
			default:
				$qb->andWhere(self::TABLE_ALIAS .".$field = :$field")
					->setParameter($field, $value);
		}
	}

	/**
	 * @param QueryBuilder $qb
	 */
	private function handleSelect(QueryBuilder $qb): void
	{
		$selectFields = array_map(function($field) {
			return self::TABLE_ALIAS . ".$field";
		}, $this->allowedSelectFields);

		$selectString = implode(', ', $selectFields);

		$qb->select($selectString);
	}

	public function setAllowedSelectFields(array $allowedSelectFields): static
	{
		$this->allowedSelectFields = $allowedSelectFields;
		return $this;
	}

	public function setAllowedApiFilterFields(array $allowedApiFilterFields): static
	{
		$this->allowedApiFilterFields = $allowedApiFilterFields;
		return $this;
	}


}
