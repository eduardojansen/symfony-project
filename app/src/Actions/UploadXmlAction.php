<?php


namespace App\Actions;

use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadXmlAction
{
    /**
     * @var FileUploader
     */
    private FileUploader $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function __invoke($request)
    {
        try {
            $files = $request->files->get('files');

            if (count($files) == 0) {
                throw new \Exception('Nenhum arquivo enviado');
            }

            /** @var UploadedFile $file */
            foreach ($files as $file) {
                if ($file->getMimeType() != "text/xml") {
                    $this->removeFile($file);
                    throw new \Exception('Apenas arquivos XML são aceitos');
                }
                $this->uploader->upload($file);
            }

            return ['success', 'Upload realizado com sucesso!'];
        } catch (\Exception $e) {
            return ['error', 'Erro ao realizar upload: ' . $e->getMessage()];
        }
    }

    /**
     * @param UploadedFile $file
     */
    private function removeFile(UploadedFile $file)
    {
        $realPath = $file->getRealPath();
        unset($realPath);
    }
}