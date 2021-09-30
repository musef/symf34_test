<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Controller\interfaces\AuthenticationInterface;
use Exception;


class AuthController extends Controller implements AuthenticationInterface
{

    
    /**
     * Método básico de autenticación en consultas
     * 
     * @param type $apikey
     * @return type
     */
    public function checkAuthentication(string $apikey):bool {        
        
        $result = $this->showAuthentication($apikey);    

        $resp = ($result['apikey'] == self::API_KEY) ? true : false;

        return $resp;
    }

    
    /**
     * Este método obtiene la autenticación y la fecha de validez
     * de una apikey encriptada recibida por la API
     * 
     * @param string $apikey codificada
     * @return - array con resultados
     */
    public function showAuthentication(string $apikey) {

        // OCULTO POR SEGURIDAD
        
    }

    
    /**
     *
     * @return type
     */
    public function generateApiAuthorization() {
        
        // OCULTO POR SEGURIDAD
        
    }
    
    
    /**
     * Este método sanitiza los string recibidos
     * 
     * @param string $input
     * @return type
     */
    public function cleanInput(string $input):string {
        
        $input = strip_tags(trim($input));
        
        $forbidden = array("&", "%", "!", "$", "[", "]", "{", "}", "?", ";", ",");

        $input = str_replace($forbidden,"",$input);
        
        return $input;
    }  
    
}
