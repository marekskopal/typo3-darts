<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Domain\Model;

use MarekSkopal\MsDarts\Domain\Model\Player;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

final class PlayerTest extends TestCase
{
    private Player $player;

    protected function setUp(): void
    {
        $this->player = new Player();
    }

    public function testStringPropertiesDefaultToEmpty(): void
    {
        self::assertSame('', $this->player->getFirstName());
        self::assertSame('', $this->player->getLastName());
        self::assertSame('', $this->player->getPhone());
        self::assertSame('', $this->player->getEmail());
    }

    public function testImagesDefaultsToEmptyObjectStorage(): void
    {
        self::assertInstanceOf(ObjectStorage::class, $this->player->getImages());
        self::assertSame(0, $this->player->getImages()->count());
    }

    public function testGetImageMainReturnsNullWhenNoImagesSet(): void
    {
        self::assertNull($this->player->getImageMain());
    }
}
