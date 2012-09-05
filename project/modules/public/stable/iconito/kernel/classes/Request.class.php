<?php

/**
 * Manipulation de la requête initiale du client
 *
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @since 2012/06/06
 * @link http://api.symfony.com/2.0/Symfony/Component/HttpFoundation/Request.html
 */

class Request
{
    /**
     * Teste si la requête est de type Ajax
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/06/22
     * @return boolean True si c'est une requête Ajax, false sinon
     */
    public function isXmlHttpRequest()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']));
    }


    /**
     * Teste si la requête est de type Ajax
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/06/22
     * @return boolean True si c'est une requête Ajax, false sinon
     */
    public function isPostMethod()
    {
        return (isset($_SERVER['REQUEST_METHOD']) && 'post' == strtolower($_SERVER['REQUEST_METHOD']));
    }



}


