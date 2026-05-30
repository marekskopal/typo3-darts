<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Domain\Repository;

use MarekSkopal\MsDarts\Domain\Model\Group;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/** @extends Repository<Group> */
class GroupRepository extends Repository
{
    /** @var array<string, string> */
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING,
    ];

    /** @return list<Group> */
    public function findAllAsList(): array
    {
        $result = $this->findAll();
        /** @var list<Group> $items */
        $items = $result instanceof QueryResultInterface
            ? $result->toArray()
            : iterator_to_array($result, false);
        return $items;
    }
}
