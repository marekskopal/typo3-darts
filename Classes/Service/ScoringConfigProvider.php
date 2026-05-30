<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Service;

use MarekSkopal\MsDarts\Configuration\ScoringConfig;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

class ScoringConfigProvider
{
    private const EXTENSION_KEY = 'ms_darts';

    public function __construct(private readonly ExtensionConfiguration $extensionConfiguration)
    {
    }

    public function get(): ScoringConfig
    {
        $defaults = new ScoringConfig();

        return new ScoringConfig(
            overtimeLegThreshold: $this->readInt('overtimeLegThreshold', $defaults->overtimeLegThreshold),
            pointsWin: $this->readInt('pointsWin', $defaults->pointsWin),
            pointsOvertimeWin: $this->readInt('pointsOvertimeWin', $defaults->pointsOvertimeWin),
            pointsOvertimeLose: $this->readInt('pointsOvertimeLose', $defaults->pointsOvertimeLose),
        );
    }

    private function readInt(string $path, int $default): int
    {
        try {
            $value = $this->extensionConfiguration->get(self::EXTENSION_KEY, $path);
        } catch (ExtensionConfigurationExtensionNotConfiguredException | ExtensionConfigurationPathDoesNotExistException) {
            return $default;
        }

        if (is_int($value)) {
            return $value;
        }
        if (is_string($value) && $value !== '' && ctype_digit(ltrim($value, '-'))) {
            return (int) $value;
        }
        return $default;
    }
}
