<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Controller\interfaces\AuthenticationInterface;


class AuthController extends Controller implements AuthenticationInterface
{

    
    /**
     * Método básico de autenticación en consultas
     * 
     * @param type $apikey
     * @return type
     */
    public function checkAuthentication(string $apikey):bool {
        
        $apikey = $this->cleanInput(base64_decode($apikey));
        
        $resp = ($apikey == self::API_KEY) ? true : false;
        
        return $resp;
    }
    
    
    /**
     * Este método sanitiza los string recibidos
     * 
     * @param string $input
     * @return type
     */
    public function cleanInput(string $input):string {
        
        $input = strip_tags(trim($input));
        
        $forbidden = array("&", "%", "!", "$", "[", "]", "{", "}", "=", "?", ";", ",");

        $input = str_replace($forbidden,"",$input);
        
        return $input;
    }  
    
}
