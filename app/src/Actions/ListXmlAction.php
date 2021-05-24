<?php


namespace App\Actions;

use App\Service\FileUploader;
use Symfony\Component\Finder\Finder;

class ListXmlAction
{
    private string $targetDirectory;

    /**
     * @var FileUploader
     */
    private FileUploader $uploader;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function __invoke()
    {
        $finder = new Finder();
        $finder->files()
            ->in($this->getTargetDirectory())
            ->name('*.xml')
            ->sortByChangedTime();

        // check if there are any search results
        $xmls = [];
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $xmls[] = [
                    'absoluteFilePath' => $absoluteFilePath,
                    'fileNameWithExtension' => $fileNameWithExtension
                ];
            }
        }
        return $xmls;
    }

    private function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}