<?php

namespace App\Tests\Feature\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
	public function testIndex()
	{
		$client = static::createClient();
		$client->request('GET', '/');

		$this->assertResponseIsSuccessful();
		$this->assertSelectorTextContains('h2:first-of-type', 'Unique Permutations', 'should contain Unique Permutations title');
	}
}
