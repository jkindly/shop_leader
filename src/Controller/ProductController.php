<?php


namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{

    /**
     * @Route("/p/{slug}/{id}", name="app_product_index")
     */
    public function product()
    {
        return $this->render('shop/product/index.html.twig');
    }
}