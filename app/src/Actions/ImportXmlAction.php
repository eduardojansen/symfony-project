<?php


namespace App\Actions;

use App\Service\XmlImporter\PersonImporter;
use App\Service\XmlImporter\ShiporderImporter;

class ImportXmlAction
{
    private string $targetDirectory;

    /**
     * @var PersonImporter
     */
    private PersonImporter $personImporter;
    /**
     * @var ShiporderImporter
     */
    private ShiporderImporter $shiporderImporter;

    public function __construct(
        $targetDirectory,
        PersonImporter $personImporter,
        ShiporderImporter $shiporderImporter

    )
    {
        $this->personImporter = $personImporter;
        $this->shiporderImporter = $shiporderImporter;
        $this->targetDirectory = $targetDirectory;
    }

    public function __invoke($filename)
    {
        try {
            $filePath = $this->getTargetDirectory() . '/' . $filename;

            if (!file_exists($filePath)) throw new \Exception('Arquivo não encontrado');

            $xmldata = new \SimpleXMLElement($filePath, 0, true);

            switch ($xmldata->getName()) {
                case 'people':
                    $importer = $this->personImporter;
                    $message = 'pessoa(s) cadastrada(s)';
                    break;
                case 'shiporders':
                    $importer = $importer = $this->shiporderImporter;
                    $message = 'pedido(s) cadastrado(s)';
                    break;
                default:
                    throw new \Exception('Arquivo inválido');
            }

            $count = $importer->import($xmldata);

            return ['success', "Arquivo {$filename} importado com sucesso. {$count} {$message}"];

        } catch (\Exception $exception) {
            return ['error', "Erro ao importar arquivo ({$filename}): " . $exception->getMessage()];
        } finally {
            if (file_exists($filePath)) unlink($filePath);
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

}