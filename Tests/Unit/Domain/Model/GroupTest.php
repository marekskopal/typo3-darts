<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Domain\Model;

use MarekSkopal\MsDarts\Domain\Model\Group;
use PHPUnit\Framework\TestCase;

final class GroupTest extends TestCase
{
    private Group $group;

    protected function setUp(): void
    {
        $this->group = new Group();
    }

    public function testTitleDefaultsToEmptyString(): void
    {
        self::assertSame('', $this->group->getTitle());
    }

    public function testActualDefaultsToTrue(): void
    {
        self::assertTrue($this->group->getActual());
    }

    public function testGetTitleReturnsSetTitle(): void
    {
        $this->group->_setProperty('title', 'A League');

        self::assertSame('A League', $this->group->getTitle());
    }

    public function testGetActualReturnsSetValue(): void
    {
        $this->group->_setProperty('actual', false);

        self::assertFalse($this->group->getActual());
    }
}
