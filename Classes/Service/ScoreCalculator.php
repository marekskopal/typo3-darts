<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Service;

use MarekSkopal\MsDarts\Configuration\ScoringConfig;
use MarekSkopal\MsDarts\Domain\Model\MatchScore;
use MarekSkopal\MsDarts\Domain\Model\Team;
use MarekSkopal\MsDarts\Dto\TeamScoreDto;

class ScoreCalculator
{
    public function __construct(private readonly ScoringConfigProvider $scoringConfigProvider)
    {
    }

    /**
     * @param iterable<Team> $teams
     * @param iterable<MatchScore> $matches
     * @return list<TeamScoreDto>
     */
    public function calculate(iterable $teams, iterable $matches): array
    {
        $config = $this->scoringConfigProvider->get();

        /** @var array<int, TeamScoreAccumulator> $accumulators */
        $accumulators = [];

        foreach ($teams as $team) {
            /** @phpstan-ignore-next-line method.internalClass */
            $uid = $team->getUid();
            if ($uid === null) {
                continue;
            }
            $accumulators[$uid] = new TeamScoreAccumulator($team, $uid);
        }

        foreach ($matches as $match) {
            $this->applyMatch($accumulators, $match, $config);
        }

        $dtos = [];
        foreach ($accumulators as $accumulator) {
            $dtos[] = $accumulator->toDto();
        }

        usort($dtos, static function (TeamScoreDto $a, TeamScoreDto $b): int {
            if ($a->score !== $b->score) {
                return $a->score > $b->score ? -1 : 1;
            }
            if ($a->pointsOwn !== $b->pointsOwn) {
                return $a->pointsOwn > $b->pointsOwn ? -1 : 1;
            }
            if ($a->legsOwn === $b->legsOwn) {
                return 0;
            }
            return $a->legsOwn > $b->legsOwn ? -1 : 1;
        });

        return $dtos;
    }

    /** @param array<int, TeamScoreAccumulator> $accumulators */
    private function applyMatch(array $accumulators, MatchScore $match, ScoringConfig $config): void
    {
        /** @phpstan-ignore-next-line method.internalClass */
        $team1Uid = $match->getTeam1()->getUid();
        /** @phpstan-ignore-next-line method.internalClass */
        $team2Uid = $match->getTeam2()->getUid();
        if ($team1Uid === null || $team2Uid === null) {
            return;
        }
        if (!isset($accumulators[$team1Uid]) || !isset($accumulators[$team2Uid])) {
            return;
        }

        $team1 = $accumulators[$team1Uid];
        $team2 = $accumulators[$team2Uid];

        $team1->matches++;
        $team2->matches++;

        $team1->legsOwn += $match->getLeg1();
        $team1->legsOponent += $match->getLeg2();
        $team2->legsOwn += $match->getLeg2();
        $team2->legsOponent += $match->getLeg1();

        $team1->pointsOwn += $match->getPoints1();
        $team1->pointsOponent += $match->getPoints2();
        $team2->pointsOwn += $match->getPoints2();
        $team2->pointsOponent += $match->getPoints1();

        $threshold = $config->overtimeLegThreshold;

        if ($match->getPoints1() === $threshold) {
            $team1->loseExt++;
            $team2->winExt++;
            $this->addOutcomePoints(
                $match,
                $team1,
                $team2,
                $config->pointsOvertimeLose,
                $config->pointsOvertimeWin,
            );
            return;
        }

        if ($match->getPoints2() === $threshold) {
            $team2->loseExt++;
            $team1->winExt++;
            $this->addOutcomePoints(
                $match,
                $team1,
                $team2,
                $config->pointsOvertimeWin,
                $config->pointsOvertimeLose,
            );
            return;
        }

        if ($match->getPoints1() > $match->getPoints2()) {
            $team1->win++;
            $team2->lose++;
            $this->addOutcomePoints($match, $team1, $team2, $config->pointsWin, 0);
            return;
        }

        $team2->win++;
        $team1->lose++;
        $this->addOutcomePoints($match, $team1, $team2, 0, $config->pointsWin);
    }

    private function addOutcomePoints(
        MatchScore $match,
        TeamScoreAccumulator $team1,
        TeamScoreAccumulator $team2,
        int $autoPoints1,
        int $autoPoints2,
    ): void {
        if ($match->getScoreManual()) {
            $team1->score += $match->getScore1();
            $team2->score += $match->getScore2();
            return;
        }
        $team1->score += $autoPoints1;
        $team2->score += $autoPoints2;
    }
}
