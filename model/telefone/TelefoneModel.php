<?php

namespace model\telefone;

use object\Telefone;
use object\Endereco;
use object\Estado;
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
            $obj->Id = 0;
            return $this->db->Insert($obj, 'endereco');
        } else {
            return $this->db->Update($obj, array('Id'=>$obj->Id), 'endereco');
        }
    }
    public function GetbyPessoaId(Telefone $obj)
    {
        return $this->db->Select("SELECT a.Id, a.Numero, TipoTelefoneId FROM telefone a WHERE pessoaid = '{$obj->PessoaId}'");
    }
        public function GetFirstbyPessoaId(Telefone $obj)
    {
        return $this->db->First($this->db->Select("SELECT Id, Numero, TipoTelefoneId FROM telefone WHERE pessoaid = '{$obj->PessoaId}'"));
    }
    public function GetbyId(Telefone $obj)
    {
        return $this->db->First($this->db->Select("SELECT Id, Numero, TipoTelefoneId FROM telefone WHERE id = '{$obj->Id}'"));
    }
    public function GetTipo()
    {
        return $this->db->Select("SELECT Id, Nome FROM tipotelefone order by nome");
    }
    public function GetDdd()
    {
        return $this->db->Select("SELECT Id, Regiao, Numero FROM ddd order by numero");
    }
    public function GetDddPessoa(\object\Ddd $obj)
    {
        return $this->db->Select("SELECT * FROM ddd where id = {$obj->Id} order by numero");
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
       return $this->db->First($this->db->Select("SELECT a.*, c.Id 'EstadoId' from endereco a join ddd b on
       a.dddid = b.id join estado c on
       b.estadoid = c.id where a.PessoaId = '{$obj->PessoaId}'"));
    }
    public function GetUF()
    {
        return $this->db->Select('SELECT Id, Nome, Sigla from estado order by nome');
    }
    public function GettDDDByUF(Estado $obj)
    {
        return  $this->db->Select("select a.Id, a.Numero, a.Regiao, b.Sigla from ddd a join estado b on
                a.estadoid = b.id where b.sigla = '{$obj->Sigla}' order by a.Numero");
    }
    public function GettDDDByUFId(\object\Ddd $obj)
    {
         return $this->db->Select("SELECT * from ddd where estadoid = {$obj->EstadoId} order by numero");
    }
}
