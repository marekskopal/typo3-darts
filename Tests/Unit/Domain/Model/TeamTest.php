<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Domain\Model;

use MarekSkopal\MsDarts\Domain\Model\Group;
use MarekSkopal\MsDarts\Domain\Model\Player;
use MarekSkopal\MsDarts\Domain\Model\Team;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

final class TeamTest extends TestCase
{
    private Team $team;

    protected function setUp(): void
    {
        $this->team = new Team();
    }

    public function testStringPropertiesDefaultToEmpty(): void
    {
        self::assertSame('', $this->team->getTitle());
        self::assertSame('', $this->team->getPlace());
        self::assertSame('', $this->team->getAddress());
        self::assertSame('', $this->team->getLoginCode());
    }

    public function testPlayingDayDefaultsToMonday(): void
    {
        self::assertSame(1, $this->team->getPlayingDay());
    }

    public function testGroupsDefaultsToEmptyObjectStorage(): void
    {
        self::assertInstanceOf(ObjectStorage::class, $this->team->getGroups());
        self::assertSame(0, $this->team->getGroups()->count());
    }

    public function testPlayersDefaultsToEmptyObjectStorage(): void
    {
        self::assertInstanceOf(ObjectStorage::class, $this->team->getPlayers());
        self::assertSame(0, $this->team->getPlayers()->count());
    }

    public function testGetGroupsReturnsAttachedGroups(): void
    {
        $group = new Group();
        $storage = new ObjectStorage();
        $storage->attach($group);

        $this->team->_setProperty('groups', $storage);

        self::assertSame(1, $this->team->getGroups()->count());
    }

    public function testGetPlayersReturnsAttachedPlayers(): void
    {
        $player = new Player();
        $storage = new ObjectStorage();
        $storage->attach($player);

        $this->team->_setProperty('players', $storage);

        self::assertSame(1, $this->team->getPlayers()->count());
    }
}
