<?php

declare(strict_types=1);

$llPath = 'LLL:EXT:ms_darts/Resources/Private/Language/locallang_db.xlf';
$table = 'tx_msdarts_domain_model_player';

return [
    'ctrl' => [
        'title' => $llPath . ':' . $table,
        'label' => 'last_name',
        'label_alt' => 'first_name',
        'label_alt_force' => true,
        'hideTable' => true,
        'sortby' => 'sorting',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'first_name, last_name, email',
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
        'team' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'first_name' => [
            'label' => $llPath . ':' . $table . '.first_name',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'last_name' => [
            'label' => $llPath . ':' . $table . '.last_name',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'phone' => [
            'label' => $llPath . ':' . $table . '.phone',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'email' => [
            'label' => $llPath . ':' . $table . '.email',
            'config' => [
                'type' => 'email',
                'size' => 40,
            ],
        ],
        'images' => [
            'label' => $llPath . ':' . $table . '.image',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'minitems' => 0,
                'allowed' => 'common-image-types',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    first_name, last_name, phone, email, images,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            ',
        ],
    ],
];
