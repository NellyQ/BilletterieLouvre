<?php

//tests/AppBundle/Controller/CommandeTest.php

namespace Tests\AppBundle\Controller;

use Louvre\BilletterieBundle\Entity\Detail;
use PHPUnit\Framework\TestCase;

class DetailTest extends TestCase
{
    public function testGetVisitorName()
    {
        $detail = new Detail();
        $detail ->setVisitorName('test');
        $result = $detail->getVisitorName();
        
        // assert that your commande added the mail correctly!
        $this->assertEquals('test', $result);
    }
    
    public function testGetCommandeId()
    {
        $detail = new Detail();
        $detail ->setCommandeId(2);
        $result = $detail->getCommandeId();
        
        // assert that your commande added the mail correctly!
        $this->assertEquals(2, $result);
    }
}