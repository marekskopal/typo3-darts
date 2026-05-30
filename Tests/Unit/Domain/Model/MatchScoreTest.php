<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Domain\Model;

use MarekSkopal\MsDarts\Domain\Model\MatchScore;
use MarekSkopal\MsDarts\Domain\Model\Team;
use PHPUnit\Framework\TestCase;

final class MatchScoreTest extends TestCase
{
    private MatchScore $match;

    protected function setUp(): void
    {
        $this->match = new MatchScore();
    }

    public function testNumericPropertiesDefaultToZero(): void
    {
        self::assertSame(0, $this->match->getRound());
        self::assertSame(0, $this->match->getLeg1());
        self::assertSame(0, $this->match->getLeg2());
        self::assertSame(0, $this->match->getPoints1());
        self::assertSame(0, $this->match->getPoints2());
        self::assertSame(0, $this->match->getScore1());
        self::assertSame(0, $this->match->getScore2());
    }

    public function testScoreManualDefaultsToFalse(): void
    {
        self::assertFalse($this->match->getScoreManual());
    }

    public function testMatchDateDefaultsToNull(): void
    {
        self::assertNull($this->match->getMatchDate());
    }

    public function testIsPlayedReturnsFalseWhenDateIsNotSet(): void
    {
        self::assertFalse($this->match->getIsPlayed());
    }

    public function testIsPlayedReturnsFalseWhenNoPointsScored(): void
    {
        $this->match->_setProperty('matchDate', new \DateTime('-1 day'));

        self::assertFalse($this->match->getIsPlayed());
    }

    public function testIsPlayedReturnsTrueWhenDateInPastAndPointsScored(): void
    {
        $this->match->_setProperty('matchDate', new \DateTime('-1 day'));
        $this->match->_setProperty('points1', 5);

        self::assertTrue($this->match->getIsPlayed());
    }

    public function testIsPlayedReturnsFalseWhenDateInFuture(): void
    {
        $this->match->_setProperty('matchDate', new \DateTime('+1 day'));
        $this->match->_setProperty('points1', 5);

        self::assertFalse($this->match->getIsPlayed());
    }

    public function testGetTeam1ReturnsAssignedTeam(): void
    {
        $team = new Team();
        $this->match->_setProperty('team1', $team);

        self::assertSame($team, $this->match->getTeam1());
    }

    public function testGetTeam2ReturnsAssignedTeam(): void
    {
        $team = new Team();
        $this->match->_setProperty('team2', $team);

        self::assertSame($team, $this->match->getTeam2());
    }
}
