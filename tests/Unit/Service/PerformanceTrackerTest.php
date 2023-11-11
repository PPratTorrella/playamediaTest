<?php

namespace App\Tests\Unit\Service;

use App\Service\PerformanceTracker;
use PHPUnit\Framework\TestCase;

class PerformanceTrackerTest extends TestCase
{
	private PerformanceTracker $performanceTracker;

	/**
	 * @return float
	 */
	public function simulateWork(): float
	{
		$result = 0;
		for ($i = 0; $i < 1000; $i++) {
			$result += sqrt($i);
		}
		return $result;
	}

	protected function setUp(): void
	{
		$this->performanceTracker = new PerformanceTracker();
	}

	public function testStartAndStop()
	{
		$this->performanceTracker->start();

		$result = $this->simulateWork();
		$this->assertSame(21065.833110879048, $result);

		$this->performanceTracker->stop();

		$this->assertGreaterThan(0, $this->performanceTracker->getExecutionTime(), 'Execution time should be greater than 0');

		// memory usage assertions are less predictable and may vary based on environment and PHPs internal handling
		$this->assertGreaterThanOrEqual(0, $this->performanceTracker->getMemoryUsageDiff(), 'Memory usage difference should be greater than or equal to 0');
		$this->assertGreaterThanOrEqual(0, $this->performanceTracker->getPeakMemoryUsageNewRecordSurplus(),
			'Peak memory usage new record surplus should be greater than or equal to 0');
	}

	public function testReset()
	{
		$this->performanceTracker->start();
		$this->performanceTracker->stop();

		$this->performanceTracker->reset();

		$this->assertEquals(0, $this->performanceTracker->getExecutionTime(), 'Execution time should be 0 after reset');
		$this->assertEquals(0, $this->performanceTracker->getMemoryUsageDiff(), 'Memory usage difference should be 0 after reset');
		$this->assertEquals(0, $this->performanceTracker->getPeakMemoryUsageNewRecordSurplus(), 'Peak memory usage new record surplus should be 0 after reset');
	}

	public function testGetInfo()
	{
		$this->performanceTracker->start();

		$result = $this->simulateWork();
		$this->assertSame(21065.833110879048, $result);

		$this->performanceTracker->stop();

		$info = $this->performanceTracker->getInfo();

		$this->assertStringContainsString('Execution time: ', $info, 'Info should contain execution time');
		$this->assertStringContainsString(' ms', $info, 'Info should contain milliseconds');
		$this->assertStringContainsString('Memory usage: ', $info, 'Info should contain memory usage');
		$this->assertStringContainsString('Peak memory usage new record difference: ', $info, 'Info should contain peak memory usage new record difference');
	}
}
