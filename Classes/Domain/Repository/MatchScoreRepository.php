<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Domain\Repository;

use MarekSkopal\MsDarts\Domain\Model\MatchScore;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/** @extends Repository<MatchScore> */
class MatchScoreRepository extends Repository
{
    /** @var array<string, string> */
    protected $defaultOrderings = [
        'round' => QueryInterface::ORDER_ASCENDING,
        'match_date' => QueryInterface::ORDER_ASCENDING,
    ];

    /** @return QueryResultInterface<int, MatchScore> */
    public function findPlayedMatches(): QueryResultInterface
    {
        $query = $this->createQuery();

        $query->matching(
            $query->logicalAnd(
                $query->logicalOr(
                    $query->greaterThan('points1', 0),
                    $query->greaterThan('points2', 0),
                ),
                $query->lessThan('match_date', time()),
            ),
        );

        return $query->execute();
    }

    /** @return QueryResultInterface<int, MatchScore> */
    public function findPlayedMatchesForGroup(int $groupId, int $groupPid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->logicalAnd(
                $query->equals('team1.groups.uid', $groupId),
                $query->logicalOr(
                    $query->greaterThan('points1', 0),
                    $query->greaterThan('points2', 0),
                ),
                $query->lessThan('match_date', time()),
                $query->in('pid', [$groupPid]),
            ),
        );

        return $query->execute();
    }

    /**
     * @param array<int, int> $teamIds
     * @return QueryResultInterface<int, MatchScore>
     */
    public function findPlayedMatchesForTeams(array $teamIds): QueryResultInterface
    {
        $query = $this->createQuery();

        $query->matching(
            $query->logicalAnd(
                $query->logicalOr(
                    $query->in('team1.uid', $teamIds),
                    $query->in('team2.uid', $teamIds),
                ),
                $query->logicalOr(
                    $query->greaterThan('points1', 0),
                    $query->greaterThan('points2', 0),
                ),
                $query->lessThan('match_date', time()),
            ),
        );

        return $query->execute();
    }
}
