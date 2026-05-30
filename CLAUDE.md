# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Build & Quality Commands

```bash
# Install dependencies
composer install

# Static analysis (level max)
./vendor/bin/phpstan analyse

# Code style check (PSR-12 + Slevomat)
./vendor/bin/phpcs

# Auto-fix code style
./vendor/bin/phpcbf

# Run tests
./vendor/bin/phpunit
```

## Architecture

This is a TYPO3 CMS extension (`ms_darts`) that provides a darts league with groups, teams, players, and match scores.

**Namespace:** `MarekSkopal\MsDarts`

### Key Components

- **LeagueController** (`Classes/Controller/`) - Extbase controller with three actions:
  - `scoreAction()` — renders the score table per group, ranked by score / points / legs
  - `teamListAction()` — renders teams grouped, hides player contact info behind a login code
  - `matchListAction()` — renders all matches
  - `processCodeAction()` — accepts a team login code and stores `permission` in the session
- **ActionController** (`Classes/Controller/`) - Abstract base that wires up FlexForm template-layout overrides
- **Group / Team / Player / MatchScore** (`Classes/Domain/Model/`) - Extbase domain models
- **GroupRepository / TeamRepository / MatchScoreRepository** (`Classes/Domain/Repository/`)
- **SessionStorage** (`Classes/Utility/`) - FE session wrapper used for team login-code gating

### Data Flow

1. `scoreAction()` loads groups (or treats teams as one synthetic group if no groups exist), looks up played matches, and accumulates wins / losses / points / legs / score per team
2. Standings are sorted by score → pointsOwn → legsOwn, with overtime (`points1 == 9` or `points2 == 9`) crediting 2/1 points
3. `teamListAction()` lists teams per group. If the FE user has previously submitted a valid login code, contact info and player details are revealed
4. `matchListAction()` lists all matches with date and result

### Template Structure

- `Layouts/Default.html` — wraps content in `.msdarts-wrapper`
- `Templates/League/Score.html` — score table per group
- `Templates/League/TeamList.html` — teams per group + login-code modal
- `Templates/League/MatchList.html` — flat match list

### Configuration

TypoScript Sets (TYPO3 13+) are in `Configuration/Sets/MsDarts/`. Set the `plugin.tx_msdarts.persistence.storagePid` constant to the page where records live.

## Requirements

- PHP 8.3+
- TYPO3 13.4+ or 14.3+

## Code Style

- Strict types enabled in all files
- **No constructor property promotion in Extbase domain models** — TYPO3 Extbase hydrates models by setting protected properties directly (bypassing the constructor), so properties must be declared classically with default values
- PHPStan level `max` with bleeding edge; `method.internalClass` ignored where needed (e.g. `getUid()` on Extbase entities)
- PSR-12 with Slevomat Coding Standard

## Migration from `ms_asl`

`Migrations/Migration_From_MsAsl.sql` renames the legacy `tx_msasl_*` tables to `tx_msdarts_*` and rewires `tt_content.CType` values and `sys_file_reference.tablenames`. Run it once when upgrading from the old `ms_asl` extension, then run the Install Tool database analyser.
