<?php
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServerControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();

        $client->request('GET', '/server');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

    }

    //check if the response contains the expected data
    public function testIndexActionContainsExpectedData()
    {
        $client = static::createClient();

        $client->request('GET', '/server');

        $this->assertStringContainsString('servers', $client->getResponse()->getContent());
        $this->assertStringContainsString('locations', $client->getResponse()->getContent());
        $this->assertStringContainsString('ramOptions', $client->getResponse()->getContent());
    }
}
