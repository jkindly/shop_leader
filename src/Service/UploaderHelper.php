<?php


namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    const PRODUCT_IMAGE = 'product_image';
    const PRODUCT_REFERENCE = 'product_reference';

    private $filesystem;
    private $requestStackContext;
    private $logger;
    private $publicAssetBaseUrl;
    private $privateFilesystem;

    public function __construct(FilesystemOperator $publicUploadFileSystem, FilesystemOperator
    $privateUploadFilesystem, RequestStackContext $requestStackContext, LoggerInterface $logger, string $uploadedAssetsBaseUrl)
    {
        $this->filesystem = $publicUploadFileSystem;
        $this->privateFilesystem = $privateUploadFilesystem;
        $this->requestStackContext = $requestStackContext;
        $this->logger = $logger;
        $this->publicAssetBaseUrl = $uploadedAssetsBaseUrl;
    }

    public function uploadProductImage(File $file, ?string $existingFilename): string
    {
        $newFileName = $this->uploadFile($file, self::PRODUCT_IMAGE, true);

        if ($existingFilename) {
            try {
                $this->filesystem->delete(self::PRODUCT_IMAGE . '/' . $existingFilename);
            } catch (FileNotFoundException $e) {
                $this->logger->alert(sprintf('Old uploaded file "%s" was missing when trying to delete',
                    $existingFilename));
            }
        }

        return $newFileName;
    }

    public function uploadProductReference(File $file): string
    {
        return $this->uploadFile($file, self::PRODUCT_REFERENCE, false);
    }
    
    public function getPublicPath(string $path): string
    {
        return $this->requestStackContext
            ->getBasePath().$this->publicAssetBaseUrl.'/'.$path;
    }

    private function uploadFile(File $file, string $directory, bool $isPublic): string
    {
        if ($file instanceof UploadedFile) {
            $originalFileName = $file->getClientOriginalName();
        }
        else {
            $originalFileName = $file->getFilename();
        }

        $newFileName = Urlizer::urlize(pathinfo($originalFileName, PATHINFO_FILENAME)).'-'.uniqid().'.'.$file->guessExtension();

        $filesystem = $isPublic ? $this->filesystem : $this->privateFilesystem;

        $stream = fopen($file->getPathname(), 'r');

        $filesystem->writeStream(
            $directory.'/'.$newFileName,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $newFileName;
    }
}