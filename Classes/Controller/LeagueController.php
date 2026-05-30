<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Controller;

use MarekSkopal\MsDarts\Domain\Model\MatchScore;
use MarekSkopal\MsDarts\Domain\Repository\GroupRepository;
use MarekSkopal\MsDarts\Domain\Repository\MatchScoreRepository;
use MarekSkopal\MsDarts\Domain\Repository\TeamRepository;
use MarekSkopal\MsDarts\Utility\SessionStorage;
use Psr\Http\Message\ResponseInterface;

class LeagueController extends ActionController
{
    public function __construct(
        private readonly MatchScoreRepository $matchScoreRepository,
        private readonly TeamRepository $teamRepository,
        private readonly GroupRepository $groupRepository,
        private readonly SessionStorage $sessionStorage,
    ) {
    }

    public function scoreAction(): ResponseInterface
    {
        $score = [];

        $groups = $this->groupRepository->findAll()->toArray();

        if ($groups === []) {
            $score[0] = [
                'title' => '',
                'actual' => true,
                'pid' => 0,
                'score' => [],
            ];

            foreach ($this->teamRepository->findByGroup(null) as $team) {
                $score[0]['score'][$team->getUid()] = $this->emptyTeamScore($team);
            }
        } else {
            foreach ($groups as $group) {
                $groupUid = $group->getUid();
                $score[$groupUid] = [
                    'title' => $group->getTitle(),
                    'actual' => $group->getActual(),
                    'pid' => $group->getPid(),
                    'score' => [],
                ];

                foreach ($this->teamRepository->findByGroup($groupUid) as $team) {
                    $score[$groupUid]['score'][$team->getUid()] = $this->emptyTeamScore($team);
                }
            }
        }

        foreach ($score as $groupUid => $group) {
            if ($groupUid !== 0) {
                $matches = $this->matchScoreRepository->findPlayedMatchesForGroup($groupUid, $group['pid']);
            } else {
                $matches = $this->matchScoreRepository->findPlayedMatchesForTeams(array_keys($group['score']));
            }

            foreach ($matches as $match) {
                $this->applyMatchToScore($score[$groupUid]['score'], $match);
            }
        }

        ksort($score);
        usort($score, static function (array $groupA, array $groupB): int {
            if ($groupA['actual'] && !$groupB['actual']) {
                return -1;
            }
            if (!$groupA['actual'] && $groupB['actual']) {
                return 1;
            }
            return 0;
        });

        foreach ($score as &$group) {
            usort($group['score'], static function (array $a, array $b): int {
                if ($a['score'] !== $b['score']) {
                    return $a['score'] > $b['score'] ? -1 : 1;
                }
                if ($a['pointsOwn'] !== $b['pointsOwn']) {
                    return $a['pointsOwn'] > $b['pointsOwn'] ? -1 : 1;
                }
                if ($a['legsOwn'] === $b['legsOwn']) {
                    return 0;
                }
                return $a['legsOwn'] > $b['legsOwn'] ? -1 : 1;
            });
        }
        unset($group);

        $this->view->assign('score', $score);

        return $this->htmlResponse();
    }

    public function teamListAction(): ResponseInterface
    {
        $groups = $this->groupRepository->findAll()->toArray();

        $teamByGroups = [];

        if ($groups === []) {
            $teamByGroups[0]['title'] = '';
            $teamByGroups[0]['teams'] = $this->teamRepository->findByGroup(null);
        } else {
            foreach ($groups as $group) {
                $teamByGroups[$group->getUid()]['title'] = $group->getTitle();
                $teamByGroups[$group->getUid()]['teams'] = $this->teamRepository->findByGroup($group->getUid());
            }
        }

        ksort($teamByGroups);

        $this->view->assign('teamByGroups', $teamByGroups);
        $this->view->assign('permission', $this->sessionStorage->has('permission'));

        return $this->htmlResponse();
    }

    public function processCodeAction(string $code): ResponseInterface
    {
        $team = $this->teamRepository->findByLoginCode($code)->getFirst();
        if ($team !== null) {
            $this->sessionStorage->set('permission', true);
        }

        return $this->redirect('teamList');
    }

    public function matchListAction(): ResponseInterface
    {
        $matches = $this->matchScoreRepository->findAll();

        $this->view->assign('matches', $matches);

        return $this->htmlResponse();
    }

    /**
     * @return array{team: \MarekSkopal\MsDarts\Domain\Model\Team, teamId: int, matches: int, legsOwn: int, legsOponent: int, pointsOwn: int, pointsOponent: int, win: int, lose: int, winExt: int, loseExt: int, score: int}
     */
    private function emptyTeamScore(\MarekSkopal\MsDarts\Domain\Model\Team $team): array
    {
        return [
            'team' => $team,
            'teamId' => $team->getUid(),
            'matches' => 0,
            'legsOwn' => 0,
            'legsOponent' => 0,
            'pointsOwn' => 0,
            'pointsOponent' => 0,
            'win' => 0,
            'lose' => 0,
            'winExt' => 0,
            'loseExt' => 0,
            'score' => 0,
        ];
    }

    /** @param array<int, array<string, mixed>> $teamScores */
    private function applyMatchToScore(array &$teamScores, MatchScore $match): void
    {
        $team1Uid = $match->getTeam1()->getUid();
        $team2Uid = $match->getTeam2()->getUid();

        if (!isset($teamScores[$team1Uid]) || !isset($teamScores[$team2Uid])) {
            return;
        }

        $teamScores[$team1Uid]['matches']++;
        $teamScores[$team2Uid]['matches']++;

        $teamScores[$team1Uid]['legsOwn'] += $match->getLeg1();
        $teamScores[$team1Uid]['legsOponent'] += $match->getLeg2();
        $teamScores[$team2Uid]['legsOwn'] += $match->getLeg2();
        $teamScores[$team2Uid]['legsOponent'] += $match->getLeg1();

        $teamScores[$team1Uid]['pointsOwn'] += $match->getPoints1();
        $teamScores[$team1Uid]['pointsOponent'] += $match->getPoints2();
        $teamScores[$team2Uid]['pointsOwn'] += $match->getPoints2();
        $teamScores[$team2Uid]['pointsOponent'] += $match->getPoints1();

        if ($match->getPoints1() === 9) {
            $teamScores[$team1Uid]['loseExt']++;
            $teamScores[$team2Uid]['winExt']++;
            if ($match->getScoreManual()) {
                $teamScores[$team1Uid]['score'] += $match->getScore1();
                $teamScores[$team2Uid]['score'] += $match->getScore2();
            } else {
                $teamScores[$team1Uid]['score'] += 1;
                $teamScores[$team2Uid]['score'] += 2;
            }
        } elseif ($match->getPoints2() === 9) {
            $teamScores[$team2Uid]['loseExt']++;
            $teamScores[$team1Uid]['winExt']++;
            if ($match->getScoreManual()) {
                $teamScores[$team1Uid]['score'] += $match->getScore1();
                $teamScores[$team2Uid]['score'] += $match->getScore2();
            } else {
                $teamScores[$team2Uid]['score'] += 1;
                $teamScores[$team1Uid]['score'] += 2;
            }
        } elseif ($match->getPoints1() > $match->getPoints2()) {
            $teamScores[$team1Uid]['win']++;
            $teamScores[$team2Uid]['lose']++;
            if ($match->getScoreManual()) {
                $teamScores[$team1Uid]['score'] += $match->getScore1();
                $teamScores[$team2Uid]['score'] += $match->getScore2();
            } else {
                $teamScores[$team1Uid]['score'] += 3;
            }
        } else {
            $teamScores[$team2Uid]['win']++;
            $teamScores[$team1Uid]['lose']++;
            if ($match->getScoreManual()) {
                $teamScores[$team1Uid]['score'] += $match->getScore1();
                $teamScores[$team2Uid]['score'] += $match->getScore2();
            } else {
                $teamScores[$team2Uid]['score'] += 3;
            }
        }
    }
}
