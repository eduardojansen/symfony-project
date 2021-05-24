<?php


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseTest extends WebTestCase
{
    const TEST_FILE_PATH = './tests/files_to_test/';

    protected function login(KernelBrowser $client): string
    {
        $client->request(
            'POST',
            '/login',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode([
                'username' => 'admin', 'password' => 'secret'
            ])
        );

        return json_decode($client->getResponse()->getContent())->access_token;
    }

    protected function importXml($file, $tempfile = "temp.xml")
    {
        $filePath = "./tests/files_to_test/$file";
        copy($filePath, $copyfile = "./tests/files_to_test/$tempfile");

        $client = static::createClient();
        $client->request('GET', "/import/$tempfile");
        return $client;
    }

}