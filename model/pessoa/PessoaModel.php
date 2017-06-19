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
    public function getlist(){
        return $this->Select("SELECT * FROM pessoa");
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
        return $this->db->First($this->db->Select("SELECT * from endereco where pessoaid = '{$obj->PessoaId}'"));
    }
}