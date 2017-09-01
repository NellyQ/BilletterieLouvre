<?php

//tests/AppBundle/Controller/CommandeTest.php

namespace Tests\AppBundle\Controller;

use Louvre\BilletterieBundle\Entity\Commande;
use PHPUnit\Framework\TestCase;

class CommandeTest extends TestCase
{
    public function testGetCommandeNbBillet()
    {
        $commande = new Commande();
        $commande ->setCommandeNbBillet(2);
        $result = $commande->getCommandeNbBillet();
        
        // assert that your commande added the mail correctly!
        $this->assertEquals(2, $result);
    }
    
    public function testGetCommandeCode()
    {
        $commande = new Commande();
        $commande ->setCommandeCode('15f1d5f1d');
        $result = $commande->getCommandeCode();
        
        // assert that your commande added the mail correctly!
        $this->assertEquals('15f1d5f1d', $result);
    }
}