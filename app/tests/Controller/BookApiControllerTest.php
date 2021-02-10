<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testShowPost()
    {
        $client = static::createClient();
        $client->request('GET', '/book/search/Three sisters');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = ($client->getResponse()->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode($content),
            json_encode('[{"id":4,"Name":"Three sisters|\u0422\u0440\u0438 \u0441\u0435\u0441\u0442\u0440\u044b","Author":"\u0410\u043d\u0442\u043e\u043d \u0427\u0435\u0445\u043e\u0432"}]')
        );
    }
}