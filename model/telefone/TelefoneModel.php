<?php

namespace model\telefone;

use object\Telefone;
use lib\Model;

class TelefoneModel extends Model 
{
    public function Save(Telefone $obj)
    {
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'telefone');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'telefone');
        }
    }
    public function GetbyPessoaId(Telefone $obj)
    {
        return $this->db->Select("SELECT a.Id, a.Numero, TipoTelefoneId, DddId, b.Numero 'DDD' FROM telefone a join ddd b on a.dddid = b.id WHERE pessoaid = '{$obj->PessoaId}'");
    }
    public function GetbyId(Telefone $obj)
    {
        return $this->db->First($this->db->Select("SELECT Id, Numero, TipoTelefoneId, DddId FROM telefone WHERE id = '{$obj->Id}'"));
    }
    public function GetTipo()
    {
        return $this->db->Select("SELECT Id, Nome FROM tipotelefone order by nome");
    }
    public function GetDdd()
    {
        return $this->db->Select("SELECT Id, Nome, Numero FROM ddd order by nome");
    }
}