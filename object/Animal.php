<?php

namespace object;

use lib\Object;

class Animal extends EntityBase
{
    public $Nome;
    public $DtNascimento;
    public $Peso;
    public $Descricao;
    public $Adotado = '0';
    public $RacaId;
    public $PelagemId;
    public $GeneroId;
    public $PorteId;
    public $AreaId;
    public $Castrado;
}

?>