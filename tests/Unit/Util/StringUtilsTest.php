<?php

namespace App\Tests\Unit\Util;

use App\Util\StringUtils;
use PHPUnit\Framework\TestCase;

class StringUtilsTest extends TestCase
{
	public function testExtractSingleDigits()
	{
		$this->assertEquals(['1', '2', '3'], StringUtils::extractSingleDigits("ab c12 3"), "Should extract single digits from a string");
		$this->assertEquals(['-1', '2'], StringUtils::extractSingleDigits("a-1b2 "), "Should extract negative digits from a string");
		$this->assertEquals([], StringUtils::extractSingleDigits("no digits"), "Should return an empty array if no digits are found");
	}

	public function testExtractParentheses()
	{
		$this->assertEquals(['(', '[', ']', '{', '}', ')'], StringUtils::extractParentheses("+|Example( With[Various]{Types}-)"), "Should extract all parentheses from a string");
		$this->assertEquals([], StringUtils::extractParentheses("No parenthesis"), "Should return an empty array if no parentheses are found");
	}

	public function testToReadableString()
	{
		$this->assertEquals('true', StringUtils::toReadableString(true), "Should convert boolean values to strings");
		$this->assertEquals('false', StringUtils::toReadableString(false), "Should convert boolean values to strings");
		$this->assertEquals('Hello World', StringUtils::toReadableString('Hello World'), "Should convert strings to strings");
		$this->assertEquals('123', StringUtils::toReadableString(123), "Should convert integers to strings");
		$this->assertEquals('12.34', StringUtils::toReadableString(12.34), "Should convert floats to strings");
		$this->assertEquals(json_encode(['key' => 'value'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), StringUtils::toReadableString(['key' => 'value']), "Should convert arrays to pritty json strings");
	}
}
