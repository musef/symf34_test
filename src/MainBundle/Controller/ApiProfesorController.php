<?php

namespace MainBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MainBundle\Controller\interfaces\AuthenticationInterface;
use MainBundle\Controller\AuthController;

/**
 * @Route("/api")
 */
class ApiProfesorController extends AuthController implements AuthenticationInterface
{
 

    /**
     * Recoge la petición para mostrar todas las asignaturas
     * 
     * @Route("/profesores")
     * @Route("/profesores/nombre/")     
     */
    public function readAllProfesoresAction(Request $request) {

        if ($request->getMethod() == "GET") {

            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {        

                $em = $this->getDoctrine()->getEntityManager(); 
                $daoP = $em->getRepository("MainBundle\Entity\Profesor");

                $profesores = $daoP->listProfesores();

                if (is_null($profesores)) {

                    $jsonresp = $this->preparingJsonResponses(404);    

                } else {

                    $arrayData = array();

                    if (is_array($profesores) && count($profesores)>0) {
                        foreach ($profesores as $profesor) {
                            $asig = array(
                                'id'=> $profesor->getId(),
                                'nombre'=> $profesor->getNombre()              
                            );
                            $arrayData[] = $asig;
                        }
                    }
                    $jsonresp = $this->preparingJsonResponses(200,$arrayData);
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
     * Recoge la petición para mostrar la asignatura id
     * 
     * @Route("/profesores/{id}")
     */
    public function readProfesorAction(Request $request, $id = 0) {
        
        // evitando errores por id
        if (!is_numeric($id)) $id = 0;
        
        if ($request->getMethod() == "GET") {
            
            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {

                $em = $this->getDoctrine()->getEntityManager(); 
                $daoP = $em->getRepository("MainBundle\Entity\Profesor");

                $profesor = $daoP->getProfesor($id);

                if (is_null($profesor)) {

                    $jsonresp = $this->preparingJsonResponses(404);

                } else {

                    $profesorBuilt = array(
                        'id'=> $profesor->getId(),
                        'nombre'=> $profesor->getNombre(),             
                    );
                    $jsonresp = $this->preparingJsonResponses(200, $profesorBuilt); 

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
     * Recoge la petición para mostrar la/s asignatura/s cuyo 
     * nombre contenga "name"
     * 
     * @Route("/profesores/nombre/{name}")     
     */
    public function readProfesorByNameAction(Request $request, string $name) {
        
        // petición vacia no debe encontrar resultados 
        
        if ($request->getMethod() == "GET") {        
                
            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {
                    
                // sanitizamos la entrada
                $name = $this->cleanInput($name);

                if (strlen($name)>0) {                    

                    $em = $this->getDoctrine()->getEntityManager(); 
                    $daoP = $em->getRepository("MainBundle\Entity\Profesor");

                    $profesores = $daoP->getProfesorByName($name);

                    if (is_null($profesores) || (is_array($profesores) && count($profesores)==0)) {

                        $jsonresp = $this->preparingJsonResponses(404);

                    } else {

                        $arrayData = array();

                        if (is_array($profesores) && count($profesores)>0) {
                            foreach ($profesores as $profesor) {
                                $asig = array(
                                    'id'=> $profesor['id'],
                                    'nombre'=> $profesor['nombre']              
                                );
                                $arrayData[] = $asig;
                            }
                        }
                        $jsonresp = $this->preparingJsonResponses(200, $arrayData);
                    }

                } else {

                    $jsonresp = $this->preparingJsonResponses(404);

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
