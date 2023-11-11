<?php

namespace App\Validator;

use App\Validator\Interface\ValidatorInterface;

class PermutationValidator implements ValidatorInterface
{
	public function validate($data)
	{
		if (empty($data)) {
			return 'Input should not be empty'; // or we could return empty results
		}
		if (count($data) > 8) {
			return 'Input cannot have more than 8 digits: ' . implode(', ', $data);
		}

		return null;
	}
}
