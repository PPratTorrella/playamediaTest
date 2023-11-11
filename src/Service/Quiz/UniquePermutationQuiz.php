<?php

namespace App\Service\Quiz;

use App\Interface\QuizInterface;

class UniquePermutationQuiz implements QuizInterface
{
	/**
	 * Generate all unique permutations of an array of digits in any order.
	 *
	 * @param array $input Array of digits (can include negative digits).
	 * @return array An array of unique permutations.
	 */
	public function answer(array $input): array
	{
		$uniquePermutations = [];

//		$uniquePermutations = $this->permuteLessSpaceEfficient($digits);
		$this->permuteBetter($input, 0, count($input) - 1, $uniquePermutations);

		return $uniquePermutations;
	}


	/**
	 * @param array $array
	 * @param int $left
	 * @param int $endIndex
	 * @param array $permutations ⚠️ by reference
	 * @return void
	 */
	private function permuteBetter(array &$array, int $left, int $endIndex, array &$permutations): void
	{
		if ($left === $endIndex) {
			// base case: if only one element is left, add the permutation to the list
			$permutations[] = $array;
			return;
		}

		for ($i = $left; $i <= $endIndex; $i++) {
			if ($this->shouldSkip($array, $left, $i)) {
				continue; // skip duplicate elements to avoid repeating permutations
			}

			$this->swap($array, $left, $i);
			$this->permuteBetter($array, $left + 1, $endIndex, $permutations);
			$this->swap($array, $left, $i); // backtrack to explore other permutations
		}
	}

	/**
	 * @param array $array
	 * @param int $currentPosition
	 * @param int $swapPosition
	 * @return bool
	 */
	private function shouldSkip(array $array, int $currentPosition, int $swapPosition): bool
	{
		// skip if the element at the swap position is a duplicate of the current element
		return $swapPosition !== $currentPosition && $array[$swapPosition] === $array[$currentPosition];
	}

	/**
	 * @param array $array
	 * @param int $i
	 * @param int $j
	 * @return void
	 */
	private function swap(array &$array, int $i, int $j)
	{
		$temp = $array[$i];
		$array[$i] = $array[$j];
		$array[$j] = $temp;
	}


	/**
	 * My initial attempt. Less space efficient than optimal solution, but easier to follow
	 *
	 * @param array $array
	 * @param array $pickedCombination
	 * @return array|array[]
	 */
	private function permuteLessSpaceEfficient(array $array, array $pickedCombination = []): array
	{
		$permutations = [];

		if (empty($array)) {
			// if array is empty, no more remaining, add the permutation to the list
			return [$pickedCombination];
		}

		// sort the array to bring duplicates together, and we can comapre with previous digit (trick for eficient skip)
		sort($array);

		$previousDigit = null;

		/*
		 * From left to right, remove each digit from the array and generate permutations (keep doing this) for the rest
		 * so permutations beginning with earlier elements are generated first (left sided children of the root note of the recursive tree)
		 * each subtree of a node is the combiantions of the remaining digits after removing the digits from the parent nodes
		 * each time it 'removes' a digit it is considereing all options 'starting' with this digit for the subtree, with any comnbination of the remaining digits
		 * each recurion the array is n-1 so the time complexity is n! (n factorial) minus some duplicates (n! devided by #dublicates)
		 * Space complexity is a little slower apparantly than optimal with backtracking and inline swapping tricks I just found
		 * */
		foreach ($array as $key => $digit) {

			if ($digit === $previousDigit) {
				// skip picking this digit and generating rest of the tree as it will be the same as the previuous sibling tree, and we won't add it to the answer as we want unqiue combinations
				continue;
			}
			$previousDigit = $digit;

			// pick a digit, new node for sub tree
			$remaining = $array;
			unset($remaining[$key]);

			// generate 'picked combinations' for the remaining digits (this makes space cpomplexity not so good as we keep generating new arrays)
			$newPickedCombination = array_merge($pickedCombination, [$digit]);
			$permuteRemainingRecursively = $this->permuteLessSpaceEfficient($remaining, $newPickedCombination);
			$permutations = array_merge($permutations, $permuteRemainingRecursively);
		}

		return $permutations;
	}
}
