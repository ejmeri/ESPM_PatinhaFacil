<?php

namespace api;

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

            $message = 'Olá, aqui está o link para voce autenticar a sua conta: <a href="https://localhost:8080/ESPMProducao/login/autenticacao/'.$hash.'">clique aqui</a>';

            $SendEmail->Send($Email['Nome'], 'Autenticacao de conta', $message);


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
