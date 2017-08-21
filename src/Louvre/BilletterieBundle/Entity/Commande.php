<?php

namespace Louvre\BilletterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="Louvre\BilletterieBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="commande_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $commandeId;

    /**
     * @var \Date
     *
     * @ORM\Column(name="commande_date", type="date")
     * @Assert\Range(
     *     min ="today",
     *     max ="+2 years",
     *     minMessage="date non valide",
     *     maxMessage="date non valide"
     * )
     */
    private $commandeDate;

    /**
     * @var string
     *
     * @ORM\Column(name="commande_typeBillet", type="string", length=255)
     */
    private $commandeTypeBillet;

    /**
     * @var int
     *
     * @ORM\Column(name="commande_nbBillet", type="integer")
     */
    private $commandeNbBillet;

    /**
     * @var int
     *
     * @ORM\Column(name="commande_prixTotal", type="integer", nullable=true)
     * 
     */
    private $commandePrixTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="commande_code", type="string", length=255, unique=true)
     */
    private $commandeCode;

    /**
     * @var string
     *
     * @ORM\Column(name="commande_mail", type="string", length=255)
     * @Assert\Email(
     *     checkHost = true,
     *     message = "email non valide",
     *     )
     */
    private $commandeMail;
    
    /**
     * @ORM\OneToMany(targetEntity="Detail", mappedBy="commande", cascade={"persist"})
     */
    private $details;

    /**
     * Get id
     *
     * @return int
     */
    public function getCommandeId()
    {
        return $this->commandeId;
    }

    /**
     * Set commandeDate
     *
     * @param \Date $commandeDate
     *
     * @return Commande
     */
    public function setCommandeDate($commandeDate)
    {
        $this->commandeDate = $commandeDate;

        return $this;
    }

    /**
     * Get commandeDate
     *
     * @return \Date
     */
    public function getCommandeDate()
    {
        return $this->commandeDate;
    }

    /**
     * Set commandeTypeBillet
     *
     * @param string $commandeTypeBillet
     *
     * @return Commande
     */
    public function setCommandeTypeBillet($commandeTypeBillet)
    {
        $this->commandeTypeBillet = $commandeTypeBillet;

        return $this;
    }

    /**
     * Get commandeTypeBillet
     *
     * @return string
     */
    public function getCommandeTypeBillet()
    {
        return $this->commandeTypeBillet;
    }

    /**
     * Set commandeNbBillet
     *
     * @param integer $commandeNbBillet
     *
     * @return Commande
     */
    public function setCommandeNbBillet($commandeNbBillet)
    {
        $this->commandeNbBillet = $commandeNbBillet;

        return $this;
    }

    /**
     * Get commandeNbBillet
     *
     * @return int
     */
    public function getCommandeNbBillet()
    {
        return $this->commandeNbBillet;
    }

    /**
     * Set commandePrixTotal
     *
     * @param integer $commandePrixTotal
     *
     * @return Commande
     */
    public function setCommandePrixTotal($commandePrixTotal)
    {
        $this->commandePrixTotal = $commandePrixTotal;

        return $this;
    }

    /**
     * Get commandePrixTotal
     *
     * @return int
     */
    public function getCommandePrixTotal()
    {
        return $this->commandePrixTotal;
    }

    /**
     * Set commandeCode
     *
     * @param string $commandeCode
     *
     * @return Commande
     */
    public function setCommandeCode($commandeCode)
    {
        $this->commandeCode = $commandeCode;

        return $this;
    }

    /**
     * Get commandeCode
     *
     * @return string
     */
    public function getCommandeCode()
    {
        return $this->commandeCode;
    }

    /**
     * Set commandeMail
     *
     * @param string $commandeMail
     *
     * @return Commande
     */
    public function setCommandeMail($commandeMail)
    {
        $this->commandeMail = $commandeMail;

        return $this;
    }

    /**
     * Get commandeMail
     *
     * @return string
     */
    public function getCommandeMail()
    {
        return $this->commandeMail;
    }
    
    public function __construct()
    {
        $this->details = new ArrayCollection();
    }

    public function addDetail(Detail $detail)
    {
        $this->details[] = $detail;
    }

    public function removeDetail(Detail $detail)
    {
        $this->details->removeElement($detail);
    }
    
    public function getDetails()
    {
        return $this->details;
    }
    
}

