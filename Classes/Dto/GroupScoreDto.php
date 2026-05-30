<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Dto;

final readonly class GroupScoreDto
{
    /** @param list<TeamScoreDto> $score */
    public function __construct(public string $title, public bool $actual, public int $pid, public array $score)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getActual(): bool
    {
        return $this->actual;
    }

    public function getPid(): int
    {
        return $this->pid;
    }

    /** @return list<TeamScoreDto> */
    public function getScore(): array
    {
        return $this->score;
    }
}
