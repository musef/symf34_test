<?php

namespace MainBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MainBundle\Controller\interfaces\AuthenticationInterface;
use MainBundle\Controller\AuthController;


/**
 * @Route("/api")
 */
class ApiAsignaturaController extends AuthController implements AuthenticationInterface
{
 

    /**
     * Recoge la petición para mostrar todas las asignaturas
     * 
     * @Route("/asignaturas")
     */
    public function readAllAsignaturasAction(Request $request) {

        if ($request->getMethod() == "GET") {

            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {        

                $em = $this->getDoctrine()->getEntityManager(); 
                $dao = $em->getRepository("MainBundle\Entity\Asignatura");

                $asignaturas = $dao->listAsignaturas();

                if (is_null($asignaturas)) {

                    $jsonresp = $this->preparingJsonResponses(404);    

                } else {

                    $arrayData = array();

                    if (is_array($asignaturas) && count($asignaturas)>0) {
                        foreach ($asignaturas as $asignatura) {
                            $asig = array(
                                'id'=> $asignatura->getId(),
                                'nombre'=> $asignatura->getNombre(),
                                'idEstudio'=> $asignatura->getIdEstudio()->getId()               
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
     * @Route("/asignaturas/{id}")
     */
    public function readAsignaturaAction(Request $request, $id = 0) {
        
        // evitando errores por id
        if (!is_numeric($id)) $id = 0;
        
        if ($request->getMethod() == "GET") {
            
            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {

                $em = $this->getDoctrine()->getEntityManager(); 
                $dao = $em->getRepository("MainBundle\Entity\Asignatura");

                $asignatura = $dao->getAsignatura($id);

                if (is_null($asignatura)) {

                    $jsonresp = $this->preparingJsonResponses(404);

                } else {

                    $asignaturaBuilt = array(
                        'id'=> $asignatura->getId(),
                        'nombre'=> $asignatura->getNombre(),
                        'idEstudio'=> $asignatura->getIdEstudio()->getId()               
                    );
                    $jsonresp = $this->preparingJsonResponses(200, $asignaturaBuilt); 

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
     * @Route("/asignaturas/nombre/{name}")
     */
    public function readAsignaturaByNameAction(Request $request, string $name) {
        
        // petición vacia no debe encontrar resultados 
        
        if ($request->getMethod() == "GET") {        

                
            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {
                    
                // sanitizamos la entrada
                $name = $this->cleanInput($name);

                if (strlen($name)>0) {                    

                    $em = $this->getDoctrine()->getEntityManager(); 
                    $dao = $em->getRepository("MainBundle\Entity\Asignatura");

                    $asignaturas = $dao->getAsignaturaByName($name);

                    if (is_null($asignaturas) || (is_array($asignaturas) && count($asignaturas)==0)) {

                        $jsonresp = $this->preparingJsonResponses(404);

                    } else {

                        $arrayData = array();

                        if (is_array($asignaturas) && count($asignaturas)>0) {
                            foreach ($asignaturas as $asignatura) {
                                $asig = array(
                                    'id'=> $asignatura['id'],
                                    'nombre'=> $asignatura['nombre'],
                                    'idEstudio'=> $asignatura['id_estudio']               
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
