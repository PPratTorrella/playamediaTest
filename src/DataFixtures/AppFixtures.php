<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
//use Doctrine\DBAL\Connection;

class AppFixtures extends Fixture
{

//	public function __construct(private readonly Connection $connection)
//	{
//	}

	public function load(ObjectManager $manager): void
	{
		/*
		normally:
		$product = new Product();
		$manager->persist($product);
		$manager->flush();
		*/

		// should not be raw SQL

		//$this->connection->executeQuery("");

	}
}
