<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Service;

use MarekSkopal\MsDarts\Configuration\ScoringConfig;
use MarekSkopal\MsDarts\Domain\Model\MatchScore;
use MarekSkopal\MsDarts\Domain\Model\Team;
use MarekSkopal\MsDarts\Service\ScoreCalculator;
use MarekSkopal\MsDarts\Service\ScoringConfigProvider;
use PHPUnit\Framework\TestCase;

final class ScoreCalculatorTest extends TestCase
{
    private const TEAM_A_UID = 1;
    private const TEAM_B_UID = 2;
    private const TEAM_C_UID = 3;

    public function testEmptyMatchesProduceZeroedScores(): void
    {
        $teamA = $this->makeTeam(self::TEAM_A_UID, 'A');
        $teamB = $this->makeTeam(self::TEAM_B_UID, 'B');

        $result = $this->calculator()->calculate([$teamA, $teamB], []);

        self::assertCount(2, $result);
        foreach ($result as $teamScore) {
            self::assertSame(0, $teamScore->matches);
            self::assertSame(0, $teamScore->score);
        }
    }

    public function testRegularWinUsesPointsWinFromConfig(): void
    {
        $teamA = $this->makeTeam(self::TEAM_A_UID, 'A');
        $teamB = $this->makeTeam(self::TEAM_B_UID, 'B');

        $match = $this->makeMatch($teamA, $teamB, points1: 5, points2: 3, leg1: 5, leg2: 4);

        $result = $this->calculator()->calculate([$teamA, $teamB], [$match]);
        $byTeam = $this->indexByTeamId($result);

        $a = $byTeam[self::TEAM_A_UID];
        $b = $byTeam[self::TEAM_B_UID];

        self::assertSame(1, $a->win);
        self::assertSame(0, $a->lose);
        self::assertSame(3, $a->score);
        self::assertSame(5, $a->pointsOwn);
        self::assertSame(3, $a->pointsOponent);
        self::assertSame(5, $a->legsOwn);
        self::assertSame(4, $a->legsOponent);

        self::assertSame(0, $b->win);
        self::assertSame(1, $b->lose);
        self::assertSame(0, $b->score);
    }

    public function testTeam1HittingOvertimeThresholdMakesItTheOvertimeLoser(): void
    {
        $teamA = $this->makeTeam(self::TEAM_A_UID, 'A');
        $teamB = $this->makeTeam(self::TEAM_B_UID, 'B');

        $match = $this->makeMatch($teamA, $teamB, points1: 9, points2: 8);

        $result = $this->calculator()->calculate([$teamA, $teamB], [$match]);
        $byTeam = $this->indexByTeamId($result);

        self::assertSame(1, $byTeam[self::TEAM_A_UID]->loseExt);
        self::assertSame(1, $byTeam[self::TEAM_B_UID]->winExt);
        self::assertSame(1, $byTeam[self::TEAM_A_UID]->score);
        self::assertSame(2, $byTeam[self::TEAM_B_UID]->score);
    }

    public function testTeam2HittingOvertimeThresholdMakesItTheOvertimeLoser(): void
    {
        $teamA = $this->makeTeam(self::TEAM_A_UID, 'A');
        $teamB = $this->makeTeam(self::TEAM_B_UID, 'B');

        $match = $this->makeMatch($teamA, $teamB, points1: 8, points2: 9);

        $result = $this->calculator()->calculate([$teamA, $teamB], [$match]);
        $byTeam = $this->indexByTeamId($result);

        self::assertSame(1, $byTeam[self::TEAM_A_UID]->winExt);
        self::assertSame(1, $byTeam[self::TEAM_B_UID]->loseExt);
        self::assertSame(2, $byTeam[self::TEAM_A_UID]->score);
        self::assertSame(1, $byTeam[self::TEAM_B_UID]->score);
    }

    public function testManualScoreOverridesAutomaticPoints(): void
    {
        $teamA = $this->makeTeam(self::TEAM_A_UID, 'A');
        $teamB = $this->makeTeam(self::TEAM_B_UID, 'B');

        $match = $this->makeMatch(
            $teamA,
            $teamB,
            points1: 5,
            points2: 3,
            scoreManual: true,
            score1: 10,
            score2: 20,
        );

        $result = $this->calculator()->calculate([$teamA, $teamB], [$match]);
        $byTeam = $this->indexByTeamId($result);

        self::assertSame(10, $byTeam[self::TEAM_A_UID]->score);
        self::assertSame(20, $byTeam[self::TEAM_B_UID]->score);
    }

    public function testCustomScoringConfigIsHonoured(): void
    {
        $teamA = $this->makeTeam(self::TEAM_A_UID, 'A');
        $teamB = $this->makeTeam(self::TEAM_B_UID, 'B');

        $config = new ScoringConfig(
            overtimeLegThreshold: 7,
            pointsWin: 5,
            pointsOvertimeWin: 4,
            pointsOvertimeLose: 2,
        );

        $match1 = $this->makeMatch($teamA, $teamB, points1: 5, points2: 2); // regular win for A
        // points1 hits the threshold — A is the overtime loser, B is the overtime winner
        $match2 = $this->makeMatch($teamA, $teamB, points1: 7, points2: 6);

        $result = $this->calculator($config)->calculate([$teamA, $teamB], [$match1, $match2]);
        $byTeam = $this->indexByTeamId($result);

        // A: 5 (regular win) + 2 (overtime lose) = 7
        self::assertSame(7, $byTeam[self::TEAM_A_UID]->score);
        // B: 0 + 4 (overtime win) = 4
        self::assertSame(4, $byTeam[self::TEAM_B_UID]->score);
    }

    public function testMatchesReferringToUnknownTeamsAreIgnored(): void
    {
        $teamA = $this->makeTeam(self::TEAM_A_UID, 'A');
        $teamB = $this->makeTeam(self::TEAM_B_UID, 'B');
        $teamC = $this->makeTeam(self::TEAM_C_UID, 'C');

        // Only A and B are tracked; a match involving C should be skipped entirely.
        $match = $this->makeMatch($teamA, $teamC, points1: 5, points2: 3);

        $result = $this->calculator()->calculate([$teamA, $teamB], [$match]);
        $byTeam = $this->indexByTeamId($result);

        self::assertSame(0, $byTeam[self::TEAM_A_UID]->matches);
        self::assertSame(0, $byTeam[self::TEAM_A_UID]->score);
        self::assertSame(0, $byTeam[self::TEAM_B_UID]->matches);
    }

    public function testStandingsAreSortedByScoreThenPointsThenLegs(): void
    {
        $teamA = $this->makeTeam(self::TEAM_A_UID, 'A'); // lowest score
        $teamB = $this->makeTeam(self::TEAM_B_UID, 'B'); // medium
        $teamC = $this->makeTeam(self::TEAM_C_UID, 'C'); // highest

        $matches = [
            // C beats B 5-1
            $this->makeMatch($teamC, $teamB, points1: 5, points2: 1, leg1: 5, leg2: 1),
            // B beats A 4-2
            $this->makeMatch($teamB, $teamA, points1: 4, points2: 2, leg1: 4, leg2: 2),
            // C beats A 6-0
            $this->makeMatch($teamC, $teamA, points1: 6, points2: 0, leg1: 6, leg2: 0),
        ];

        $result = $this->calculator()->calculate([$teamA, $teamB, $teamC], $matches);

        self::assertSame(self::TEAM_C_UID, $result[0]->getTeamId());
        self::assertSame(self::TEAM_B_UID, $result[1]->getTeamId());
        self::assertSame(self::TEAM_A_UID, $result[2]->getTeamId());
    }

    public function testTiebreakerByPointsOwnWhenScoreIsEqual(): void
    {
        $teamA = $this->makeTeam(self::TEAM_A_UID, 'A');
        $teamB = $this->makeTeam(self::TEAM_B_UID, 'B');
        $teamC = $this->makeTeam(self::TEAM_C_UID, 'C');

        // A and B each beat C once with the same +3 score, but A scores more raw points.
        $matches = [
            $this->makeMatch($teamA, $teamC, points1: 7, points2: 1, leg1: 7, leg2: 1),
            $this->makeMatch($teamB, $teamC, points1: 5, points2: 2, leg1: 5, leg2: 2),
        ];

        $result = $this->calculator()->calculate([$teamA, $teamB, $teamC], $matches);

        self::assertSame(3, $result[0]->score);
        self::assertSame(3, $result[1]->score);
        self::assertSame(self::TEAM_A_UID, $result[0]->getTeamId());
        self::assertSame(self::TEAM_B_UID, $result[1]->getTeamId());
        self::assertSame(self::TEAM_C_UID, $result[2]->getTeamId());
    }

    private function calculator(?ScoringConfig $config = null): ScoreCalculator
    {
        $provider = $this->createStub(ScoringConfigProvider::class);
        $provider->method('get')->willReturn($config ?? new ScoringConfig());

        return new ScoreCalculator($provider);
    }

    private function makeTeam(int $uid, string $title): Team
    {
        $team = new Team();
        $team->_setProperty('uid', $uid);
        $team->_setProperty('title', $title);
        return $team;
    }

    private function makeMatch(
        Team $team1,
        Team $team2,
        int $points1 = 0,
        int $points2 = 0,
        int $leg1 = 0,
        int $leg2 = 0,
        bool $scoreManual = false,
        int $score1 = 0,
        int $score2 = 0,
    ): MatchScore {
        $match = new MatchScore();
        $match->_setProperty('team1', $team1);
        $match->_setProperty('team2', $team2);
        $match->_setProperty('points1', $points1);
        $match->_setProperty('points2', $points2);
        $match->_setProperty('leg1', $leg1);
        $match->_setProperty('leg2', $leg2);
        $match->_setProperty('scoreManual', $scoreManual);
        $match->_setProperty('score1', $score1);
        $match->_setProperty('score2', $score2);
        return $match;
    }

    /**
     * @param list<\MarekSkopal\MsDarts\Dto\TeamScoreDto> $scores
     * @return array<int, \MarekSkopal\MsDarts\Dto\TeamScoreDto>
     */
    private function indexByTeamId(array $scores): array
    {
        $byTeam = [];
        foreach ($scores as $score) {
            $byTeam[$score->getTeamId()] = $score;
        }
        return $byTeam;
    }
}
