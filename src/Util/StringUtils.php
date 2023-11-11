<?php

namespace App\Util;

class StringUtils
{
	/**
	 * Extracts single-digit numbers (including negatives) from a string.
	 *
	 * @param string $input
	 * @return array single-digit numbers found in the string.
	 */
	public static function extractSingleDigits(string $input): array
	{
		preg_match_all('/-?\d/', $input, $matches);
		return $matches[0];
	}

	/**
	 * Extracts only the parentheses from a string.
	 *
	 * @param string $input
	 * @return array only the parentheses in the original order
	 */
	public static function extractParentheses(string $input): array
	{
		preg_match_all('/[()\[\]{}]/', $input, $matches);
		return $matches[0];
	}


	/**
	 * Converts mixed input to a human-readable string representation.
	 *
	 * @param mixed $input The input to be converted.
	 * @return string The readable JSON string representation of the input.
	 */
	public static function toReadableString(mixed $input): string
	{
		if (is_bool($input)) {
			return $input ? 'true' : 'false';
		}
		if (is_string($input)) {
			return $input;
		}
		if (is_int($input) || is_float($input)) {
			return (string)$input;
		}
		return json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}
}
