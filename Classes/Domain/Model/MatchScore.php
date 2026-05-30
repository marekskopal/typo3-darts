<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class MatchScore extends AbstractEntity
{
    protected int $round = 0;

    protected ?\DateTime $matchDate = null;

    protected Team $team1;

    protected Team $team2;

    protected int $leg1 = 0;

    protected int $leg2 = 0;

    protected int $points1 = 0;

    protected int $points2 = 0;

    protected bool $scoreManual = false;

    protected int $score1 = 0;

    protected int $score2 = 0;

    public function getRound(): int
    {
        return $this->round;
    }

    public function getMatchDate(): ?\DateTime
    {
        return $this->matchDate;
    }

    public function getTeam1(): Team
    {
        return $this->team1;
    }

    public function getTeam2(): Team
    {
        return $this->team2;
    }

    public function getLeg1(): int
    {
        return $this->leg1;
    }

    public function getLeg2(): int
    {
        return $this->leg2;
    }

    public function getPoints1(): int
    {
        return $this->points1;
    }

    public function getPoints2(): int
    {
        return $this->points2;
    }

    public function getScoreManual(): bool
    {
        return $this->scoreManual;
    }

    public function getScore1(): int
    {
        return $this->score1;
    }

    public function getScore2(): int
    {
        return $this->score2;
    }

    public function getIsPlayed(): bool
    {
        if ($this->matchDate === null) {
            return false;
        }
        return $this->matchDate->getTimestamp() <= time() && ($this->points1 !== 0 || $this->points2 !== 0);
    }
}
