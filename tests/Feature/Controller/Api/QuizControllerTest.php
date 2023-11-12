<?php

namespace App\Tests\Feature\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class QuizControllerTest extends WebTestCase
{
	public function testGetUniquePermutations()
	{
		$client = static::createClient();
		$client->request('GET', '/api/unique-permutations?input=123');

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode(), 'should return 200 OK');
		$this->assertJson($client->getResponse()->getContent(), 'should return JSON');

		$arrayResponse = json_decode($client->getResponse()->getContent(), true);

		$this->assertArrayHasKey('inputRecognized', $arrayResponse, 'Response should have key inputRecognized');
		$this->assertArrayHasKey('performanceInfo', $arrayResponse, 'Response should have key performanceInfo');
		$this->assertArrayHasKey('answer', $arrayResponse, 'Response should have key answer');
		$this->assertArrayHasKey('readableStringAnswer', $arrayResponse, 'Response should have key readableStringAnswer');

		$this->assertEquals(['1', '2', '3'], $arrayResponse['inputRecognized'], 'inputRecognized should be an array with "1", "2", "3"');

		$this->assertCount(6, $arrayResponse['answer'], 'There should be 6 permutations');
		foreach ($arrayResponse['answer'] as $permutation) {
			$this->assertCount(3, $permutation, 'Each permutation should have 3 elements');
			foreach ($permutation as $element) {
				$this->assertContains($element, ['1', '2', '3'], 'Each element of permutation should be "1", "2", or "3"');
			}
		}

		$this->assertIsString($arrayResponse['readableStringAnswer'], 'readableStringAnswer should be a string');
		$this->assertStringContainsString('"1"', $arrayResponse['readableStringAnswer'], 'readableStringAnswer should contain the string "1"'); // can do more
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
