<?php

namespace App\Util;

class StringUtils
{
	/**
	 * Extracts single-digit numbers (including negatives) from a string.
	 *
	 * @param string $input The input string.
	 * @return array An array of single-digit numbers found in the string.
	 */
	public static function extractSingleDigits(string $input): array
	{
		preg_match_all('/-?\d/', $input, $matches);
		return $matches[0];
	}
}
