<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Dto;

use MarekSkopal\MsDarts\Domain\Model\Team;

final readonly class TeamScoreDto
{
    public function __construct(
        public Team $team,
        public int $teamId,
        public int $matches,
        public int $legsOwn,
        public int $legsOponent,
        public int $pointsOwn,
        public int $pointsOponent,
        public int $win,
        public int $lose,
        public int $winExt,
        public int $loseExt,
        public int $score,
    ) {
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getTeamId(): int
    {
        return $this->teamId;
    }

    public function getMatches(): int
    {
        return $this->matches;
    }

    public function getLegsOwn(): int
    {
        return $this->legsOwn;
    }

    public function getLegsOponent(): int
    {
        return $this->legsOponent;
    }

    public function getPointsOwn(): int
    {
        return $this->pointsOwn;
    }

    public function getPointsOponent(): int
    {
        return $this->pointsOponent;
    }

    public function getWin(): int
    {
        return $this->win;
    }

    public function getLose(): int
    {
        return $this->lose;
    }

    public function getWinExt(): int
    {
        return $this->winExt;
    }

    public function getLoseExt(): int
    {
        return $this->loseExt;
    }

    public function getScore(): int
    {
        return $this->score;
    }
}
