<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Utility;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

class SessionStorage implements SingletonInterface
{
    private const SESSION_NAMESPACE = 'tx_msdarts';

    public function get(string $key): mixed
    {
        $sessionData = $this->getFrontendUser()->getKey('ses', self::SESSION_NAMESPACE . $key);
        if ($sessionData === null) {
            throw new \LogicException('No value for key found in session ' . $key);
        }

        return $sessionData;
    }

    public function has(string $key): bool
    {
        try {
            $this->get($key);
        } catch (\LogicException) {
            return false;
        }

        return true;
    }

    public function set(string $key, mixed $value): void
    {
        $this->getFrontendUser()->setKey('ses', self::SESSION_NAMESPACE . $key, $value);
        $this->getFrontendUser()->storeSessionData();
    }

    public function clean(string $key): void
    {
        $this->getFrontendUser()->setKey('ses', self::SESSION_NAMESPACE . $key, null);
        $this->getFrontendUser()->storeSessionData();
    }

    private function getFrontendUser(): FrontendUserAuthentication
    {
        /** @var FrontendUserAuthentication|null $frontendUser */
        $frontendUser = $this->getRequest()->getAttribute('frontend.user');
        if ($frontendUser === null) {
            throw new \LogicException('No FrontendUserAuthentication found!');
        }
        return $frontendUser;
    }

    private function getRequest(): ServerRequestInterface
    {
        /** @var ServerRequestInterface|null $request */
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        if ($request === null) {
            throw new \LogicException('No ServerRequest found!');
        }
        return $request;
    }
}
