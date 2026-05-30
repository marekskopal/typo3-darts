# Changelog

All notable changes to `ms_darts` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-05-30

Initial public release of the TYPO3 darts league extension.

### Added

- Domain models: `Group`, `Team`, `Player`, `MatchScore` with TCA, language labels (EN, CS, DE), and Extbase repositories.
- `LeagueController` exposing three actions wired to dedicated content element plugins:
  - **Darts - Score** — sorted standings per group, including overtime wins/losses and a tie-break on points and legs.
  - **Darts - Team list** — public team listing; player contact info is gated behind a per-team login code stored in the FE session.
  - **Darts - Match list** — chronological match list with date and result.
- Service layer that keeps controllers thin:
  - `LeagueScoreService` and `ScoreCalculator` compute standings into immutable `TeamScoreDto` / `GroupScoreDto` value objects.
  - `TeamListService` builds the grouped team list as `TeamListGroupDto`s.
  - `LoginCodeService` validates the login code and writes the permission flag through `SessionStorage`.
- Extension configuration (`ext_conf_template.txt`) exposes the scoring constants — overtime leg threshold, points for a regulation win, and points for the winner and loser of an overtime decider — with the historical hard-coded values as defaults.
- Switchable template layouts per content element via FlexForm, resolved by `ActionController`.
- Migration script `Migrations/Migration_From_MsAsl.sql` for upgrading from the legacy `ms_asl` extension (renames `tx_msasl_*` tables to `tx_msdarts_*` and rewires `tt_content.CType` plus `sys_file_reference.tablenames`).
- Unit test suite covering domain models, DTOs, and services (43 tests).
- CI workflow running PHPUnit on PHP 8.3/8.4 against TYPO3 ^13.4 and ^14.3.

### Requirements

- PHP 8.3 or newer.
- TYPO3 13.4 LTS or 14.3+.

[1.0.0]: https://github.com/marekskopal/typo3-darts/releases/tag/v1.0.0
