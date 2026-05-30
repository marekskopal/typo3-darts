<?php

declare(strict_types=1);

defined('TYPO3') or die;

$table = 'sys_file_reference';
$llPath = 'LLL:EXT:ms_darts/Resources/Private/Language/locallang_db.xlf';

$GLOBALS['TCA'][$table]['columns']['showinpreview']['label'] = $llPath . ':' . $table . '.showinpreview';
