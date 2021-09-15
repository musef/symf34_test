<?php

namespace MainBundle\Controller\interfaces;


/**
 *
 * @author floren
 */
interface AuthenticationInterface {
    
    // key basica fija de seguridad
    const API_KEY = "dFfGjkjew2rjT9fjdsNfjewur5wojMfojds6k8fCjdsjfasdueOw9D3ruwjPfsFdjfsd4fj";
    
    // comprueba que esta autenticado el cliente
    public function checkAuthentication( string $apikey ): bool;
    
    // limpia entradas de texto por parametros
    public function cleanInput( string $data ): string;
    
}
