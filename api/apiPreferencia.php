<?php

namespace api;

use helper\Session;
use helper\CpfCnpj;
use lib\Controller;
use object\Pessoa;
use object\Usuario;
use model\preferencia\PreferenciaModel;
use model\pessoa\PessoaModel;
use model\email\EmailModel;
use model\telefone\TelefoneModel;

Class apiPreferencia
{
    public function GetList(\object\Preferencia $obj, $limit)
    {
        $PreferenciaModel = new PreferenciaModel();

        return $PreferenciaModel->GetList($obj, $limit);
    }
}

?>
