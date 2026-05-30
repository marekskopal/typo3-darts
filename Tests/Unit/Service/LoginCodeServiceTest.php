<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Service;

use MarekSkopal\MsDarts\Domain\Model\Team;
use MarekSkopal\MsDarts\Domain\Repository\TeamRepository;
use MarekSkopal\MsDarts\Service\LoginCodeService;
use MarekSkopal\MsDarts\Utility\SessionStorage;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

final class LoginCodeServiceTest extends TestCase
{
    public function testAuthorizeReturnsFalseAndSkipsSessionWhenCodeIsUnknown(): void
    {
        $queryResult = $this->createStub(QueryResult::class);
        $queryResult->method('getFirst')->willReturn(null);

        $teamRepository = $this->createStub(TeamRepository::class);
        $teamRepository->method('findByLoginCode')->willReturn($queryResult);

        $sessionStorage = $this->createMock(SessionStorage::class);
        $sessionStorage->expects(self::never())->method('set');

        $service = new LoginCodeService($teamRepository, $sessionStorage);

        self::assertFalse($service->authorize('bogus'));
    }

    public function testAuthorizeSetsSessionFlagAndReturnsTrueOnMatch(): void
    {
        $team = new Team();

        $queryResult = $this->createStub(QueryResult::class);
        $queryResult->method('getFirst')->willReturn($team);

        $teamRepository = $this->createStub(TeamRepository::class);
        $teamRepository->method('findByLoginCode')->willReturn($queryResult);

        $sessionStorage = $this->createMock(SessionStorage::class);
        $sessionStorage->expects(self::once())->method('set')->with('permission', true);

        $service = new LoginCodeService($teamRepository, $sessionStorage);

        self::assertTrue($service->authorize('secret'));
    }

    public function testHasPermissionDelegatesToSessionStorage(): void
    {
        $teamRepository = $this->createStub(TeamRepository::class);
        $sessionStorage = $this->createStub(SessionStorage::class);
        $sessionStorage->method('has')->willReturn(true);

        $service = new LoginCodeService($teamRepository, $sessionStorage);

        self::assertTrue($service->hasPermission());
    }
}
