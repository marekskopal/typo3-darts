<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Dto;

use MarekSkopal\MsDarts\Domain\Model\Team;
use MarekSkopal\MsDarts\Dto\TeamListGroupDto;
use PHPUnit\Framework\TestCase;

final class TeamListGroupDtoTest extends TestCase
{
    public function testGettersExposeConstructorArguments(): void
    {
        $team = new Team();
        $dto = new TeamListGroupDto(title: 'Premier', teams: [$team]);

        self::assertSame('Premier', $dto->getTitle());
        self::assertSame([$team], $dto->getTeams());
    }
}
