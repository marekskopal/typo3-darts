<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Team extends AbstractEntity
{
    protected string $title = '';

    protected string $place = '';

    protected string $address = '';

    /** @var ObjectStorage<Group> */
    #[Lazy]
    protected ObjectStorage $groups;

    protected string $loginCode = '';

    protected int $playingDay = 1;

    /** @var ObjectStorage<Player> */
    #[Lazy]
    protected ObjectStorage $players;

    public function __construct()
    {
        $this->groups = new ObjectStorage();
        $this->players = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPlace(): string
    {
        return $this->place;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    /** @return ObjectStorage<Group> */
    public function getGroups(): ObjectStorage
    {
        return $this->groups;
    }

    public function getLoginCode(): string
    {
        return $this->loginCode;
    }

    public function getPlayingDay(): int
    {
        return $this->playingDay;
    }

    /** @return ObjectStorage<Player> */
    public function getPlayers(): ObjectStorage
    {
        return $this->players;
    }
}
