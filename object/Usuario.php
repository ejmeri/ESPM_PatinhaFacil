<?php 

namespace object;
use lib\Object;

class Usuario extends Object
{
    public $Id = 0;
    public $Login;
    public $Senha;
    public $PessoaId;
    public $DtInclusao;
    public $DtAtualizacao;
}

?>
