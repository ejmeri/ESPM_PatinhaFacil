<?php

namespace controller\site;

use lib\Controller;
use helper\Session;
use model\pessoa\PessoaModel;
use api\apiAnimal;

class dashController extends Controller {
    public function index()
    {
        $this->layout = '_layoutdash';  
        $apiAnimal = new apiAnimal();

        $this->dados = array(
            'estados' => $apiAnimal->GetAnimalByUF(),
            'achadosperdidos' =>$apiAnimal->GetAnimalAchadosPerdidosByUfNome(),
        );

        $this->View();
    }
    public function doacao()
    {
        $this->layout = '_layoutlogoff';
        $this->View();
    }
    public function email($value='')
    {
        $this->layout = '_email';
        $this->View();
    }
}