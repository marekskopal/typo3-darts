<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Dto;

use MarekSkopal\MsDarts\Domain\Model\Team;

final readonly class TeamListGroupDto
{
    /** @param iterable<Team> $teams */
    public function __construct(public string $title, public iterable $teams)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /** @return iterable<Team> */
    public function getTeams(): iterable
    {
        return $this->teams;
    }
}
