<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Domain\Repository;

use MarekSkopal\MsDarts\Domain\Model\Team;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/** @extends Repository<Team> */
class TeamRepository extends Repository
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     * @var array<non-empty-string, QueryInterface::ORDER_*>
     */
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING,
    ];

    /** @return QueryResultInterface<int, Team> */
    public function findByGroup(?int $groupUid): QueryResultInterface
    {
        $query = $this->createQuery();

        if ($groupUid !== null) {
            $query->getQuerySettings()->setRespectStoragePage(false);
        }

        $query->matching(
            $query->equals('groups.uid', $groupUid),
        );

        return $query->execute();
    }

    /** @return QueryResultInterface<int, Team> */
    public function findByLoginCode(string $loginCode): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->equals('loginCode', $loginCode),
        );

        return $query->execute();
    }
}
