<?php

namespace lib;

class Router {
    /*
    * @var routers
    */

    protected $routers = array(
        'site' => 'site',
        'admin' => 'admin'
    );

    private $urlBase = APP_ROOT;

    /*
     * @var router on raiz
     */

    protected $routerOnRaiz = 'site';

    /*
     * @var onRaiz
     */

    protected $onRaiz = true;
}