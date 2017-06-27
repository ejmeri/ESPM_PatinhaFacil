<?php


namespace model\pessoa;

use object\PessoaAnimal;
use object\Pessoa;
use object\TipoPessoa;
use lib\Model;
use helper\CpfCnpj;


class PessoaModel extends Model {
    public function GetbyId(Pessoa $obj)
    {
        return $this->db->First($this->db->Select("SELECT * FROM pessoa WHERE id = '{$obj->Id}'"));  
    }
    public function getlist()
    {
        return $this->db->Select("SELECT * FROM pessoa");
    }
    public function GetTipoPessoaByName(TipoPessoa $obj)
    {
       return $this->db->First($this->db->Select("SELECT * from tipopessoa where nome = '{$obj->Nome}'"));
    }
    public function Save(Pessoa $obj)
    {
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'pessoa');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'pessoa');
        }
    }
    public function SaveEndereco(Pessoa $obj)
    {
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'pessoa');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'pessoa');
        }
    }
    public function GetEnderecoByPessoaId(PessoaAnimal $obj)
    {
        return $this->db->First($this->db->Select("SELECT a.Logradouro, a.Cep, b.Numero 'Ddd', b.Regiao, c.Nome 'Estado' from endereco a join ddd b on 
        a.dddid = b.id join estado c on
        b.estadoid = c.id where a.pessoaid = '{$obj->PessoaId}'"));
    }
    public function GetEstado()
    {
        return  $this->db->Select("SELECT * FROM estado order by nome");
    }
    public function GettDDDByUF(Estado $obj)
    {
        return  $this->db->Select("select a.Id, a.Numero, a.Regiao, b.Sigla from ddd a join estado b on
                a.estadoid = b.id where b.sigla = {$obj->Sigla} order by a.Numero");
    }
}