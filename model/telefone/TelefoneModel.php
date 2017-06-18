<?php

namespace model\telefone;

use object\Telefone;
use object\Endereco;
use lib\Model;

class TelefoneModel extends Model
{
    public function Save(Telefone $obj)
    {
        if (empty($obj->Id)) {
            return $this->db->Insert($obj, 'telefone');
        } else {
            return $this->db->Update($obj, array('Id'=>$obj->Id), 'telefone');
        }
    }
    public function SaveEndereco(Endereco $obj)
    {
        if (empty($obj->Id)) {
            return $this->db->Insert($obj, 'endereco');
        } else {
            return $this->db->Update($obj, array('Id'=>$obj->Id), 'endereco');
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
    public function GetTipoEndereco()
    {
        return $this->db->Select('SELECT Id, Nome from tipoendereco order by nome');
    }
    public function GetEnderecoByPessoaId(Endereco $obj)
    {
        return $this->db->Select("SELECT Id, Nome from endereco where pessoaid = '{$obj->PessoaId}'");
    }
    public function GetEnderecoById(Endereco $obj)
    {
       return $this->db->First($this->db->Select("SELECT * from endereco where id = '{$obj->Id}'"));
    }
    public function GetEnderecoFullByPessoaId(Endereco $obj)
    {
       return $this->db->First($this->db->Select("SELECT * from endereco where PessoaId = '{$obj->PessoaId}'"));
    }
    public function GetUF()
    {
        return $this->db->Select('SELECT Nome, Sigla from estado order by nome');
    }
}
