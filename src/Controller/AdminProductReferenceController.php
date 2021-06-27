<?php


namespace App\Controller;


use App\Entity\Product;
use App\Entity\ProductReference;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminProductReferenceController extends BaseController
{
    /**
     * @Route("/admin/product/{id}/references", name="app_admin_product_add_reference", methods={"POST"})
     */
    public function uploadProductReference(Product $product, Request $request, UploaderHelper $uploaderHelper,
                                           EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('reference');

        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank([
                   'message' => 'Proszę wybrać plik',
                ]),
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/*',
                    ],
                ])
            ]
        );

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];
            $this->addFlash('error', $violation->getMessage());

            return $this->redirectToRoute('app_admin_product_edit', [
                'id' => $product->getId(),
            ]);
        }

        $filename = $uploaderHelper->uploadProductReference($uploadedFile);

        $productReference = new ProductReference($product);
        $productReference->setFilename($filename);
        $productReference->setOriginalFilename($uploadedFile->getClientOriginalName() ?? $filename);
        $productReference->setMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');

        $em->persist($productReference);
        $em->flush();

        return $this->redirectToRoute('app_admin_product_edit', [
            'id' => $product->getId(),
        ]);
    }
}