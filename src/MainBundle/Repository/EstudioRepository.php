<?php

namespace MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use \PDOException;
use Exception;

class EstudioRepository extends EntityRepository {
    
    
    /**
     * Este método obtiene un estudio relacionado por 
     * su id
     * 
     * @param int $id
     * @return - object estudio | null
     */
    public function getEstudio(int $id) {
        
        try {
            
            $em = $this->getEntityManager();
            
            $estudio = $em->getRepository("MainBundle\Entity\Estudio")->find($id);
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $estudio;
        
    }
    
    
    /**
     * Este método obtiene la lista de todos las estudios filtrados por 
     * nombre
     * 
     * @param string $name
     * @return array Estudios | null
     */
    public function getEstudioByName(string $name) {
        
        $name = '%'.$name.'%';
        
        try {

            $sql = " SELECT * FROM estudio WHERE nombre LIKE :name";            
            
            $em = $this->getEntityManager();
                       
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindParam('name', $name);
            $stmt->execute();
            
            $estudio = $stmt->fetchAll();
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $estudio;
        
    }    
    
    
    /**
     * Este método obtiene la lista de todos los estudios
     * @return - lista Estudios | null
     */
    public function listEstudios() {
        
        try {
            
            $em = $this->getEntityManager();
            
            $estudios = $em->getRepository("MainBundle\Entity\Estudio")->findAll();
            
            
        } catch (Exception $exc) {
            
            return null;
            
        } catch (PDOException $pdex) {
            
            return null;
        }

        return $estudios;
        
    }    
         
    
}

