<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Service;

use MarekSkopal\MsDarts\Domain\Model\Group;
use MarekSkopal\MsDarts\Domain\Repository\GroupRepository;
use MarekSkopal\MsDarts\Domain\Repository\MatchScoreRepository;
use MarekSkopal\MsDarts\Domain\Repository\TeamRepository;
use MarekSkopal\MsDarts\Dto\GroupScoreDto;

class LeagueScoreService
{
    public function __construct(
        private readonly GroupRepository $groupRepository,
        private readonly TeamRepository $teamRepository,
        private readonly MatchScoreRepository $matchScoreRepository,
        private readonly ScoreCalculator $scoreCalculator,
    ) {
    }

    /** @return list<GroupScoreDto> */
    public function getStandings(): array
    {
        $groups = $this->groupRepository->findAllAsList();

        if ($groups === []) {
            return [$this->buildUngroupedStandings()];
        }

        $groupScores = [];
        foreach ($groups as $group) {
            $groupScores[] = $this->buildGroupStandings($group);
        }

        usort($groupScores, static function (GroupScoreDto $a, GroupScoreDto $b): int {
            if ($a->actual && !$b->actual) {
                return -1;
            }
            if (!$a->actual && $b->actual) {
                return 1;
            }
            return 0;
        });

        return $groupScores;
    }

    private function buildUngroupedStandings(): GroupScoreDto
    {
        $teams = $this->teamRepository->findByGroup(null);
        $teamIds = [];
        foreach ($teams as $team) {
            /** @phpstan-ignore-next-line method.internalClass */
            $uid = $team->getUid();
            if ($uid !== null) {
                $teamIds[] = $uid;
            }
        }

        $matches = $teamIds === [] ? [] : $this->matchScoreRepository->findPlayedMatchesForTeams($teamIds);
        $score = $this->scoreCalculator->calculate($teams, $matches);

        return new GroupScoreDto(title: '', actual: true, pid: 0, score: $score);
    }

    private function buildGroupStandings(Group $group): GroupScoreDto
    {
        /** @phpstan-ignore-next-line method.internalClass */
        $groupUid = $group->getUid();
        /** @phpstan-ignore-next-line method.internalClass */
        $groupPid = $group->getPid() ?? 0;

        if ($groupUid === null) {
            return new GroupScoreDto(
                title: $group->getTitle(),
                actual: $group->getActual(),
                pid: $groupPid,
                score: [],
            );
        }

        $teams = $this->teamRepository->findByGroup($groupUid);
        $matches = $this->matchScoreRepository->findPlayedMatchesForGroup($groupUid, $groupPid);
        $score = $this->scoreCalculator->calculate($teams, $matches);

        return new GroupScoreDto(
            title: $group->getTitle(),
            actual: $group->getActual(),
            pid: $groupPid,
            score: $score,
        );
    }
}
