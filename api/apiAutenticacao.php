<?php

namespace api;


use helper\sendmail\HtmlEmail;
use object\Usuario;
use object\Autenticacao;
use object\Login;
use helper\sendmail\sendEmail;
use helper\GerarHash;
use helper\Database;
use model\autenticacao\AutenticacaoModel;
use model\usuario\UsuarioModel;

    Class apiAutenticacao extends Database
    {
        public function Autenticacao(Autenticacao $obj)
        {
            $html = new HtmlEmail();
            $SendEmail = new sendEMail();

            $AutenticacaoModel = new AutenticacaoModel();
            $UsuarioModel = new UsuarioModel();
            $Usuario = new Usuario();
            
            $GerarHash = new GerarHash();

            while (true) {
                $hash = $GerarHash->HashAuth();
                $obj->Nome = $hash;
                
                if(empty($AutenticacaoModel->GetByNome($obj))) break;
            }
            
            $obj->Autenticado = 0;
            $retorno = $AutenticacaoModel->Save($obj);

            $Usuario->PessoaId = $obj->PessoaId;

            $Usuario = $UsuarioModel->GetByPessoaId($Usuario);
            
            // print_r($Email);

            // $message = $html->GetHtmlAuth($Email['Nome'], $obj->Nome);
            //Add the message header
            $message = file_get_contents('content/site/shared/emails/header-email.html');
            $message .= file_get_contents('content/site/shared/emails/_bem-vindo.html');
            $message .= file_get_contents('content/site/shared/emails/footer-email.html');

            $replacements = array(
                '({name})' => $Usuario['Login'],
                '({hash})' => $obj->Nome
            );
            $message = preg_replace( array_keys( $replacements ), array_values( $replacements ), $message );
            
            return $SendEmail->Send($Usuario['Login'], 'Autenticação da sua conta', $message);

        }
        public function Validar(Autenticacao $obj)
        {
            $AutenticacaoModel = new AutenticacaoModel();

            $obj = $AutenticacaoModel->GetByNome($obj);

            if($obj['Autenticado'] == 1) return true;
        }
        public function ValidarByPessoaId(Autenticacao $obj)
        {
            $AutenticacaoModel = new AutenticacaoModel();

            $obj = $AutenticacaoModel->GetByPessoaId($obj);

            if($obj['Autenticado'] == 1)
            {
                return true;
            }
            else
            {
                return false;
            } 
        }
        public function Autenticar(Autenticacao $obj)
        {
            $AutenticacaoModel = new AutenticacaoModel();

            $array = $AutenticacaoModel->GetByNome($obj);
            
            $obj->Autenticado = 1;
            $obj->Id = $array['Id'];
            $obj->Nome = $array['Nome'];
            $obj->PessoaId = $array['PessoaId'];

            $retorno = $AutenticacaoModel->Save($obj);

            return $retorno['sucess'];
        }
    }

?>
