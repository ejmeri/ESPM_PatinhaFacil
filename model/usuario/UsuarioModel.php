<?php

namespace model\usuario;

use object\Usuario;
use lib\Model;
use helper\Email;

Class UsuarioModel extends Model {
    public function Save(Usuario $obj){
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'usuario');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'usuario');
        }
    }
    public function GetByPessoaId(Usuario $obj)
    {
        return $this->First($this->db->Select("SELECT Id, Login, Senha from usuario where pessoaid = '{$obj->PessoaId}'"));
    }
    public function GetByLogin(Usuario $obj)
    {
        return $this->First($this->db->Select("SELECT Id, Login, PessoaId from usuario where login = '{$obj->Login}'"));
    }
    public function GetList(Usuario $obj) 
    {
        return $this->db->Select("select login from usuario where login like '{$obj->Login}%'");
    }
    public function Enter(Usuario $obj) 
    {   
        return $this->db->First($this->db->Select("SELECT Id, Login, Senha, PessoaId FROM usuario WHERE login = '{$obj->Login}'"));
    }
}