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
    public function GetList(Usuario $obj) 
    {
        return $this->db->Select("select login from usuario where login like '{$obj->Login}%'");
    }
    public function Enter(Usuario $obj) 
    {   
        return $this->db->First($this->db->Select("SELECT Id, Login, Senha, PessoaId FROM usuario WHERE login = '{$obj->Login}'"));
    }
    public function Close()
    {
        // fechar a sessão aqui
        unset($_SESSION['PessoaId']);
    }
    public function SendEmail(Usuario $obj)
    {
        $Email = new Email();
        $Usuario =  $this->db->First($this->Select("select * from usuario where login = '{$obj->Login}'"));

        $senha = "2626+65265+";

        $retorno = $Email->Send('elmerisilva@hotmail.com', 'Nova senha - Pet fácil', 'Olá, '.$Usuario['Login'].', aqui está sua nova senha: '.$senha);


        if($retorno) echo $Usuario['Login'].', sim';
        else echo $Usuario['Login'].',fail <br>'. json_encode($Usuario);

    }
}