<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControlPanelControllerTest extends WebTestCase
{

    public function testShow()
    {

    }

    public function testStepByStep()
    {
        // Team user
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'team',
            'PHP_AUTH_PW' => '1234',
        ));

        $crawler = $client->request('GET', '/kontrollpanel');

        // Assert that the page response status code is 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that we have the correct page
        $this->assertEquals(1, $crawler->filter('h1:contains("Kontrollpanel")')->count());

        // Assert that we have the correct active step
        $this->assertEquals(1, $crawler->filter('div.step-active>h4:contains("")')->count());//TODO: Insert correct test step
        $this->assertEquals(1, $crawler->filter('div.step-active>p:contains("")')->count());//TODO: Insert correct step info

        // Assert that we have the correct inactive step info
        $this->assertEquals(1, $crawler->filter('div.step-box>p:contains("")')->count());//TODO: Insert correct step info
        $this->assertEquals(1, $crawler->filter('div.step-box>p:contains("")')->count());//TODO: Insert correct step info
        $this->assertEquals(1, $crawler->filter('div.step-box>p:contains("")')->count());//TODO: Insert correct step info
        $this->assertEquals(1, $crawler->filter('div.step-box>p:contains("")')->count());//TODO: Insert correct step info
    }
}