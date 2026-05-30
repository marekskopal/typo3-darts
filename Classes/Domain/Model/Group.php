<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Group extends AbstractEntity
{
    protected string $title = '';

    protected bool $actual = true;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getActual(): bool
    {
        return $this->actual;
    }
}
