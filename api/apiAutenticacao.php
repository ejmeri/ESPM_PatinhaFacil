<?php

namespace api;


use helper\sendmail\HtmlEmail;
use object\Email;
use object\Autenticacao;
use object\Login;
use helper\sendmail\sendEmail;
use helper\GerarHash;
use helper\Database;
use model\autenticacao\AutenticacaoModel;
use model\email\EmailModel;

    Class apiAutenticacao extends Database
    {
        public function Autenticacao(Autenticacao $obj)
        {
            $html = new HtmlEmail();
            $SendEmail = new sendEMail();

            $AutenticacaoModel = new AutenticacaoModel();
            $EmailModel = new EmailModel();
            $Email = new Email();
            
            $GerarHash = new GerarHash();

            while (true) {
                $hash = $GerarHash->Hash();
                $obj->Nome = $hash;
                
                if(empty($AutenticacaoModel->GetByNome($obj))) break;
            }
            
            $retorno = $AutenticacaoModel->Save($obj);

            $Email->PessoaId = $obj->PessoaId;

            $Email = $EmailModel->GetFirstByPessoaId($Email);
            
            // print_r($Email);

            $message = $html->GetHtmlAuth($Email['Nome'], $obj->Nome);

            $SendEmail->Send($Email['Nome'], 'Autenticação da sua conta', $message);


        }
        public function Validar(Autenticacao $obj)
        {
            $AutenticacaoModel = new AutenticacaoModel();

            $obj = $AutenticacaoModel->GetByNome($obj);

            if($obj['Autenticado'] == 1) return true;
        }
        public function ValidarComLogin(Autenticacao $obj)
        {
            $AutenticacaoModel = new AutenticacaoModel();

            $obj = $AutenticacaoModel->GetByPessoaId($obj);

            if($obj['Autenticado'] == 1) return true;
            else return false;
        }
        public function Autenticar(Autenticacao $obj)
        {
            $AutenticacaoModel = new AutenticacaoModel();

            $array = $AutenticacaoModel->GetByNome($obj);
            
            $obj->Autenticado = true;
            $obj->Id = $array['Id'];
            $obj->Nome = $array['Nome'];
            $obj->PessoaId = $array['PessoaId'];

            $retorno = $AutenticacaoModel->Save($obj);

            return $retorno['sucess'];
        }
    }

?>