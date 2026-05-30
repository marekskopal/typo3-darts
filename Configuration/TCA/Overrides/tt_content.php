<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die;

$pluginSignature = ExtensionUtility::registerPlugin(
    'MsDarts',
    'DartsScore',
    'Darts - Score',
    null,
    'darts',
);
ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:ms_darts/Configuration/FlexForms/Flexform.xml', $pluginSignature);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform, pages, recursive',
    $pluginSignature,
    'after:palette:headers',
);

$pluginSignature = ExtensionUtility::registerPlugin(
    'MsDarts',
    'DartsTeamList',
    'Darts - Team list',
    null,
    'darts',
);
ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:ms_darts/Configuration/FlexForms/Flexform.xml', $pluginSignature);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform, pages, recursive',
    $pluginSignature,
    'after:palette:headers',
);

$pluginSignature = ExtensionUtility::registerPlugin(
    'MsDarts',
    'DartsMatchList',
    'Darts - Match list',
    null,
    'darts',
);
ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:ms_darts/Configuration/FlexForms/Flexform.xml', $pluginSignature);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform, pages, recursive',
    $pluginSignature,
    'after:palette:headers',
);
