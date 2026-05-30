<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Tests\Unit\Service;

use MarekSkopal\MsDarts\Service\ScoringConfigProvider;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

final class ScoringConfigProviderTest extends TestCase
{
    public function testReturnsDefaultsWhenExtensionIsUnconfigured(): void
    {
        $extensionConfiguration = $this->createStub(ExtensionConfiguration::class);
        $extensionConfiguration
            ->method('get')
            ->willThrowException(new ExtensionConfigurationExtensionNotConfiguredException());

        $config = (new ScoringConfigProvider($extensionConfiguration))->get();

        self::assertSame(9, $config->overtimeLegThreshold);
        self::assertSame(3, $config->pointsWin);
        self::assertSame(2, $config->pointsOvertimeWin);
        self::assertSame(1, $config->pointsOvertimeLose);
    }

    public function testFallsBackToDefaultForMissingPaths(): void
    {
        $extensionConfiguration = $this->createStub(ExtensionConfiguration::class);
        $extensionConfiguration
            ->method('get')
            ->willReturnCallback(static function (string $extension, string $path): int {
                if ($path === 'pointsWin') {
                    return 5;
                }
                throw new ExtensionConfigurationPathDoesNotExistException();
            });

        $config = (new ScoringConfigProvider($extensionConfiguration))->get();

        self::assertSame(9, $config->overtimeLegThreshold);
        self::assertSame(5, $config->pointsWin);
        self::assertSame(2, $config->pointsOvertimeWin);
        self::assertSame(1, $config->pointsOvertimeLose);
    }

    public function testReadsAllValuesWhenProvided(): void
    {
        $values = [
            'overtimeLegThreshold' => 7,
            'pointsWin' => '5',
            'pointsOvertimeWin' => 4,
            'pointsOvertimeLose' => '2',
        ];

        $extensionConfiguration = $this->createStub(ExtensionConfiguration::class);
        $extensionConfiguration
            ->method('get')
            ->willReturnCallback(static function (string $extension, string $path) use ($values): int|string {
                return $values[$path];
            });

        $config = (new ScoringConfigProvider($extensionConfiguration))->get();

        self::assertSame(7, $config->overtimeLegThreshold);
        self::assertSame(5, $config->pointsWin);
        self::assertSame(4, $config->pointsOvertimeWin);
        self::assertSame(2, $config->pointsOvertimeLose);
    }

    public function testNonNumericValueFallsBackToDefault(): void
    {
        $extensionConfiguration = $this->createStub(ExtensionConfiguration::class);
        $extensionConfiguration
            ->method('get')
            ->willReturn('not-a-number');

        $config = (new ScoringConfigProvider($extensionConfiguration))->get();

        self::assertSame(9, $config->overtimeLegThreshold);
        self::assertSame(3, $config->pointsWin);
    }
}
