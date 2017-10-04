<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Undertasks
 *
 * @ORM\Table(name="undertasks")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UndertasksRepository")
 */
class Undertasks
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
     * @ORM\Column(name="idtasks", type="integer")
     */
    private $idtasks;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=255)
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetimetz")
     */
    private $created;


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
     * Set idtasks
     *
     * @param integer $idtasks
     *
     * @return Undertasks
     */
    public function setIdtasks($idtasks)
    {
        $this->idtasks = $idtasks;

        return $this;
    }

    /**
     * Get idtasks
     *
     * @return int
     */
    public function getIdtasks()
    {
        return $this->idtasks;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Undertasks
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Undertasks
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}

