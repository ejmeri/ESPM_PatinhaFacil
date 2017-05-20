<?php

namespace lib;

class System extends Router {

    /*
     * @var url
     */
    private $url;

    /*
     * @var explode
     */
    private $explode;

    /*
     * @var area
     */
    private $area;

    /*
     * @var controller
     */
    private $controller;

    /*
     * @var run controller
     */
    private $runcontroller;

    /*
     * @var action
     */
    private $action;

    /*
     * @var params
     */
    private $params;

    /*
     * Start
     */

    public function __construct() {
        //Filter string
        $this->_cleanString();
        //Run function set url
        $this->setUrl();
        //Run function set explode
        $this->setExplode();
        //Run function set area
        $this->setArea();
        //Run function set controller
        $this->setController();
        //Run function set action
        $this->setAction();
        //Run function set params
        $this->setParams();
    }

    /*
     * set $url
     *
     * see System
     *
     * return Void
     */

    private function setUrl() {
        $this->url = isset($_GET['url']) ? $_GET['url'] : 'home/index';
    }

    /*
     * set $explode
     *
     * see System
     *
     * return void
     */

    public function setExplode() {
        $this->explode = explode('/', $this->url);
    }

    /*
     * set $area
     *
     * see System
     *
     * return Void
     */

    private function setArea() {
        foreach ($this->routers as $ind => $val) {
            if ($this->onRaiz && $this->explode[0] == $ind) {
                $this->area = $val;
                $this->onRaiz = false;
            }
        }

        $this->area = empty($this->area) ? $this->routerOnRaiz : $this->area;
        if (!defined('APP_AREA')){
            define('APP_AREA', $this->area);
        }
    }

    /*
     * Return $area
     *
     * see System
     *
     * return String
     */

    public function getArea() {
        return $this->area;
    }

    /*
     * set $controller
     *
     * see System
     *
     * return Void
     */

    private function setController() {
        $this->controller = $this->onRaiz ? $this->explode[0] :
            (empty($this->explode[1]) || is_null($this->explode[1]) || !isset($this->explode[1]) ? 'home' : $this->explode[1]);
    }

    /*
     * Return $controller
     *
     * see System
     *
     * return String
     */

    public function getController() {
        return $this->controller;
    }

    /*
     * Return $controller
     *
     * see System
     *
     * return void
     */

    private function validateController() {
        if (!(class_exists($this->runcontroller))) {
            header("HTTP/1.0 404 Not Found");
            define('ERROR', 'A página '.$this->controller.' foi localizada');
            include("content/{$this->area}/shared/errorpage.phtml");
            exit();
        }
    }

    /*
     * set $action
     *
     * see System
     *
     * return Void
     */

    private function setAction() {
        $this->action = $this->onRaiz ?
            (!isset($this->explode[1]) || is_null($this->explode[1]) || empty($this->explode[1]) ? 'index' : $this->explode[1]) :
            (!isset($this->explode[2]) || is_null($this->explode[2]) || empty($this->explode[2]) ? 'index' : $this->explode[2]);
    }

    /*
     * Return $action
     *
     * see System
     *
     * return String
     */

    public function getAction() {
        return $this->action;
    }

    /*
     * Return $controller
     *
     * see System
     *
     * return void
     */

    private function validateAction() {
        if (!(method_exists($this->runcontroller, $this->action))) {
            header("HTTP/1.0 404 Not Found");
            define('ERROR', 'A página '.$this->action.' foi localizada');
            include("content/{$this->area}/shared/errorpage.phtml");
            exit();
        }
    }

    /*
     * set $params
     *
     * see System
     *
     * return Void
     */

    private function setParams() {
        if ($this->onRaiz) {
            unset($this->explode[0], $this->explode[1]);
        } else {
            unset($this->explode[0], $this->explode[1], $this->explode[2]);
        }

        if (end($this->explode) == null) {
            array_pop($this->explode);
        }

        if (empty($this->explode)) {
            $this->params = array();
        } else {
            foreach ($this->explode as $val) {
                $params[] = $val;
            }
            $this->params = $params;
        }
    }

    /*
     * Return $params
     *
     * see System
     *
     * @param indice "int"
     *
     * return String
     */

    public function getParams($indice) {
        return isset($this->params[$indice]) ? $this->params[$indice] : null;
    }

    public function Run() {
        //Run function validate controller
        //$this->requiredController();


        $this->runcontroller = 'controller\\' . $this->area . '\\' . $this->controller . 'Controller';

        $this->validateController();

        $this->runcontroller = new $this->runcontroller();

        //Run function validate controller
        $this->validateAction();

        $act = $this->action;

        $this->runcontroller->$act();
    }

    private function _cleanString() {
        if (isset($_POST)) {
            foreach ($_POST as $ind => $val) {
                $_POST[$ind] = $this->_rulesString($val);
            }
        }
        if (isset($_GET)) {
            foreach ($_GET as $ind => $val) {
                $_GET[$ind] = $this->_rulesString($val);
            }
        }
    }

    private function _rulesString($value) {
        $search = array(
            'INSERT', 'insert',
            'UPDATE', 'update',
            'DELETE', 'delete',
            'SELECT', 'select',
            'FROM', 'from',
            'WHERE', 'where'
        );
        $string = $value;
        $string = str_replace("'", "´", $string);
        $string = str_replace($search, "", $string);
        return $string;
    }
}