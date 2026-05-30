<?php

declare(strict_types=1);

$llPath = 'LLL:EXT:ms_darts/Resources/Private/Language/locallang_db.xlf';
$table = 'tx_msdarts_domain_model_matchscore';

$rounds = [];
for ($i = 1; $i <= 30; $i++) {
    $rounds[] = ['label' => (string) $i, 'value' => (string) $i];
}

return [
    'ctrl' => [
        'title' => $llPath . ':' . $table,
        'label' => 'match_date',
        'label_alt' => 'team1, team2',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'default_sortby' => 'match_date ASC',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'searchFields' => 'match_date',
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
        'matchgroup' => [
            'label' => $llPath . ':' . $table . '.group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_msdarts_domain_model_group',
                'foreign_table_where' => ' AND (tx_msdarts_domain_model_group.pid=###CURRENT_PID###)',
                'items' => [
                    ['label' => $llPath . ':' . $table . '.group_no_group', 'value' => '0'],
                ],
            ],
        ],
        'round' => [
            'label' => $llPath . ':' . $table . '.round',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => $rounds,
            ],
        ],
        'match_date' => [
            'label' => $llPath . ':' . $table . '.match_date',
            'config' => [
                'type' => 'datetime',
                'format' => 'date',
            ],
        ],
        'team1' => [
            'label' => $llPath . ':' . $table . '.team1',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_msdarts_domain_model_team',
                'foreign_table_where' => ' AND (tx_msdarts_domain_model_team.pid=###CURRENT_PID### OR tx_msdarts_domain_model_team.pid IN (###PAGE_TSCONFIG_IDLIST###))',
            ],
        ],
        'team2' => [
            'label' => $llPath . ':' . $table . '.team2',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_msdarts_domain_model_team',
                'foreign_table_where' => ' AND (tx_msdarts_domain_model_team.pid=###CURRENT_PID### OR tx_msdarts_domain_model_team.pid IN (###PAGE_TSCONFIG_IDLIST###))',
            ],
        ],
        'leg1' => [
            'label' => $llPath . ':' . $table . '.leg1',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
            ],
        ],
        'leg2' => [
            'label' => $llPath . ':' . $table . '.leg2',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
            ],
        ],
        'points1' => [
            'label' => $llPath . ':' . $table . '.points1',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
            ],
        ],
        'points2' => [
            'label' => $llPath . ':' . $table . '.points2',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
            ],
        ],
        'score_manual' => [
            'onChange' => 'reload',
            'label' => $llPath . ':' . $table . '.score_manual',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    ['label' => ''],
                ],
            ],
        ],
        'score1' => [
            'displayCond' => 'FIELD:score_manual:REQ:true',
            'label' => $llPath . ':' . $table . '.score1',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
            ],
        ],
        'score2' => [
            'displayCond' => 'FIELD:score_manual:REQ:true',
            'label' => $llPath . ':' . $table . '.score2',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    matchgroup, round, match_date, team1, team2, leg1, leg2, points1, points2, score_manual, score1, score2,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    sys_language_uid, l10n_parent, l10n_diffsource,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            ',
        ],
    ],
];
