<?php

namespace App\Validator\Interface;

interface ValidatorInterface
{
	/**
	 * Validate the provided data.
	 *
	 * @param mixed $data Data to be validated.
	 * @return string|null Error message if validation fails, null otherwise.
	 */
	public function validate($data);
}
