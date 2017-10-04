<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invite
 *
 * @ORM\Table(name="invite")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\InviteRepository")
 */
class Invite
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
     * @ORM\Column(name="idinvite", type="integer")
     */
    private $idinvite;

    /**
     * @var int
     *
     * @ORM\Column(name="idprojet", type="integer")
     */
    private $idprojet;

    /**
     * @var int
     *
     * @ORM\Column(name="etat", type="integer")
     */
    private $etat;


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
     * Set idinvite
     *
     * @param integer $idinvite
     *
     * @return Invite
     */
    public function setIdinvite($idinvite)
    {
        $this->idinvite = $idinvite;

        return $this;
    }

    /**
     * Get idinvite
     *
     * @return int
     */
    public function getIdinvite()
    {
        return $this->idinvite;
    }

    /**
     * Set idprojet
     *
     * @param integer $idprojet
     *
     * @return Invite
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
     * Set etat
     *
     * @param integer $etat
     *
     * @return Invite
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return int
     */
    public function getEtat()
    {
        return $this->etat;
    }

}

