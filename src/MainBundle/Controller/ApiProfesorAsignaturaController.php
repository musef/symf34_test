<?php

namespace MainBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MainBundle\Controller\interfaces\AuthenticationInterface;
use MainBundle\Controller\AuthController;

/**
 * @Route("/api")
 */
class ApiProfesorAsignaturaController extends AuthController implements AuthenticationInterface
{
    
    
    /**
     * Recoge la petición para mostrar los profesores de una asignatura id
     * 
     * @Route("/asignaturas/{id}/profesores")
     */
    public function readProfesoresByAsignaturaAction(Request $request, $id = 0) {
        
        // evitando errores por id
        if (!is_numeric($id)) $id = 0;
        
        if ($request->getMethod() == "GET") {
            
            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {

                $em = $this->getDoctrine()->getEntityManager(); 
                $daoPA = $em->getRepository("MainBundle\Entity\ProfesorAsignatura");

                $profesores = $daoPA->getProfesoresByAsignatura($id);

                if (is_null($profesores)) {

                    $jsonresp = $this->preparingJsonResponses(404);

                } else {
                    $arrayData = array();

                    if (is_array($profesores) && count($profesores)>0) {
                        foreach ($profesores as $profesorAsig) {
                            $profe = array(
                                'id'=> $profesorAsig->getIdProfesor()->getId(),
                                'nombre'=> $profesorAsig->getIdProfesor()->getNombre()              
                            );
                            $arrayData[] = $profe;
                        }                        
                    }
                    $jsonresp = $this->preparingJsonResponses(200, $arrayData); 

                }     
                
            } else {

                $jsonresp = $this->preparingJsonResponses(401);

            }    
        
        } else {
            
            $jsonresp = $this->preparingJsonResponses(405);
        }        

        return $this->json($jsonresp['resp'], $jsonresp['status']); 
        
    }
    
    
    
    /**
     * Recoge la petición para mostrar las asignaturas de un profesor id
     * 
     * @Route("/profesores/{id}/asignaturas")
     */
    public function readAsignaturasByProfesorAction(Request $request, $id = 0) {
        
        // evitando errores por id
        if (!is_numeric($id)) $id = 0;
        
        if ($request->getMethod() == "GET") {
            
            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {

                $em = $this->getDoctrine()->getEntityManager(); 
                $daoPA = $em->getRepository("MainBundle\Entity\ProfesorAsignatura");

                $asignaturas = $daoPA->getAsignaturasByProfesor($id);

                if (is_null($asignaturas)) {

                    $jsonresp = $this->preparingJsonResponses(404);

                } else {
                    $arrayData = array();

                    if (is_array($asignaturas) && count($asignaturas)>0) {
                        foreach ($asignaturas as $asignaturaProf) {
                            $asig = array(
                                'id'=> $asignaturaProf->getIdAsignatura()->getId(),
                                'nombre'=> $asignaturaProf->getIdAsignatura()->getNombre()              
                            );
                            $arrayData[] = $asig;
                        }                        
                    }
                    $jsonresp = $this->preparingJsonResponses(200, $arrayData); 

                }     
                
            } else {

                $jsonresp = $this->preparingJsonResponses(401);

            }    
        
        } else {
            
            $jsonresp = $this->preparingJsonResponses(405);
        }        

        return $this->json($jsonresp['resp'], $jsonresp['status']); 
        
    }
        
    
    
    /**
     * Este método genera el array de respuestas JSON
     * 
     * @param int $status
     * @param type $data
     * @return - array respuesta
     */
    public function preparingJsonResponses(int $status, $data = array()) {
        
        switch ($status) {
            case 200:
                $arrayReturned = array (
                    'resp' => array(
                                'data' => $data,
                                'message' => 'Petición ejecutada correctamente'
                            ),
                    'status' => $status
                );
                break;             
            case 401:
                $arrayReturned = array (
                    'resp' => array(
                                'data' => $data,
                                'message' => 'Autenticación errónea'
                            ),
                    'status' => $status
                );
                break;            
            case 404:
                $arrayReturned = array (
                    'resp' => array(
                                'data' => $data,
                                'message' => 'Ningún resultado obtenido'
                            ),
                    'status' => $status
                );
                break;
            case 405:
                $arrayReturned = array (
                    'resp' => array(
                                'data' => $data,
                                'message' => 'Método petición HTTP incorrecto'
                            ),
                    'status' => $status
                );
                break;            
            default:
                $arrayReturned = array (
                    'resp' => array(
                                'data' => array(),
                                'message' => 'Error en petición'
                            ),
                    'status' => 400
                );                
            
        }
        
        return $arrayReturned;
         
    }
    
}
