<?php

namespace App\Tests\Controller;

use App\Controller\DefaultController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        $this->assertSame(
            'Latest movies:',
            $crawler->filter('h2')->innerText()
        );
        $this->assertCount(
            1,
            $crawler->filter('.card')
        );
    }
}
