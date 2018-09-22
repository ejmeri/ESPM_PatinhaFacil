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

       if(count($array) > 0) echo 'E-mail indisponível. <a href="login/esqueciasenha">Recupar senha</a>';
    //    else if (count($array) == 0)
    //    {
    //         $validateemail = file_get_contents('https://api.email-validator.net/api/verify?EmailAddress='.$obj->Login.'&APIKey=ev-42f1cd54afea3976d23c11a12ee6e30f'); 
    //         $validateemail = json_decode($validateemail);

    //         if($validateemail->status != '200') echo 'E-mail inválido/Inexistente';
    //    }
    }
    public function Enter(Usuario $obj, $redirect = '')
    {   
        $Hash = new GerarHash();
        $UsuarioModel = new UsuarioModel();
        $retorno = $UsuarioModel->Enter($obj);

        
        // if(!isset($retorno)) echo "Login não encontrado.";
        // $decrypt = $Hash->Unhash($retorno['Senha']);
        if($retorno['Senha'] == $Hash->HashPass($obj->Senha)) 
        {   
            $apiAutenticacao = new apiAutenticacao();
            $Autenticacao = new Autenticacao();
            $Autenticacao->PessoaId = $retorno['PessoaId'];
            
            if(($apiAutenticacao->ValidarByPessoaId($Autenticacao) == false)){
                 
                 $retornojson = array(
                    'Status' => false,
                    'Do' => '',
                    'Mensagem' => 'O sua conta não foi confirmada!'
                );
                 
            }
            else if(($redirect != '')){
                
                //abrir sessao
                $_SESSION['PessoaId'] = $retorno['PessoaId'];
                
                $retornojson = array(
                    'Status' => true,
                    'Do' => $redirect,
                    'Mensagem' => ''
                );

            } 
            else
            {
                //abrir sessão                
                $_SESSION['PessoaId'] = $retorno['PessoaId'];
                
                $retornojson = array(
                    'Status' => true,
                    'Do' => 'home',
                    'Mensagem' => ''
                );

            }
        }
        else
        {
            $retornojson = array(
                'Status' => false,
                'Do' => '',
                'Mensagem' => 'E-mail e/ou senha incorreto.'
            );
        }


         echo json_encode($retornojson);
        
    }
    public function Close()
    {
        // fechar a sessão aqui
        unset($_SESSION['PessoaId']);
        header('location:' . APP_ROOT);

    }
    public function EditAcesso()
    {   

        $Hash = new GerarHash();
        $UsuarioModel = new UsuarioModel();
        $Usuario = new Usuario('POST', 'Usuario');


        $OldPassHash = $Hash->HashPass($_POST['senhaantiga']);

        $Usuario->PessoaId = $_SESSION['PessoaId'];
        $OldUser = $UsuarioModel->GetByPessoaId($Usuario);

        if($OldPassHash != $OldUser['Senha'])
        {
            $retornojson = array(
                'Status' => true,
                'Do' => '',
                'Mensagem' => 
                    '<div class="alert alert-danger alert-dismissable fade in">
                        <a href="#" class="close" style="padding-right: 20px" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Sua senha antiga está incorreta!</strong>
                    </div>'
            );
        }
        else 
        {
            $PassHashed = $Hash->HashPass($Usuario->Senha);
            $Usuario->Senha = $PassHashed;

            $Usuario->Id = $OldUser['Id'];
            $Usuario->DtInclusao = $OldUser['DtInclusao'];
            $Usuario->Login = $OldUser['Login'];

            $retorno = $UsuarioModel->Save($Usuario);

            if($retorno['sucess'])
            {
                $retornojson = array(
                'Status' => true,
                'Do' => '',
                'Mensagem' => 
                    '<div class="alert alert-success alert-dismissable fade in">
                        <a href="#" class="close" style="padding-right: 20px" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>A sua senha foi alterada!</strong> <br>
                            <a href="pessoa" class=""> Voltar </a>
                        </div>'
            );
            }
            else 
            {
                $retornojson = array(
                'Status' => true,
                'Do' => '',
                'Mensagem' => '<div class="alert alert-info alert-dismissable">
                                    <h2>'.$retorno['feedback'].'</h2>
                               </div>'
                );
            }
        }
        
        return $retornojson;

    }
    public function EsqueciASenha(Usuario $obj)
    {
        $UsuarioModel = new UsuarioModel();        
        $retornoUsuario = $UsuarioModel->GetByLogin($obj);


        if($retornoUsuario['Id'] > 0)
        {
          
            $html = new HtmlEmail();
            $SendEmail = new sendEMail();

            $Hash = new GerarHash();
            $EmailModel = new EmailModel();
            $NewPassword = $Hash->RandomNumbers();
            $PassHashed = $Hash->HashPass($NewPassword);

      
        
            $obj->Senha = $PassHashed;
            $obj->PessoaId = $retornoUsuario['PessoaId'];
            $obj->Id = $retornoUsuario['Id'];

        
            $UsuarioModel->Save($obj);

            // $Email->PessoaId = $retornoUsuario['PessoaId'];
            
            // $retornoEmail = $EmailModel->GetFirstbyPessoaId($Email);

            $message = file_get_contents('content/site/shared/emails/header-email.html');
            $message .= file_get_contents('content/site/shared/emails/_recuperarsenha.html');
            $message .= file_get_contents('content/site/shared/emails/footer-email.html');

            $replacements = array(
                '({name})' => $retornoUsuario['Login'],
                '({senha})' => $NewPassword
            );

            $message = preg_replace( array_keys( $replacements ), array_values( $replacements ), $message );
            
            $retorno = $SendEmail->Send($retornoUsuario['Login'], 'Recuperação de senha', $message);
         

            if($retorno == 'ok')
            {
                echo 'Sua senha foi recuperada, enviamos para o seu e-mail: '.$retornoUsuario['Login'].'. <br> Verifique sua caixa de spam.';
            }
            else
            {
                echo 'Ocorreu um erro: '.$retorno;
            }
        }
        else 
        {
            echo 'Usuário não encontrado.';
        }
    }
    public function ValidarSenha(Usuario $obj)
    {
       $Hash = new GerarHash();
       $PassHashed = $Hash->HashPass($obj->Senha);
       $PessoaId = $_SESSION['PessoaId'];

       $query = $this->Select("select login from usuario where senha = '{$PassHashed}' and pessoaid = '{$PessoaId}'");

       $array = (array) $query;

       if(count($array) == 0) echo "Senha incorreta.";
    }
}

?>
