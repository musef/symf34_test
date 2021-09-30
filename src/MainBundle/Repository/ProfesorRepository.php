<?php

namespace MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use \PDOException;
use Exception;

class ProfesorRepository extends EntityRepository {
    
    
    /**
     * Este método obtiene un profesor relacionado por 
     * su id
     * 
     * @param int $id
     * @return - object profesor | null
     */
    public function getProfesor(int $id) {
        
        try {
            
            $em = $this->getEntityManager();
            
            $profesor = $em->getRepository("MainBundle\Entity\Profesor")->find($id);
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $profesor;
        
    }
    
    
    /**
     * Este método obtiene la lista de todos las profesores filtrados por 
     * nombre
     * 
     * @param string $name
     * @return array Profesores | null
     */
    public function getProfesorByName(string $name) {
        
        $name = '%'.$name.'%';
        
        try {

            $sql = " SELECT * FROM profesor WHERE nombre LIKE :name";            
            
            $em = $this->getEntityManager();
                       
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindParam('name', $name);
            $stmt->execute();
            
            $profesor = $stmt->fetchAll();
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $profesor;
        
    }    
    
    
    /**
     * Este método obtiene la lista de todos los profesores
     * @return - lista Profesores | null
     */
    public function listProfesores() {
        
        try {
            
            $em = $this->getEntityManager();
            
            $profesores = $em->getRepository("MainBundle\Entity\Profesor")->findAll();
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $profesores;
        
    }    
         
    
}

