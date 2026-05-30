# Darts league for TYPO3 CMS

A darts league plugin for TYPO3 — manage groups, teams, players, and match scores in the backend and render a score table, team list, and match list in the frontend as content elements.

## Features

- **Groups** with current-season flag (actual groups are listed first)
- **Teams** with a many-to-many relation to groups, a place, an address, a playing day, and a per-team login code
- **Players** with name, phone, email, and a profile photo
- **Match scores** with round, date, two teams, legs, points, and an optional manual score override
- **Score table** — automatically computed per group with regulation wins (3 pts), overtime wins (2 pts), overtime losses (1 pt); ties are broken by points then by legs
- **Team list** — public team listing; player contact info is gated behind a per-team **login code** so only teammates who know it can see addresses and phone numbers
- **Match list** — all matches with date and result
- **Three content element plugins** — *Darts - Score*, *Darts - Team list*, *Darts - Match list*
- **Template layouts** — switchable per content element via FlexForm, configured through TSconfig or PHP globals
- Multilingual labels (EN, CS, DE out of the box)
- TYPO3 13 and 14 compatible

## Requirements

- PHP 8.3+
- TYPO3 13.4 or 14.x

## Installation

```bash
composer require marekskopal/typo3-darts
```

After installation, run the database analyser in the TYPO3 Install Tool to create the required tables.

## Setup

Include the TypoScript Set **Darts** in your site package or via the site configuration sets, then set the storage PID:

```typoscript
plugin.tx_msdarts.persistence.storagePid = 42
```

## Content Elements

The extension registers three CType plugins. Drop them on the page that should display the data:

| Plugin | Action | What it shows |
|--------|--------|---------------|
| **Darts - Score** | `scoreAction` | Sorted score table per group |
| **Darts - Team list** | `teamListAction` | Teams per group + login-code modal for contact info |
| **Darts - Match list** | `matchListAction` | All matches with results |

## Backend Setup

Create records on the storage page:

1. **Groups** — give each group a title and check **Actual** for the current season
2. **Teams** — set the title, assign one or more groups, set the playing day, and (optionally) a **Login code** that lets team members reveal contact info on the public team list
3. **Players** — inline under each team, with optional photo
4. **Match scores** — round, date, two teams, legs, points. A final score of `9` for one side is treated as **overtime**

## Template Layouts

Register custom template layouts in Page TSconfig:

```typoscript
tx_msdarts.templateLayouts {
    my_layout = My custom layout
}
```

Or in PHP (e.g. `ext_localconf.php`):

```php
$GLOBALS['TYPO3_CONF_VARS']['EXT']['ms_darts']['templateLayouts'][] = ['My layout label', 'my_layout'];
```

Then configure the corresponding template paths in TypoScript:

```typoscript
plugin.tx_msdarts.settings.templateLayouts {
    my_layout {
        templateRootPath = EXT:your_extension/Resources/Private/Templates/MsDarts/MyLayout/
        partialRootPath  = EXT:your_extension/Resources/Private/Partials/MsDarts/MyLayout/
        layoutRootPath   = EXT:your_extension/Resources/Private/Layouts/MsDarts/MyLayout/
    }
}
```

## Customization

### Templates

Override templates by setting custom paths in TypoScript:

```typoscript
plugin.tx_msdarts.view.templateRootPaths.10 = EXT:your_extension/Resources/Private/Templates/MsDarts/
plugin.tx_msdarts.view.partialRootPaths.10  = EXT:your_extension/Resources/Private/Partials/MsDarts/
plugin.tx_msdarts.view.layoutRootPaths.10   = EXT:your_extension/Resources/Private/Layouts/MsDarts/
```

### Styling

The templates render Bootstrap-style classes (`.table`, `.modal`, etc.). Wrap your own CSS around them or override the templates to drop the framework.

## Migration from `ms_asl`

If you are upgrading from the predecessor extension `ms_asl`, run `Migrations/Migration_From_MsAsl.sql` once. It renames every `tx_msasl_*` table to its `tx_msdarts_*` counterpart, rewrites `tt_content.CType` for the three plugin signatures, and updates `sys_file_reference.tablenames` for player photos. After running it, execute the Install Tool database analyser to add the new columns (categories, language, etc.).

## License

GPL-2.0-or-later
