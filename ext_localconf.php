<?php

declare(strict_types=1);

use MarekSkopal\MsDarts\Controller\LeagueController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die;

ExtensionUtility::configurePlugin(
    'MsDarts',
    'DartsScore',
    [
        LeagueController::class => 'score',
    ],
    [
        LeagueController::class => 'score',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'MsDarts',
    'DartsTeamList',
    [
        LeagueController::class => 'teamList, processCode',
    ],
    [
        LeagueController::class => 'teamList, processCode',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'MsDarts',
    'DartsMatchList',
    [
        LeagueController::class => 'matchList',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);
