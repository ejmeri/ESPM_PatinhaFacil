<?php

namespace controller\site;

use lib\Controller;
use helper\Session;
use model\pessoa\PessoaModel;

class dashController extends Controller {
    public function index(){
        $this->layout = '_layoutdash';
        
        $PessoaModel = new PessoaModel();
        
        $this->dados = array(
            'estados' => $PessoaModel->GetEstado()
        );


        $this->View();
    }
    public function email($value='')
    {
        $this->layout = '_email';
        $this->View();
    }
}