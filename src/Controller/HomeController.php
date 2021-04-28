<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="app_index")
     */
    public function homepage(): Response
    {
        return $this->render('homepage/index.html.twig');
    }
}