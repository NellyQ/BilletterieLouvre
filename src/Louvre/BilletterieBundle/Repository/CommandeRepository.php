<?php

namespace Louvre\BilletterieBundle\Repository;

/**
 * CommandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandeRepository extends \Doctrine\ORM\EntityRepository
{ 
    /*Nombre total de billet vendu par jour*/
    public function totalNbBilletJour()
    {
        $qb = $this->createQueryBuilder('c');
        
        $qb ->select('c.commandeDate')
            ->addSelect('SUM(c.commandeNbBillet) AS nbTotal')
            ->groupBy('c.commandeDate');

        return $qb->getQuery()->getArrayResult();
        
    }
}

