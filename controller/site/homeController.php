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
        $this->title = 'Home';

        $AnimalModel = new \model\animal\AnimalModel;

        $this->dados = array(
            'lista' => $AnimalModel->GetList()
        );

        $this->view();
    }
}