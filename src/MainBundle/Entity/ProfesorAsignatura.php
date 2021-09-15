<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProfesorAsignatura
 *
 * @ORM\Table(name="profesor_asignatura", indexes={@ORM\Index(name="id_asignatura", columns={"id_asignatura"}), @ORM\Index(name="id_profesor", columns={"id_profesor"})})
 * @ORM\Entity
 */
class ProfesorAsignatura
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
     * @var \MainBundle\Entity\Profesor
     *
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Profesor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_profesor", referencedColumnName="id")
     * })
     */
    private $idProfesor;

    /**
     * @var \MainBundle\Entity\Asignatura
     *
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Asignatura")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_asignatura", referencedColumnName="id")
     * })
     */
    private $idAsignatura;
    

    public function getId() {
        return $this->id;
    }    
    
    public function getIdProfesor() {
        return $this->idProfesor;
    }        
    
    public function getIdAsignatura() {
        return $this->idAsignatura;
    }        
     
    
}

