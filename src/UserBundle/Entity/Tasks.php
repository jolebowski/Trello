<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tasks
 *
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\TasksRepository")
 */
class Tasks
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
     * @ORM\Column(name="idprojet", type="integer")
     */
    private $idprojet;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dates", type="datetimetz")
     */
    private $dates;


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
     * Set idprojet
     *
     * @param integer $idprojet
     *
     * @return Tasks
     */
    public function setIdprojet($idprojet)
    {
        $this->idprojet = $idprojet;

        return $this;
    }

    /**
     * Get idprojet
     *
     * @return int
     */
    public function getIdprojet()
    {
        return $this->idprojet;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Tasks
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set dates
     *
     * @param \DateTime $dates
     *
     * @return Tasks
     */
    public function setDates($dates)
    {
        $this->dates = $dates;

        return $this;
    }

    /**
     * Get dates
     *
     * @return \DateTime
     */
    public function getDates()
    {
        return $this->dates;
    }
}

