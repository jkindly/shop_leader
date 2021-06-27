<?php


namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="app_index")
     */
    public function homepage(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('homepage/index.html.twig', [
            'products' => $products,
        ]);
    }
}