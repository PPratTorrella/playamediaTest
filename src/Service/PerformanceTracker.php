<?php

namespace App\Service;

class PerformanceTracker
{
	private int $startPeakMemoryUsage;
	private int $startMemory;
	private float $startTime;

	private float $executionTime;
	private int $memoryUsageDiff;
	private int $peakMemoryUsageNewRecordSurplus;

	public function start(): void
	{
		$this->reset();
		$this->startMemory = memory_get_usage();
		$this->startTime = microtime(true);
		$this->startPeakMemoryUsage = memory_get_peak_usage();
	}

	public function stop(): void
	{
		$this->executionTime = microtime(true) - $this->startTime;

		// ⚠️ imperfect information, as it is not guaranteed that the garbage collector won't act in between
		// seems that garbage collector has not yet freed up the memory when tracking recursive permutation quiz but does free space in linear parenthesis quiz
		$this->memoryUsageDiff = memory_get_usage() - $this->startMemory;

		// ⚠️ imperfect information, will only show new records between last start and stop or be 0. Previous usage spills over as record for next start
		$this->peakMemoryUsageNewRecordSurplus = memory_get_peak_usage() - $this->startPeakMemoryUsage;
	}

	public function reset(): void
	{
		$this->executionTime = 0;
		$this->memoryUsageDiff = 0;
		$this->startPeakMemoryUsage = 0;
	}

	public function getInfo(): string
	{
		$executionTimeMs = $this->executionTime * 1000;
		$executionTimeReadable = number_format($executionTimeMs, 5);

		return 'Execution time: ' . $executionTimeReadable . ' ms' . PHP_EOL
			. 'Memory usage: ' . $this->memoryUsageDiff . ' bytes' . PHP_EOL
			. 'Peak memory usage new record difference: ' . $this->peakMemoryUsageNewRecordSurplus . ' bytes' . PHP_EOL;
	}

	public function getExecutionTime(): float
	{
		return $this->executionTime;
	}

	public function getMemoryUsageDiff(): int
	{
		return $this->memoryUsageDiff;
	}

	public function getPeakMemoryUsageNewRecordSurplus(): int
	{
		return $this->peakMemoryUsageNewRecordSurplus;
	}


}
