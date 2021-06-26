<?php


namespace App\Controller;


use App\Form\NewProductFormType;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends BaseController
{
    /**
     * @Route("/admin", name="app_admin_index")
     */
    public function index(): Response
    {
        return $this->render('admin/base.html.twig');
    }

    /**
     * @Route("/admin/category", name="app_admin_category")
     */
    public function category(): Response
    {
        return $this->render('admin/category/index.html.twig');
    }

    /**
     * @Route("/admin/product", name="app_admin_product")
     */
    public function product(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('admin/product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/admin/product/add", name="app_admin_product_add")
     */
    public function addProduct(Request $request, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(NewProductFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            dd($form->getData());
            //todo handle form
        }

        return $this->render('admin/product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/upload/test", name="upload_test")
     */
    public function temporaryUploadAction(Request $request)
    {
        dd($request->files->get('image'));
    }
}