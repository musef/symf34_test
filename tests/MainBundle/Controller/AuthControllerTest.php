<?php

namespace MainBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use MainBundle\Controller\AuthController;


class AuthControllerTest extends WebTestCase
{

    /**
     * @group checkauth
     * Comprueba si la api-key es chequeada true
     */
    public function testCheckAuthentication()
    {
        $auth = new AuthController;
        $apiKey = base64_encode($auth::API_KEY);
        
        $checked = $auth->checkAuthentication($apiKey);        
        
        $this->assertTrue($checked);
    } 

    
    /**
     * @group checkauth
     * Chequea si una api-key incorrecta no es chequeada true
     */
    public function testCheckAuthenticationFails()
    {
                
        $auth = new AuthController;
        $apiKey = base64_encode("blablablablablablablablablabla");
        
        $checked = $auth->checkAuthentication($apiKey);        
        
        $this->assertNotTrue($checked);
        
    }
    
    
    /**
     * @group clean
     * chequea si limpia el string
     */
    public function testcleanInput()
    {
        $auth = new AuthController;
        
        $stringToClean = 'A&BS%O!L$UT&AM%EN=T?E; ;L,I{M}P[I]O';
        $stringCleaned = "ABSOLUTAMENTE LIMPIO";
        
        $cleaned = $auth->cleanInput($stringToClean);        
        
        $this->assertEquals($stringCleaned,$cleaned);
    }
  

    /**
     * @group clean
     * Chequea si los caracteres limpiados serian admitidos
     */
    public function testcleanInputFails()
    {
        $auth = new AuthController;
        
        $stringToClean = 'A&BS%O!L$UT&AM%EN=T?E;';
        $trashCleaned = '&%!$&%=?;';
        
        $cleaned = $auth->cleanInput($stringToClean);  
        $forbidden = str_split($cleaned);
        $stringCleanedReversed = str_replace($forbidden, "", $stringToClean);
        
        $this->assertEquals($trashCleaned,$stringCleanedReversed);
        
        $stringToClean = ';L,I{M}P[I]O';
        $trashCleaned = ";,{}[]";

        $cleaned = $auth->cleanInput($stringToClean);  
        $forbidden = str_split($cleaned);
        $stringCleanedReversed = str_replace($forbidden, "", $stringToClean);
        
        $this->assertEquals($trashCleaned,$stringCleanedReversed);
    }    
}
