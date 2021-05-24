<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportXMLControllerTest extends BaseTest
{
    public function tearDown(): void
    {
        $this->removeTestFiles();
    }

    public function test_index_page_is_loaded()
    {
        $client = self::createClient();

        $client->request(
            'GET',
            '/index',
        );

        $this->assertSelectorTextContains('h2', 'Importar XML');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function test_failed_file_upload_when_no_file_sent()
    {
        $client = self::createClient();

        $client->request(
            'POST',
            '/upload',
            [],
            ['files' => []]
        );

        self::assertEquals(302, $client->getResponse()->getStatusCode());


        $client->request('GET', '/index');

        $this->assertSelectorTextContains('div', 'Erro ao realizar upload: Nenhum arquivo enviado');
    }

    public function test_upload_no_xml_file_should_return_an_error()
    {
        $client = self::createClient();

        $filePath = './tests/files_to_test/noxmlfile.json';
        copy($filePath, $copyfile = './tests/files_to_test/temp-noxmlfile.json');

        $upload = new UploadedFile($copyfile, 'temp-noxmlfile.json');

        $client->request(
            'POST',
            '/upload',
            [],
            ['files' => [$upload]]
        );

        $client->request('GET', '/index');

        $this->assertSelectorTextContains('div', 'Erro ao realizar upload: Apenas arquivos XML são aceitos');
    }

    public function test_successful_file_upload()
    {
        $client = self::createClient();

        $filePath = './tests/files_to_test/people.xml';
        copy($filePath, $copyfile = './tests/files_to_test/temp-people.xml');
        $upload = new UploadedFile($copyfile, 'people.xml');

        $client->request(
            'POST',
            '/upload',
            [],
            ['files' => [$upload]]
        );

        $client->request('GET', '/index');

        $this->assertSelectorTextContains('div', 'Upload realizado com sucesso!');
        $this->assertSelectorTextContains('h4', 'Arquivos disponíveis para importação');

    }

    public function test_failed_file_import_when_file_does_not_exist()
    {
        $client = self::createClient();

        $client->request(
            'GET',
            '/import/notfound.xml',
        );

        $client->request('GET', '/index');

        $this->assertSelectorTextContains('div', 'Erro ao importar arquivo (notfound.xml): Arquivo não encontrado');
    }

    public function test_import_xml_people_successfully()
    {
        $client = $this->importXml('people.xml', 'temp-people.xml');

        $client->request('GET', '/index');

        $this->assertSelectorTextContains('div', 'Arquivo temp-people.xml importado com sucesso. 3 pessoa(s) cadastrada(s)');
        $this->assertTrue(!file_exists(self::TEST_FILE_PATH . 'temp-people.xml'));

    }

    public function test_import_should_fail_when_any_person_required_fields_are_missing()
    {
        $client = $this->importXml('people-error.xml', 'temp-people.xml');

        $client->request('GET', '/index');

        $this->assertSelectorTextContains('div', 'Erro ao importar arquivo (temp-people.xml): Campo id é obrigatório');
        $this->assertTrue(!file_exists(self::TEST_FILE_PATH . 'temp-people.xml'));
    }

    public function test_import_xml_shiporders_successfully()
    {
        $this->importXml('people.xml', 'temp-people.xml');

        $client = $this->importXml('shiporders.xml', 'temp-shiporders.xml');

        $client->request('GET', '/index');

        $this->assertSelectorTextContains('div', 'Arquivo temp-shiporders.xml importado com sucesso. 1 pedido(s) cadastrado(s)');
        $this->assertTrue(!file_exists(self::TEST_FILE_PATH . 'temp-shiporders.xml'));
    }

    public function test_import_should_fail_when_any_shiporder_required_fields_are_missing()
    {
        $client = $this->importXml('shiporders-error.xml', 'temp-shiporders.xml');

        $client->request('GET', '/index');

        $this->assertSelectorTextContains('div', 'Erro ao importar arquivo (temp-shiporders.xml): Pessoa com id 3 não está cadastrada no sistema');
        $this->assertTrue(!file_exists(self::TEST_FILE_PATH . 'temp-shiporders.xml'));
    }

    private function removeTestFiles(): void
    {
        $dir = './tests/files_to_test/';
        $filesToRemove = array_diff(
            scandir($dir),
            ['.', '..', 'people.xml', 'shiporders.xml', 'people-error.xml', 'shiporders-error.xml', 'noxmlfile.json', '']
        );
        if (count($filesToRemove) > 0) {
            array_map(function ($file) use ($dir) {
                unlink($dir . $file);
            }, $filesToRemove);
        }
    }
}