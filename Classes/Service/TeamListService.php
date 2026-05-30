<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Service;

use MarekSkopal\MsDarts\Domain\Repository\GroupRepository;
use MarekSkopal\MsDarts\Domain\Repository\TeamRepository;
use MarekSkopal\MsDarts\Dto\TeamListGroupDto;

class TeamListService
{
    public function __construct(private readonly GroupRepository $groupRepository, private readonly TeamRepository $teamRepository)
    {
    }

    /** @return list<TeamListGroupDto> */
    public function getTeamList(): array
    {
        $groups = $this->groupRepository->findAllAsList();

        if ($groups === []) {
            return [
                new TeamListGroupDto(
                    title: '',
                    teams: $this->teamRepository->findByGroup(null),
                ),
            ];
        }

        $result = [];
        foreach ($groups as $group) {
            /** @phpstan-ignore-next-line method.internalClass */
            $groupUid = $group->getUid();
            $result[] = new TeamListGroupDto(
                title: $group->getTitle(),
                teams: $this->teamRepository->findByGroup($groupUid),
            );
        }

        return $result;
    }
}
