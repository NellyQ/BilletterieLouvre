<?php

namespace Louvre\BilletterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Detail
 *
 * @ORM\Table(name="details")
 * @ORM\Entity(repositoryClass="Louvre\BilletterieBundle\Repository\DetailsRepository")
 */
class Detail
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var int
     *
     * @ORM\Column(name="commande_id", type="integer")
     */
     private $commandeId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="visitor_name", type="string", length=255)
     */
    private $visitorName;

    /**
     * @var string
     *
     * @ORM\Column(name="visitor_firstname", type="string", length=255)
     */
    private $visitorFirstname;

    /**
     * @var int
     *
     * @ORM\Column(name="visitor_age", type="date")
     */
    private $visitorAge;

    /**
     * @var string
     *
     * @ORM\Column(name="visitor_country", type="string", length=255)
     */
    private $visitorCountry;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="visitor_reduc", type="boolean")
     */
    private $visitorReduc;
    
    /**
     * @ORM\ManyToOne(targetEntity="Commande", inversedBy="details")
     * @ORM\JoinColumn(name="id", referencedColumnName="commande_id", nullable=false)
     */
    private $commande;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set commandeId
     *
     * @param int $commandeId
     *
     * @return int
     */
    public function setCommandeId($commandeId)
    {
        $this->commandeId = $commandeId;

        return $this;
    }
    
    /**
     * Get commandeId
     *
     * @return int
     */
    public function getCommandeId()
    {
        return $this->commandeId;
    }

    /**
     * Set visitorName
     *
     * @param string $visitorName
     *
     * @return Detail
     */
    public function setVisitorName($visitorName)
    {
        $this->visitorName = $visitorName;

        return $this;
    }

    /**
     * Get visitorName
     *
     * @return string
     */
    public function getVisitorName()
    {
        return $this->visitorName;
    }

    /**
     * Set visitorFirstname
     *
     * @param string $visitorFirstname
     *
     * @return Detail
     */
    public function setVisitorFirstname($visitorFirstname)
    {
        $this->visitorFirstname = $visitorFirstname;

        return $this;
    }

    /**
     * Get visitorFirstname
     *
     * @return string
     */
    public function getVisitorFirstname()
    {
        return $this->visitorFirstname;
    }

    /**
     * Set visitorAge
     *
     * @param integer $visitorAge
     *
     * @return Detail
     */
    public function setVisitorAge($visitorAge)
    {
        $this->visitorAge = $visitorAge;

        return $this;
    }

    /**
     * Get visitorAge
     *
     * @return int
     */
    public function getVisitorAge()
    {
        return $this->visitorAge;
    }

    /**
     * Set visitorCountry
     *
     * @param string $visitorCountry
     *
     * @return Detail
     */
    public function setVisitorCountry($visitorCountry)
    {
        $this->visitorCountry = $visitorCountry;

        return $this;
    }

    /**
     * Get visitorCountry
     *
     * @return string
     */
    public function getVisitorCountry()
    {
        return $this->visitorCountry;
    }

    /**
     * Set visitorReduc
     *
     * @param boolean $visitorReduc
     *
     * @return Details
     */
    public function setVisitorReduc($visitorReduc)
    {
        $this->visitorReduc = $visitorReduc;

        return $this;
    }

    /**
     * Get visitorReduc
     *
     * @return boolean
     */
    public function getVisitorReduc()
    {
        return $this->visitorReduc;
    }
    
    /**
     * Set commande
     *
     */
    public function setCommande(Commande $commande)
    {
        $this->commande = $commande;
        return $this;
    }
    /**
     * Get commande
     *
     * @return string
     */
    public function getCommande()
    {
        return $this->commande;
    }
    
    /**
     * Set commande
     *
     */
    public function setPrixBillet($prixBillet)
    {
        $this->prixBillet = $prixBillet;
        return $this;
    }
    /**
     * Get commande
     *
     * @return string
     */
    public function getPrixBillet()
    {
        return $this->prixBillet;
    }
    
}
