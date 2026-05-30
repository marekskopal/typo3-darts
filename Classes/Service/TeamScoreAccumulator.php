<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Service;

use MarekSkopal\MsDarts\Domain\Model\Team;
use MarekSkopal\MsDarts\Dto\TeamScoreDto;

/**
 * Internal mutable accumulator used while iterating matches. Snapshot to an immutable
 * {@see TeamScoreDto} via {@see TeamScoreAccumulator::toDto()} once aggregation is complete.
 */
final class TeamScoreAccumulator
{
    public int $matches = 0;

    public int $legsOwn = 0;

    public int $legsOponent = 0;

    public int $pointsOwn = 0;

    public int $pointsOponent = 0;

    public int $win = 0;

    public int $lose = 0;

    public int $winExt = 0;

    public int $loseExt = 0;

    public int $score = 0;

    public function __construct(
        private readonly Team $team,
        private readonly int $teamId,
    ) {
    }

    public function toDto(): TeamScoreDto
    {
        return new TeamScoreDto(
            team: $this->team,
            teamId: $this->teamId,
            matches: $this->matches,
            legsOwn: $this->legsOwn,
            legsOponent: $this->legsOponent,
            pointsOwn: $this->pointsOwn,
            pointsOponent: $this->pointsOponent,
            win: $this->win,
            lose: $this->lose,
            winExt: $this->winExt,
            loseExt: $this->loseExt,
            score: $this->score,
        );
    }
}
