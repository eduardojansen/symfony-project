<?php

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PeopleControllerTest extends BaseTest
{
    public function test_return_401_when_token_not_informed()
    {
        $client = static::createClient();
        $client->request('GET', '/people');

        self::assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function test_empty_list_people()
    {
        $client = static::createClient();

        $token = $this->login($client);

        $client->request('GET', '/people', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token",
        ]);

        $response = json_decode($client->getResponse()->getContent());

        $expected = (new stdClass());
        $expected->success = true;
        $expected->currentPage = 1;
        $expected->perPage = 5;
        $expected->data = [];

        self::assertEquals($expected, $response);

        self::assertTrue($response->success);
    }

    public function test_populated_list_people()
    {
        $client = $this->importXml('people.xml', 'temp-people.xml');

        $token = $this->login($client);

        $client->request('GET', '/people', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token",
        ]);

        $response = json_decode($client->getResponse()->getContent());

        $person = new stdClass();
        $person->id = 1;
        $person->code = 1;
        $person->name = 'Name 1';
        $person->phones = [
            '2345678', '1234567'
        ];

        self::assertEquals($person, $response->data[0]);

        self::assertIsArray($response->data);

        self::assertCount(3, $response->data);

        self::assertTrue($response->success);

        self::assertEquals(200, $client->getResponse()->getStatusCode());

    }

}