<?php

namespace controller\admin;

// require('lib/Controller.php');

use lib\Controller;

class homeController extends Controller {
    public function __construct(){
        parent::__construct();

        $this->layout = '_layout';
        $this->title = "Admin - Home";
    }
    public function index(){
        $this->view();
    }
}