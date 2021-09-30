<?php

namespace MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use \PDOException;
use Exception;

class ProfesorAsignaturaRepository extends EntityRepository {
    
    
    /**
     * Este método obtiene profesor/es relacionados por 
     * su asignatura
     * 
     * @param int $idAsignatura
     * @return - list profesores | null
     */
    public function getProfesoresByAsignatura(int $idAsignatura) {
        
        try {
            
            $em = $this->getEntityManager();
            
            $profesores = $em->getRepository("MainBundle\Entity\ProfesorAsignatura")->findBy(['idAsignatura'=>$idAsignatura]);
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $profesores;
        
    }
    
  
    /**
     * Este método obtiene asignatura/s relacionados por 
     * su profesor
     * 
     * @param int $idAsignatura
     * @return - list Asignaturas | null
     */
    public function getAsignaturasByProfesor(int $idProfesor) {
        
        try {
            
            $em = $this->getEntityManager();
            
            $asignaturas = $em->getRepository("MainBundle\Entity\ProfesorAsignatura")->findBy(['idProfesor'=>$idProfesor]);
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $asignaturas;
        
    }  
         
    
}

