<?php

namespace controller\site;

use lib\Controller;
use helper\Session;

class dashController extends Controller {
    public function index(){
        $this->layout = '_layoutdash';
        $this->View();
    }
}