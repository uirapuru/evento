<?php
namespace AppBundle\Tests;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Faker\Generator;
use FOS\UserBundle\Model\UserInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Nelmio\Alice\Instances\Processor\Methods\Faker;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Client;

/**
 * Class BaseFunctionalTestCase
 * @package AppBundle\Tests\Functional
 */
abstract class BaseFunctionalTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var AbstractFixture
     */
    protected $fixtures;

    /** @var  Generator */
    protected $faker;

    /**
     * @param UserInterface $user
     * @return void
     */
    public function login(UserInterface $user)
    {
        parent::loginAs($user, 'main');

        $this->client = static::makeClient();
        $this->client->followRedirects(true);
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->faker = $this->getContainer()->get("hautelook_alice.faker");

        error_reporting(E_ALL & ~E_USER_DEPRECATED);

        $this->client = static::makeClient();
        $this->client->followRedirects(true);
    }

    protected function getContent()
    {
        return $this->client->getResponse()->getContent();
    }

    protected function getStatusCode()
    {
        return $this->client->getResponse()->getStatusCode();
    }
}