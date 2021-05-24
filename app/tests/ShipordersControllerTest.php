<?php

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShipordersControllerTest extends BaseTest
{
    public function test_return_401_when_token_not_informed()
    {
        $client = static::createClient();
        $client->request('GET', '/people');

        self::assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function test_empty_list_shiporders()
    {
        $client = static::createClient();

        $token = $this->login($client);

        $client->request('GET', '/shiporders', [], [], [
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

    public function test_populated_list_shiporders()
    {
        $this->importXml('people.xml', 'temp-people.xml');

        $client = $this->importXml('shiporders.xml', 'temp-shiporders.xml');

        $token = $this->login($client);

        $client->request('GET', '/shiporders', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token",
        ]);

        $response = json_decode($client->getResponse()->getContent());

        $expectedJson = <<<EOJ
{
    "success":true,
   "currentPage":1,
   "perPage":5,
   "data":[
      {
          "id":1,
         "code":3,
         "person":{
          "id":3,
            "code":3,
            "name":"Name 3",
            "phones":[
              "7777777",
              "8888888"
          ]
         },
         "shipto":{
          "name":"Name 9",
            "address":"Address 9",
            "city":"City 9",
            "country":"Country 9"
         },
         "items":[
            {
                "id":1,
               "title":"Title 9",
               "note":"Note 3",
               "quantity":5,
               "prince":1.12
            },
            {
                "id":2,
               "title":"Title",
               "note":"Note 4",
               "quantity":2,
               "prince":77.12
            }
         ]
      }
   ]
}
EOJ;

        self::assertEquals(json_decode($expectedJson), $response);

        self::assertIsArray($response->data);

        self::assertCount(1, $response->data);

        self::assertTrue($response->success);

        self::assertEquals(200, $client->getResponse()->getStatusCode());

    }

}