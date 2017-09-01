<?php

//tests/AppBundle/Controller/BilletterieControllerTest.php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BilletterieControllerTest extends WebTestCase
{
    public function testCommande()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/fr');
        
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Billetterie")')->count()
        );
    }
    
    public function testDetail()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/fr/details');
        
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Prix")')->count()
        );
    }
    
    public function testconfirmation()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/fr/confirmation');
        
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Merci")')->count()
        );
    }
}