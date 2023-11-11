<?php

namespace App\Interface;

interface QuizInterface {

	/**
	 * @param array $input
	 * @return mixed this could be standarized
	 */
	public function answer(array $input): mixed;

}
