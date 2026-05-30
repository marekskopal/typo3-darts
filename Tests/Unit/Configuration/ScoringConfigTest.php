<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Configuration;

use MarekSkopal\MsDarts\Configuration\ScoringConfig;
use PHPUnit\Framework\TestCase;

final class ScoringConfigTest extends TestCase
{
    public function testDefaultsMatchHistoricalHardcodedValues(): void
    {
        $config = new ScoringConfig();

        self::assertSame(9, $config->overtimeLegThreshold);
        self::assertSame(3, $config->pointsWin);
        self::assertSame(2, $config->pointsOvertimeWin);
        self::assertSame(1, $config->pointsOvertimeLose);
    }

    public function testValuesAreOverridable(): void
    {
        $config = new ScoringConfig(
            overtimeLegThreshold: 7,
            pointsWin: 5,
            pointsOvertimeWin: 4,
            pointsOvertimeLose: 2,
        );

        self::assertSame(7, $config->overtimeLegThreshold);
        self::assertSame(5, $config->pointsWin);
        self::assertSame(4, $config->pointsOvertimeWin);
        self::assertSame(2, $config->pointsOvertimeLose);
    }
}
