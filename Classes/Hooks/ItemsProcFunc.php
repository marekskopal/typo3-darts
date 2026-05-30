<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ItemsProcFunc
{
    /**
     * @param array{
     *     items: array<array{0: string, 1: string}>,
     *     flexParentDatabaseRow: array{colPos?: int, pid: int|string},
     * } $config
     */
    public function templateLayout(array &$config): void
    {
        $currentColPos = $config['flexParentDatabaseRow']['colPos'] ?? null;
        if ($currentColPos === null) {
            return;
        }

        $pageId = $this->getPageId((int) $config['flexParentDatabaseRow']['pid']);
        if ($pageId <= 0) {
            return;
        }

        $templateLayouts = $this->getAvailableTemplateLayouts($pageId);
        foreach ($templateLayouts as $layout) {
            $additionalLayout = [
                htmlspecialchars($this->getLanguageService()->sL($layout[0])),
                $layout[1],
            ];
            array_push($config['items'], $additionalLayout);
        }
    }

    /** @return list<array{0: string, 1: string}> */
    private function getAvailableTemplateLayouts(int $pageUid): array
    {
        $templateLayouts = $this->getTemplateLayoutsFromGlobals();

        foreach ($this->getTemplateLayoutsFromTsConfig($pageUid) as $templateKey => $title) {
            if (str_starts_with($title, '--div--')) {
                $optGroupParts = GeneralUtility::trimExplode(',', $title, true, 2);
                $title = $optGroupParts[1];
                $templateKey = $optGroupParts[0];
            }
            $templateLayouts[] = [$title, $templateKey];
        }

        return $templateLayouts;
    }

    /** @return array<string, string> */
    private function getTemplateLayoutsFromTsConfig(int $pageUid): array
    {
        /** @var array{"tx_msdarts."?: array{"templateLayouts."?: array<string, string>}} $pagesTsConfig */
        $pagesTsConfig = BackendUtility::getPagesTSconfig($pageUid);

        return $pagesTsConfig['tx_msdarts.']['templateLayouts.'] ?? [];
    }

    /** @return list<array{0: string, 1: string}> */
    private function getTemplateLayoutsFromGlobals(): array
    {
        /**
         * @var list<array{0: string, 1: string}> $templateLayouts
         * @phpstan-ignore-next-line offsetAccess.nonOffsetAccessible
         */
        $templateLayouts = $GLOBALS['TYPO3_CONF_VARS']['EXT']['ms_darts']['templateLayouts'] ?? [];

        return $templateLayouts;
    }

    private function getPageId(int $pid): int
    {
        if ($pid > 0) {
            return $pid;
        }

        /** @var array{uid: int, pid: int} $row */
        $row = BackendUtility::getRecord('tt_content', abs($pid), 'uid,pid');
        return $row['pid'];
    }

    private function getLanguageService(): LanguageService
    {
        // @phpstan-ignore-next-line return.type
        return $GLOBALS['LANG'];
    }
}
