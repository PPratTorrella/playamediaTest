<?php

namespace App\Service;

class PerformanceTracker
{
	private float $executionTime = 0;
	private int $memoryUsageDiff = 0;
	private int $peakMemoryUsage = 0;
	private int $startMemory;
	private float $startTime;

	public function start(): void
	{
		$this->startMemory = memory_get_usage();
		$this->startTime = microtime(true);
	}

	public function stop(): void
	{
		$this->executionTime = microtime(true) - $this->startTime;
		$this->memoryUsageDiff = memory_get_usage() - $this->startMemory;
		$this->peakMemoryUsage = memory_get_peak_usage();
	}

	public function reset(): void
	{
		$this->executionTime = 0;
		$this->memoryUsageDiff = 0;
		$this->peakMemoryUsage = 0;
	}

	public function getExecutionTime(): float
	{
		return $this->executionTime;
	}

	public function getMemoryUsageDiff(): int
	{
		return $this->memoryUsageDiff;
	}

	public function getPeakMemoryUsage(): int
	{
		return $this->peakMemoryUsage;
	}

	public function getInfo(): string
	{
		$executionTimeMs = $this->executionTime * 1000;
		$executionTimeReadable = number_format($executionTimeMs, 5);

		$info = '';
		$info .= 'Execution time: ' . $executionTimeReadable . ' ms' . PHP_EOL;
		$info .= 'Memory usage: ' . $this->memoryUsageDiff . ' bytes' . PHP_EOL;
		$info .= 'Peak memory usage: ' . $this->peakMemoryUsage . ' bytes' . PHP_EOL;

		return $info;
	}


}
