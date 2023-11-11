<?php

namespace App\Tests\Feature\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
	public function testIndex()
	{
		$client = static::createClient();
		$client->request('GET', '/');

		$this->assertResponseIsSuccessful();
		$this->assertSelectorTextContains('h2:first-of-type', 'Unique Permutations', 'should contain Unique Permutations title');
	}

	public function testGetUniquePermutations()
	{
		$client = static::createClient();
		$client->request('GET', '/api/unique-permutations?input=123');

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode(), 'should return 200 OK');
		$this->assertJson($client->getResponse()->getContent(), 'should return JSON');

		$arrayResponse = json_decode($client->getResponse()->getContent(), true);

		// asserting that the response has the necessary keys
		$this->assertArrayHasKey('inputRecognized', $arrayResponse, 'Response should have key inputRecognized');
		$this->assertArrayHasKey('performanceInfo', $arrayResponse, 'Response should have key performanceInfo');
		$this->assertArrayHasKey('answer', $arrayResponse, 'Response should have key answer');
		$this->assertArrayHasKey('readableStringAnswer', $arrayResponse, 'Response should have key readableStringAnswer');

		// asserting the content of 'inputRecognized'
		$this->assertEquals(['1', '2', '3'], $arrayResponse['inputRecognized'], 'inputRecognized should be an array with "1", "2", "3"');

		// asserting the structure of 'answer'
		$this->assertCount(6, $arrayResponse['answer'], 'There should be 6 permutations');
		foreach ($arrayResponse['answer'] as $permutation) {
			$this->assertCount(3, $permutation, 'Each permutation should have 3 elements');
			foreach ($permutation as $element) {
				$this->assertContains($element, ['1', '2', '3'], 'Each element of permutation should be "1", "2", or "3"');
			}
		}

		// asserting the format of 'readableStringAnswer'
		$this->assertIsString($arrayResponse['readableStringAnswer'], 'readableStringAnswer should be a string');
		$this->assertStringContainsString('"1"', $arrayResponse['readableStringAnswer'], 'readableStringAnswer should contain the string "1"'); // can do more presice
	}

	public function testGetIsBalancedParenthesis()
	{
		$client = static::createClient();
		$client->request('GET', '/api/balanced-parentheses?input=()');

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode(), 'should return 200 OK');
		$this->assertJson($client->getResponse()->getContent(), 'should return JSON');
	}

	// @todo add more tests for different scenarios and KO tests too for invalid input, empty input, etc.
}
