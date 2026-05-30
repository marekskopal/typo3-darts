<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Service;

use MarekSkopal\MsDarts\Domain\Repository\TeamRepository;
use MarekSkopal\MsDarts\Utility\SessionStorage;

class LoginCodeService
{
    private const PERMISSION_KEY = 'permission';

    public function __construct(private readonly TeamRepository $teamRepository, private readonly SessionStorage $sessionStorage)
    {
    }

    public function authorize(string $code): bool
    {
        $team = $this->teamRepository->findByLoginCode($code)->getFirst();
        if ($team === null) {
            return false;
        }

        $this->sessionStorage->set(self::PERMISSION_KEY, true);
        return true;
    }

    public function hasPermission(): bool
    {
        return $this->sessionStorage->has(self::PERMISSION_KEY);
    }
}
