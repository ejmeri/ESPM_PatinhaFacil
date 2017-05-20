<?php

namespace controller\site;

use lib\Controller;
use helper\Session;

class homeController extends Controller {
    public function __construct(){
        parent::__construct();

        new Session();
        $this->layout = '_layouthome';
    }
    public function index(){

         new Session();
        // $logado = $_SESSION['login'];
        $this->title = 'Home';
        $this->view();
    }
}