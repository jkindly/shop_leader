<?php


namespace App\Controller;


use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseController
{
    /**
     * @Route("/c/{slug}/{id}", name="app_category_index")
     */
    public function category(Category $category): Response
    {
        $metaTitle = $category->getMetaTitle() ? $category->getMetaTitle() : $category->getTitle();

        return $this->render("shop/category/index.html.twig", [
            'category' => $category,
            'meta_title' => $metaTitle,
            'meta_description' => $category->getMetaDescription(),
        ]);
    }
}