<?php

namespace MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use \PDOException;
use Exception;

class AsignaturaRepository extends EntityRepository {
    
    
    /**
     * Este método obtiene una asignatura relacionada por 
     * su id
     * 
     * @param int $id
     * @return - object asignatura | null
     */
    public function getAsignatura(int $id) {
        
        try {
            
            $em = $this->getEntityManager();
            
            $asignatura = $em->getRepository("MainBundle\Entity\Asignatura")->find($id);
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $asignatura;
        
    }
    
    
    /**
     * Este método obtiene la lista de todas las asignaturas filtradas por 
     * nombre
     * 
     * @param string $name
     * @return array Asignaturas | null
     */
    public function getAsignaturaByName(string $name) {
        
        $name = '%'.$name.'%';
        
        try {

            $sql = " SELECT * FROM asignatura WHERE nombre LIKE :name";            
            
            $em = $this->getEntityManager();
                       
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindParam('name', $name);
            $stmt->execute();
            
            $asignatura = $stmt->fetchAll();
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $asignatura;
        
    }    
    
    
    /**
     * Este método obtiene la lista de todas las asignaturas
     * @return - lista Asignaturas | null
     */
    public function listAsignaturas() {
        
        try {
            
            $em = $this->getEntityManager();
            
            $asignaturas = $em->getRepository("MainBundle\Entity\Asignatura")->findAll();
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $asignaturas;
        
    }    
         
    
}

