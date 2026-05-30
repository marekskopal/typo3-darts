<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Configuration;

final readonly class ScoringConfig
{
    public function __construct(
        public int $overtimeLegThreshold = 9,
        public int $pointsWin = 3,
        public int $pointsOvertimeWin = 2,
        public int $pointsOvertimeLose = 1,
    ) {
    }
}
