<?php

namespace api;

use object\Usuario;
use helper\Database;
use model\usuario\UsuarioModel;

Class apiUsuario extends Database
{
    public function ValidateLogin(Usuario $obj)
    {
       $query = $this->Select("select login from usuario where login = '{$obj->Login}'");

       $array = (array) $query;

       if(count($array) > 0) echo "Login indisponível";

    }
    public function Enter(Usuario $obj)
    {
        $UsuarioModel = new UsuarioModel();

        $retorno = $UsuarioModel->Enter($obj);

        // if(!isset($retorno)) echo "Login não encontrado.";

        if($retorno['Senha'] == $obj->Senha) 
        {   
            //abrir sessão      
            $_SESSION['PessoaId'] = $retorno['PessoaId'];
            echo 'OK';
        }
        else echo "Login e/ou senha incorretos.";
        
    }
}

?>
