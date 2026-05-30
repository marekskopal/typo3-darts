<?php

declare(strict_types=1);

$llPath = 'LLL:EXT:ms_darts/Resources/Private/Language/locallang_db.xlf';
$table = 'tx_msdarts_domain_model_team';

return [
    'ctrl' => [
        'title' => $llPath . ':' . $table,
        'label' => 'title',
        'sortby' => 'sorting',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title, place, address',
        'iconfile' => 'EXT:ms_darts/Resources/Public/Icons/' . $table . '.svg',
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    ['label' => '', 'invertStateDisplay' => true],
                ],
            ],
        ],
        'title' => [
            'label' => $llPath . ':' . $table . '.title',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'groups' => [
            'label' => $llPath . ':' . $table . '.group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'foreign_table' => 'tx_msdarts_domain_model_group',
                'foreign_table_where' => ' AND (tx_msdarts_domain_model_group.pid=###CURRENT_PID### OR tx_msdarts_domain_model_group.pid IN (###PAGE_TSCONFIG_IDLIST###))',
                'MM' => 'tx_msdarts_team_group_mm',
                'minitems' => 1,
                'maxitems' => 10,
            ],
        ],
        'place' => [
            'label' => $llPath . ':' . $table . '.place',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'address' => [
            'label' => $llPath . ':' . $table . '.address',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'login_code' => [
            'label' => $llPath . ':' . $table . '.login_code',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'playing_day' => [
            'label' => $llPath . ':' . $table . '.playing_day',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => $llPath . ':' . $table . '.playing_day_monday', 'value' => 1],
                    ['label' => $llPath . ':' . $table . '.playing_day_tuesday', 'value' => 2],
                    ['label' => $llPath . ':' . $table . '.playing_day_wednesday', 'value' => 3],
                    ['label' => $llPath . ':' . $table . '.playing_day_thursday', 'value' => 4],
                    ['label' => $llPath . ':' . $table . '.playing_day_friday', 'value' => 5],
                    ['label' => $llPath . ':' . $table . '.playing_day_saturday', 'value' => 6],
                    ['label' => $llPath . ':' . $table . '.playing_day_sunday', 'value' => 7],
                ],
            ],
        ],
        'players' => [
            'label' => $llPath . ':' . $table . '.players',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_msdarts_domain_model_player',
                'foreign_field' => 'team',
                'appearance' => [
                    'collapseAll' => true,
                    'useSortable' => true,
                ],
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title, groups, place, address, login_code, playing_day, players,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            ',
        ],
    ],
];
