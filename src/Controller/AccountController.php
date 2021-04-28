<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends BaseController
{
    /**
     * @Route("/account", "app_account_index")
     */
    public function index(LoggerInterface $logger): Response
    {
        $logger->debug('Checking account page for ' . $this->getUser()->getLogin());

        return $this->render('account/index.html.twig');
    }
}
