<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Dto;

use MarekSkopal\MsDarts\Domain\Model\Team;
use MarekSkopal\MsDarts\Dto\TeamScoreDto;
use PHPUnit\Framework\TestCase;

final class TeamScoreDtoTest extends TestCase
{
    public function testGettersExposeConstructorArguments(): void
    {
        $team = new Team();
        $dto = $this->makeDto(
            $team,
            teamId: 42,
            matches: 5,
            win: 2,
            lose: 1,
            winExt: 1,
            loseExt: 1,
            score: 7,
            pointsOwn: 30,
            pointsOponent: 12,
            legsOwn: 21,
            legsOponent: 9,
        );

        self::assertSame($team, $dto->getTeam());
        self::assertSame(42, $dto->getTeamId());
        self::assertSame(5, $dto->getMatches());
        self::assertSame(2, $dto->getWin());
        self::assertSame(1, $dto->getLose());
        self::assertSame(1, $dto->getWinExt());
        self::assertSame(1, $dto->getLoseExt());
        self::assertSame(7, $dto->getScore());
        self::assertSame(30, $dto->getPointsOwn());
        self::assertSame(12, $dto->getPointsOponent());
        self::assertSame(21, $dto->getLegsOwn());
        self::assertSame(9, $dto->getLegsOponent());
    }

    private function makeDto(
        Team $team,
        int $teamId = 0,
        int $matches = 0,
        int $legsOwn = 0,
        int $legsOponent = 0,
        int $pointsOwn = 0,
        int $pointsOponent = 0,
        int $win = 0,
        int $lose = 0,
        int $winExt = 0,
        int $loseExt = 0,
        int $score = 0,
    ): TeamScoreDto {
        return new TeamScoreDto(
            team: $team,
            teamId: $teamId,
            matches: $matches,
            legsOwn: $legsOwn,
            legsOponent: $legsOponent,
            pointsOwn: $pointsOwn,
            pointsOponent: $pointsOponent,
            win: $win,
            lose: $lose,
            winExt: $winExt,
            loseExt: $loseExt,
            score: $score,
        );
    }
}
