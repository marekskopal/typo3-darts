<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Controller;

use MarekSkopal\MsDarts\Domain\Repository\MatchScoreRepository;
use MarekSkopal\MsDarts\Service\LeagueScoreService;
use MarekSkopal\MsDarts\Service\LoginCodeService;
use MarekSkopal\MsDarts\Service\TeamListService;
use Psr\Http\Message\ResponseInterface;

class LeagueController extends ActionController
{
    public function __construct(
        private readonly LeagueScoreService $leagueScoreService,
        private readonly TeamListService $teamListService,
        private readonly LoginCodeService $loginCodeService,
        private readonly MatchScoreRepository $matchScoreRepository,
    ) {
    }

    public function scoreAction(): ResponseInterface
    {
        $this->view->assign('score', $this->leagueScoreService->getStandings());

        return $this->htmlResponse();
    }

    public function teamListAction(): ResponseInterface
    {
        $this->view->assign('teamByGroups', $this->teamListService->getTeamList());
        $this->view->assign('permission', $this->loginCodeService->hasPermission());

        return $this->htmlResponse();
    }

    public function processCodeAction(string $code): ResponseInterface
    {
        $this->loginCodeService->authorize($code);

        return $this->redirect('teamList');
    }

    public function matchListAction(): ResponseInterface
    {
        $this->view->assign('matches', $this->matchScoreRepository->findAll());

        return $this->htmlResponse();
    }
}
