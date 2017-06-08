<?php

namespace api;

use object\Usuario;
use object\Email;
use object\Autenticacao;
use helper\sendmail\sendEmail;
use helper\sendmail\HtmlEmail;
use helper\Database;
use helper\GerarHash;
use model\usuario\UsuarioModel;
use model\email\EmailModel;
use api\apiAutenticacao;

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
        $Hash = new GerarHash();
        $UsuarioModel = new UsuarioModel();
        $retorno = $UsuarioModel->Enter($obj);

        // if(!isset($retorno)) echo "Login não encontrado.";
        $decrypt = $Hash->Unhash($retorno['Senha']);
        if($decrypt == $obj->Senha) 
        {   
            $apiAutenticacao = new apiAutenticacao();
            $Autenticacao = new Autenticacao();
            $Autenticacao->PessoaId = $retorno['PessoaId'];
            
            if(($apiAutenticacao->ValidarByPessoaId($Autenticacao) == false)) echo "O seu acesso não foi autenticado!";

            else
            {
                //abrir sessão                
                $_SESSION['PessoaId'] = $retorno['PessoaId'];
                echo 'OK';
            }
        }
        else echo "Login e/ou senha incorretos.";
        
    }
    public function Close()
    {
        // fechar a sessão aqui
        unset($_SESSION['PessoaId']);
    }
    public function EditAcesso()
    {
        $UsuarioModel = new UsuarioModel();
        $Usuario = new Usuario('POST', 'Usuario');
        
        $Hash = new GerarHash();
        $PassHashed = $Hash->Hash($Usuario->Senha);
        $Usuario->Senha = $PassHashed;


        $Usuario->PessoaId = $_SESSION['PessoaId'];
        $retorno = $UsuarioModel->Save($Usuario);

        // echo print_r($Usuario);

    }
    public function SendEmail(Usuario $obj)
    {
        $Email = new Email();

        $html = new HtmlEmail();
        $SendEmail = new sendEMail();

        $Hash = new GerarHash();
        $EmailModel = new EmailModel();
        $UsuarioModel = new UsuarioModel();
        $NewPassword = $Hash->RandomNumbers();
        $PassHashed = $Hash->Hash($NewPassword);

      
        $retornoUsuario = $UsuarioModel->GetByLogin($obj);
        $obj->Senha = $PassHashed;
        $obj->PessoaId = $retornoUsuario['PessoaId'];
        $obj->Id = $retornoUsuario['Id'];

        
        $UsuarioModel->Save($obj);

        $Email->PessoaId = $retornoUsuario['PessoaId'];
        
        $retornoEmail = $EmailModel->GetFirstbyPessoaId($Email);

        $message = file_get_contents('content/site/shared/emails/header-email.html');
        $message .= file_get_contents('content/site/shared/emails/_recuperarsenha.html');
        $message .= file_get_contents('content/site/shared/emails/footer-email.html');

        $replacements = array(
            '({name})' => $retornoUsuario['Login'],
            '({senha})' => $NewPassword
        );

        $message = preg_replace( array_keys( $replacements ), array_values( $replacements ), $message );
        
        $retorno = $SendEmail->Send($retornoEmail['Nome'], 'Recuperação de senha', $message);

        if($retorno == 'ok')
        {
            echo 'Sua senha foi recuperada, enviamos para o seu e-mail: '.$retornoEmail['Nome'].'.';
        }
        else
        {
            echo 'Ocorreu um erro: '.$retorno;
        }
    }
    public function ValidarSenha(Usuario $obj)
    {
       $Hash = new GerarHash();
       $PassHashed = $Hash->Hash($obj->Senha);
       $PessoaId = $_SESSION['PessoaId'];

       $query = $this->Select("select login from usuario where senha = '{$PassHashed}' and pessoaid = '{$PessoaId}'");

       $array = (array) $query;

       if(count($array) == 0) echo "Senha incorreta.";
    }
}

?>
