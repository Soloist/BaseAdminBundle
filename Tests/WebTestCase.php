<?php

namespace Soloist\Bundle\CoreBundle\Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Soloist\Bundle\BaseAdminBundle\Tests\AppKernel;


class WebTestCase extends BaseWebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    /**
     * @var bool
     */
    protected static $schemaSetted = false;

    protected function setUp()
    {
        $this->client = self::createClient();

        if (!self::$schemaSetted) {
            $em = $this->client->getContainer()->get('doctrine')->getManager();
            $st = new SchemaTool($em);
            $classes = $em->getMetadataFactory()->getAllMetadata();
            $st->dropSchema($classes);
            $st->createSchema($classes);

            self::$schemaSetted = true;
        }

        parent::setUp();
    }

    protected static function createKernel(array $options = array())
    {
        return new AppKernel('test', true);
    }
}
