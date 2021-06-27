<?php


namespace App\Controller;


use App\Entity\Product;
use App\Form\NewProductFormType;
use App\Repository\ProductRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public function addProduct(Request $request, EntityManagerInterface $em, UploaderHelper $uploaderHelper): Response
    {
        $form = $this->createForm(NewProductFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Product $product */
            $product = $form->getData();

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile)
            {
                $newFileName = $uploaderHelper->uploadProductImage($uploadedFile, $product->getImageFilename());
                $product->setImageFilename($newFileName);
            }

            $product->setSlug(Urlizer::urlize($product->getTitle()));

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produkt został dodany.');

            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin/product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/{id<\d+>}/edit", name="app_admin_product_edit")
     */
    public function editProduct(Product $product, Request $request, EntityManagerInterface $em, UploaderHelper $uploaderHelper):
    Response
    {
        $form = $this->createForm(NewProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile)
            {
                $newFileName = $uploaderHelper->uploadProductImage($uploadedFile, $product->getImageFilename());
                $product->setImageFilename($newFileName);
            }

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produkt został edytowany.');

            return $this->redirectToRoute('app_admin_product_edit', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/admin/upload/test", name="upload_test")
     */
    public function temporaryUploadAction(Request $request)
    {

    }
}