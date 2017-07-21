<?php

namespace Louvre\BilletterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Detail
 *
 * @ORM\Table(name="detail")
 * @ORM\Entity(repositoryClass="Louvre\BilletterieBundle\Repository\DetailRepository")
 */
class Details
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
     * @var string
     *
     * @ORM\Column(name="visitor_name", type="string", length=255)
     */
    private $visitorName;

    /**
     * @var string
     *
     * @ORM\Column(name="visitor_fisrtname", type="string", length=255)
     */
    private $visitorFisrtname;

    /**
     * @var int
     *
     * @ORM\Column(name="visitor_age", type="integer")
     */
    private $visitorAge;

    /**
     * @var string
     *
     * @ORM\Column(name="visitor_country", type="string", length=255)
     */
    private $visitorCountry;


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
     * Set visitorFisrtname
     *
     * @param string $visitorFisrtname
     *
     * @return Detail
     */
    public function setVisitorFisrtname($visitorFisrtname)
    {
        $this->visitorFisrtname = $visitorFisrtname;

        return $this;
    }

    /**
     * Get visitorFisrtname
     *
     * @return string
     */
    public function getVisitorFisrtname()
    {
        return $this->visitorFisrtname;
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
}

