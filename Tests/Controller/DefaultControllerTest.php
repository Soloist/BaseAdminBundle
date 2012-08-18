<?php

namespace Soloist\Bundle\BaseAdminBundle\Tests\Controller;

use Soloist\Bundle\BaseAdminBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Tableau de bord")')->count() > 0);
    }
}
