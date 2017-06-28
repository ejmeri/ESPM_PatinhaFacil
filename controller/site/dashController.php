<?php

namespace controller\site;

use lib\Controller;
use helper\Session;
use model\pessoa\PessoaModel;
use api\apiAnimal;

class dashController extends Controller {
    public function index(){
        $this->layout = '_layoutdash';
        
        $apiAnimal = new apiAnimal();



        $this->dados = array(
            'estados' => $apiAnimal->GetAnimalByUF(),
            'elmeri' => $elemri
        );

        $this->View();
    }
    public function email($value='')
    {
        $this->layout = '_email';
        $this->View();
    }
}