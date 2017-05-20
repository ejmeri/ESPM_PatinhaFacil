<?php

namespace object;

use lib\Object;

class Email extends Object {
    public $Id = 0;
    public $Nome;
    public $TipoEmailId;
    public $PessoaId;
    public $DtInclusao = null;
    public $DtAtualizacao = null;
}

?>