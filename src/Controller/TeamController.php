<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends BaseController
{
    /**
     * @Route("/team", name="app_team_index")
     */
    public function index(): Response
    {
        return $this->render('team/index.html.twig');
    }
}