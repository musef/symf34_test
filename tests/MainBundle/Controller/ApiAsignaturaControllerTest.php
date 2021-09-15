<?php

namespace test\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use MainBundle\Controller\ApiAsignaturaController;
use Symfony\Component\HttpFoundation\Request;


class ApiAsignaturaControllerTest extends WebTestCase
{
    
    protected $apiKey = "";

    public function setUp(): void {
        if ($this->apiKey =="") {
            $asignController = new ApiAsignaturaController;
            //$this->apiKey = "dFfGjkjew2rjT9fjdsNfjewur5wojMfojds6k8fCjdsjfasdueOw9D3ruwjPfsFdjfsd4fj";
            $this->apiKey = $asignController::API_KEY;
        }

    }
    
    
    /* ********* readAsignaturaAction **************** */
    
    /**
     * Chequea fallo por API KEY incorrecta, vale para todos los métodos
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_oneasign
     */       
    public function testAsignaturaActionFailAuthentication()
    {

        $apiKey = base64_encode("blablablablablabla");
        $idSearched = 1;
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/asignaturas/'.$idSearched, array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $expectedResult = array();
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getResult = (is_array($getResult->data) && count($getResult->data) == 0) ? array() : $getResult->data->nombre; 
                
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertEquals($expectedResult, $getResult);
        
    }    
    
    
    /**
     * Chequea fallo por METHOD incorrecto
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_oneasign
     */       
    public function testAsignaturaActionFailMethod()
    {
        $apiKey = base64_encode($this->apiKey);
        $idSearched = 1;
        
        $client = static::createClient();

        $crawler = $client->request('PATCH', '/asignaturas/'.$idSearched, array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $expectedResult = array();
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getResult = (is_array($getResult->data) && count($getResult->data) == 0) ? array() : $getResult->data->nombre; 
                
        $this->assertEquals(405, $client->getResponse()->getStatusCode());
        $this->assertEquals($expectedResult, $getResult);
        
    }      
    
    
    /**
     * Chequea obtener asignatura por id
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_oneasign
     */    
    public function testAsignaturaActionOK()
    {
        $apiKey = base64_encode($this->apiKey);
        $idSearched = 1;
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/asignaturas/'.$idSearched, array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $expectedResult = array ("id"=>1,"nombre"=>"Algebra");
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getResult = (is_array($getResult->data) && count($getResult->data) == 0) ? "" : $getResult->data->nombre;        
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expectedResult['nombre'], $getResult);
        
    }
    
    
    /**
     * Chequea obtener asignatura por id
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_oneasign
     */    
    public function testAsignaturaActionFailWrongIdSearched()
    {
        $apiKey = base64_encode($this->apiKey);
        $idSearched = 2;
                
        $client = static::createClient();

        $crawler = $client->request('GET', '/asignaturas/'.$idSearched, array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $expectedResult = array ("id"=>1,"nombre"=>"Algebra");
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getResult = (is_array($getResult->data) && count($getResult->data) == 0) ? "" : $getResult->data->nombre;        
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEquals($expectedResult['nombre'], $getResult);
        
    }    
    
    
    /**
     * Chequea obtener asignatura por id
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_oneasign
     */    
    public function testAsignaturaActionFailNoExistingIdSearched()
    {
        $apiKey = base64_encode($this->apiKey);
        $idSearched = 8;
                
        $client = static::createClient();

        $crawler = $client->request('GET', '/asignaturas/'.$idSearched, array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $expectedResult = array ("id"=>1,"nombre"=>"Algebra");
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getResult = (is_array($getResult->data) && count($getResult->data) == 0) ? "" : $getResult->data->nombre;        
        
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertNotEquals($expectedResult['nombre'], $getResult);
        
    }     
    
    
    /* ********* readAllAsignaturasAction **************** */
    
    
    /**
     * Chequea obtener todas asignaturas
     * Test preparado para usar con DDBB de test !!
     * verificar $numResults en DDBB 
     *      
     * @group asign_all
     */
    public function testReadAllAsignaturasActionOK()
    {
        
        $apiKey = base64_encode($this->apiKey);
        // poner aqui los registros en DDBB
        $numResults = 6;
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/asignaturas',array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getNumResults = (is_array($getResult->data) && count($getResult->data) == 0) ? 0 : count($getResult->data);
                
        $this->assertEquals($numResults, $getNumResults);
        
        $expectedFirstResult = array ("id"=>1,"nombre"=>"Algebra");
        $getResult = (is_array($getResult->data) && count($getResult->data) == 0) ? "" : $getResult->data[0]->nombre;
        
        $this->assertEquals($expectedFirstResult['nombre'], $getResult);
    } 
    
    
    /**
     * Chequea fallo por API KEY incorrecta, vale para todos los métodos
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_all
     */       
    public function testAllAsignaturasActionFailAuthentication()
    {

        $apiKey = base64_encode("blablablablablabla");
        
        // poner aqui los registros en DDBB
        $numResults = 6;
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/asignaturas',array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getNumResults = (is_array($getResult->data) && count($getResult->data) == 0) ? 0 : count($getResult->data);
                
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $getNumResults);
        
    }    
    
    
    /**
     * Chequea fallo por METHOD incorrecto
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_all
     */       
    public function testAllAsignaturaActionFailMethod()
    {
        $apiKey = base64_encode($this->apiKey);
        // poner aqui los registros en DDBB
        $numResults = 6;
        
        $client = static::createClient();

        $crawler = $client->request('PATCH', '/asignaturas',array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getNumResults = (is_array($getResult->data) && count($getResult->data) == 0) ? 0 : count($getResult->data);
                
        $this->assertEquals(405, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $getNumResults);
        
    }      
    

    
    /* ********* readAsignaturaByNameAction **************** */
    
    
    /**
     * Chequea busqueda asignatura por nombre (string parcial o total)
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_asignbyname
     */     
    public function testAsignaturaByNameActionOK()
    {
        $apiKey = base64_encode($this->apiKey);
        $nameSearched = "Franc";
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/asignaturas/nombre/'.$nameSearched, array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getNumResults = (is_array($getResult->data) && count($getResult->data) == 0) ? 0 : count($getResult->data);
                
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(2, $getNumResults);
        
    }    
    
    
    /**
     * Chequea busqueda asignatura por nombre (string parcial o total)
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_asignbyname
     */     
    public function testAsignaturaByNameActionFail()
    {
        $apiKey = base64_encode($this->apiKey);
        // cadena no existente !!
        $nameSearched = "XXXXXXX";
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/asignaturas/nombre/'.$nameSearched, array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getNumResults = (is_array($getResult->data) && count($getResult->data) == 0) ? 0 : count($getResult->data);
                
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $getNumResults);
        
    }      
    
    
    /**
     * Chequea fallo por API KEY incorrecta, vale para todos los métodos
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_asignbyname
     */       
    public function testAsignaturaByNameActionFailAuthentication()
    {

        $apiKey = base64_encode("blablablablablabla");
        $nameSearched = "Franc";
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/asignaturas/nombre/'.$nameSearched, array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getNumResults = (is_array($getResult->data) && count($getResult->data) == 0) ? 0 : count($getResult->data);
                
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $getNumResults);
        
    }    
    
    
    /**
     * Chequea fallo por METHOD incorrecto
     * Test preparado para usar con DDBB de test !!
     * 
     * @group asign_asignbyname
     */       
    public function testAsignaturaByNameActionFailMethod()
    {
        $apiKey = base64_encode($this->apiKey);
        $nameSearched = "Franc";
        
        $client = static::createClient();

        $crawler = $client->request('PATCH', '/asignaturas/nombre/'.$nameSearched, array(), array(), array('HTTP_x-api-key'=>array('x-api-key'=>$apiKey)) );
        
        $getResult = $client->getResponse()->getContent();
        $getResult = json_decode($getResult);
        $getNumResults = (is_array($getResult->data) && count($getResult->data) == 0) ? 0 : count($getResult->data);
                
        $this->assertEquals(405, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $getNumResults);
        
    }  
    
    
    
    /* ********* preparingJsonResponses **************** */
    
    /**
     * @group asign_json
     * EL status 200 debe devolver un data de array con contenido
     */    
    public function testPreparingJsonResponses200()
    {
        $statusExpected = 200;
        $dataExpected = array("blablabla");
        
        $apiController = new ApiAsignaturaController;
        $result = $apiController->preparingJsonResponses($statusExpected, $dataExpected);
        
        $this->assertEquals($statusExpected,$result['status']);
        $this->assertNotEquals(array(),$result['resp']['data']);
    }    
    
    
    /**
     * @group asign_json
     * EL status 404 debe devolver un data de array vacio
     */
    public function testPreparingJsonResponses404()
    {
        $statusExpected = 404;
        $dataExpected = array();
        
        $apiController = new ApiAsignaturaController;
        $result = $apiController->preparingJsonResponses($statusExpected);       
        
        $this->assertEquals($statusExpected,$result['status']);
        $this->assertEquals($dataExpected,$result['resp']['data']);
    } 

    /**
     * @group asign_json 
     * EL status 404 debe devolver un data de array vacio
     */
    public function testPreparingJsonResponses404Fails()
    {
        $statusExpected = 404;
        $dataExpected =  array('algo');
        
        $apiController = new ApiAsignaturaController;
        $result = $apiController->preparingJsonResponses($statusExpected);
        
        $this->assertNotEquals(200,$result['status']);
        $this->assertNotEquals($dataExpected,$result['resp']['data']);
    } 
    
}
