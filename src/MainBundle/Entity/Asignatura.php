<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asignatura
 *
 * @ORM\Table(name="asignatura", indexes={@ORM\Index(name="fk_id_estudio", columns={"id_estudio"})})
 * @ORM\Entity
 */
class Asignatura
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre = 'nueva asignatura';

    /**
     * @var \MainBundle\Entity\Estudio
     *
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Estudio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_estudio", referencedColumnName="id")
     * })
     */
    private $idEstudio;

    public function getId() {
        return $this->id;
    }    
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getIdEstudio() {
        return $this->idEstudio;
    }    
}

