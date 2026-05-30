<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Dto;

use MarekSkopal\MsDarts\Domain\Model\Team;
use MarekSkopal\MsDarts\Dto\GroupScoreDto;
use MarekSkopal\MsDarts\Dto\TeamScoreDto;
use PHPUnit\Framework\TestCase;

final class GroupScoreDtoTest extends TestCase
{
    public function testGettersExposeConstructorArguments(): void
    {
        $teamScore = new TeamScoreDto(
            team: new Team(),
            teamId: 1,
            matches: 0,
            legsOwn: 0,
            legsOponent: 0,
            pointsOwn: 0,
            pointsOponent: 0,
            win: 0,
            lose: 0,
            winExt: 0,
            loseExt: 0,
            score: 0,
        );

        $dto = new GroupScoreDto(title: 'A League', actual: true, pid: 17, score: [$teamScore]);

        self::assertSame('A League', $dto->getTitle());
        self::assertTrue($dto->getActual());
        self::assertSame(17, $dto->getPid());
        self::assertSame([$teamScore], $dto->getScore());
    }
}
