<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Controller;

use TYPO3\CMS\Fluid\View\FluidViewAdapter;
use TYPO3Fluid\Fluid\View\TemplatePaths;
use TYPO3Fluid\Fluid\View\TemplateView;
use TYPO3Fluid\Fluid\View\ViewInterface;

abstract class ActionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    protected function initializeView(ViewInterface $view): void
    {
        /** @phpstan-ignore-next-line instanceof.internalClass */
        assert($view instanceof TemplateView || $view instanceof FluidViewAdapter);

        /**
         * @var array{
         *     templateLayout?: string,
         *     templateLayouts?: array<string, array{
         *         templateRootPath?: string,
         *         partialRootPath?: string,
         *         layoutRootPath?: string
         *     }>,
         *  } $settings
         */
        $settings = $this->settings;

        /** @var string $templateLayoutKey */
        $templateLayoutKey = $settings['templateLayout'] ?? '';
        if ($templateLayoutKey === '') {
            return;
        }

        $templateLayout = $settings['templateLayouts'][$templateLayoutKey] ?? null;
        if (!is_array($templateLayout)) {
            return;
        }

        /**
         * @var TemplatePaths $templatePaths
         * @phpstan-ignore-next-line method.internalClass
         */
        $templatePaths = $view->getRenderingContext()->getTemplatePaths();

        if (isset($templateLayout['templateRootPath'])) {
            $templatePaths->setTemplateRootPaths(
                array_merge(
                    $templatePaths->getTemplateRootPaths(),
                    [$templateLayout['templateRootPath']],
                ),
            );
        }

        if (isset($templateLayout['partialRootPath'])) {
            $templatePaths->setPartialRootPaths(
                array_merge(
                    $templatePaths->getPartialRootPaths(),
                    [$templateLayout['partialRootPath']],
                ),
            );
        }

        if (!isset($templateLayout['layoutRootPath'])) {
            return;
        }

        $templatePaths->setLayoutRootPaths(
            //@phpstan-ignore-next-line argument.type
            array_merge(
                $templatePaths->getLayoutRootPaths(),
                [$templateLayout['layoutRootPath']],
            ),
        );
    }
}
