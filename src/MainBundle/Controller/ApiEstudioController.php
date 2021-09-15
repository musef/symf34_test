<?php

namespace MainBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MainBundle\Controller\interfaces\AuthenticationInterface;
use MainBundle\Controller\AuthController;

/**
 * @Route("/api")
 */
class ApiEstudioController extends AuthController implements AuthenticationInterface
{
 

    /**
     * Recoge la petición para mostrar todos los estudios
     * 
     * @Route("/estudios")
     * @Route("/estudios/nombre/")     
     */
    public function readAllEstudiosAction(Request $request) {

        if ($request->getMethod() == "GET") {

            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {        

                $em = $this->getDoctrine()->getEntityManager(); 
                $daoE = $em->getRepository("MainBundle\Entity\Estudio");

                $estudios = $daoE->listEstudios();

                if (is_null($estudios)) {

                    $jsonresp = $this->preparingJsonResponses(404);    

                } else {

                    $arrayData = array();

                    if (is_array($estudios) && count($estudios)>0) {
                        foreach ($estudios as $estudio) {
                            $asig = array(
                                'id'=> $estudio->getId(),
                                'nombre'=> $estudio->getNombre()              
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
     * Recoge la petición para mostrar los estudios por id
     * 
     * @Route("/estudios/{id}")
     */
    public function readEstudioAction(Request $request, $id = 0) {
        
        // evitando errores por id
        if (!is_numeric($id)) $id = 0;
        
        if ($request->getMethod() == "GET") {
            
            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {

                $em = $this->getDoctrine()->getEntityManager(); 
                $daoE = $em->getRepository("MainBundle\Entity\Estudio");

                $estudio = $daoE->getEstudio($id);

                if (is_null($estudio)) {

                    $jsonresp = $this->preparingJsonResponses(404);

                } else {

                    $estudioBuilt = array(
                        'id'=> $estudio->getId(),
                        'nombre'=> $estudio->getNombre(),             
                    );
                    $jsonresp = $this->preparingJsonResponses(200, $estudioBuilt); 

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
     * Recoge la petición para mostrar el/los estudio/s cuyo 
     * nombre contenga "name"
     * 
     * @Route("/estudios/nombre/{name}")     
     */
    public function readEstudioByNameAction(Request $request, string $name) {
        
        // petición vacia no debe encontrar resultados 
        
        if ($request->getMethod() == "GET") {        
                
            $api_key = $request->headers->get('x-api-key') ?? "0";

            if ($this->checkAuthentication($api_key)) {
                    
                // sanitizamos la entrada
                $name = $this->cleanInput($name);

                if (strlen($name)>0) {                    

                    $em = $this->getDoctrine()->getEntityManager(); 
                    $daoE = $em->getRepository("MainBundle\Entity\Estudio");

                    $estudios = $daoE->getEstudioByName($name);

                    if (is_null($estudios) || (is_array($estudios) && count($estudios)==0)) {

                        $jsonresp = $this->preparingJsonResponses(404);

                    } else {

                        $arrayData = array();

                        if (is_array($estudios) && count($estudios)>0) {
                            foreach ($estudios as $estudio) {
                                $asig = array(
                                    'id'=> $estudio['id'],
                                    'nombre'=> $estudio['nombre']              
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
