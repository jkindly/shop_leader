<?php


namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

class LoginController extends BaseController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login()
    {
        return $this->render('login/login_template.html.twig');
    }
}