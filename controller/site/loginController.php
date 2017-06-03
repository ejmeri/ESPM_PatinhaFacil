<?php

namespace controller\site;

use helper\Session;
use helper\GerarHash;
use lib\Controller;
use object\Usuario;
use object\Pessoa;
use object\Email;
use object\Autenticacao;
use model\usuario\UsuarioModel;
use model\pessoa\PessoaModel;
use model\email\EmailModel;
use api\apiPessoa;
use api\apiUsuario;
use api\apiAutenticacao;

    Class loginController Extends Controller 
    {
        public function index()
        {    
            $this->layout = "_layoutlogin";
            $this->title = "Login";

            $EmailModel = new EmailModel();

            $this->dados = array(
                'list' => $EmailModel->GetTipo()
            );

            if(isset($_POST['enter']))
            {
                $apiUsuario = new apiUsuario();
                $this->PartialResultView($apiUsuario->Enter(new Usuario('POST', 'Usuario')));
            }
            else if(isset($_POST['button']))
            {
                 $Hash = new GerarHash();
                $apiAutenticacao = new apiAutenticacao();
                $PessoaModel = new PessoaModel();
                $EmailModel = new EmailModel();
                $UsuarioModel = new UsuarioModel();

                $Autenticacao = new Autenticacao();
                $Pessoa = new Pessoa('POST', 'Pessoa');                
                $Usuario = new Usuario('POST', 'Usuario');
                $Email = new Email('POST', 'Email');
        
                $retorno = $PessoaModel->Save($Pessoa);
                $Pessoa->Id = $retorno['Identity'];

                $Usuario->PessoaId = $Pessoa->Id;
                $Email->PessoaId = $Pessoa->Id;
                $Autenticacao->PessoaId = $Pessoa->Id;
                
                $retornoEmail = $EmailModel->Save($Email);
                $Usuario->Senha = $Hash->Hash($Usuario->Senha);
                $retornoUsuario = $UsuarioModel->Save($Usuario);
                $apiAutenticacao->Autenticacao($Autenticacao);
                
                
                if($retorno['sucess'] && $retornoEmail['sucess'] && $retornoUsuario['sucess']) {
                    $this->PartialResultView('<h2>Parabéns '.$Pessoa->Nome.', você foi cadastrado em nosso sistema!</h2>
                        <p>Enviamos um e-mail para você confirmar a sua conta, após confirmação o seu acesso estará liberado. <br>Faça seu login <a href="login"> <b>Aqui</b> </a>!</p>');
                }
                else {
                     $this->PartialResultView($retorno['feedback']." <br> ".$retornoEmail['feedback']." <br>".$retornoUsuario['feedback']);
                }
            }
            else 
            { 
               $this->View();
            }

        }
        public function Close() 
        {
            $api = new UsuarioModel();
            $api->Close();
            header('location:' . APP_ROOT . '/login');
        }

        public function novo()
        {
            $this->title = "Novo Cadastro";
            $this->layout = "_layoutlogoff";

            $EmailModel = new EmailModel();

            $this->dados = array(
                'list' => $EmailModel->GetTipo()
            );
            
            if(!isset($_POST['button'])) $this->view();
            else
            {
                $Hash = new GerarHash();
                $apiAutenticacao = new apiAutenticacao();
                $PessoaModel = new PessoaModel();
                $EmailModel = new EmailModel();
                $UsuarioModel = new UsuarioModel();

                $Autenticacao = new Autenticacao();
                $Pessoa = new Pessoa('POST', 'Pessoa');                
                $Usuario = new Usuario('POST', 'Usuario');
                $Email = new Email('POST', 'Email');
        
                $retorno = $PessoaModel->Save($Pessoa);
                $Pessoa->Id = $retorno['Identity'];

                $Usuario->PessoaId = $Pessoa->Id;
                $Email->PessoaId = $Pessoa->Id;
                $Autenticacao->PessoaId = $Pessoa->Id;
                
                $retornoEmail = $EmailModel->Save($Email);
                $Usuario->Senha = $Hash->Hash($Usuario->Senha);
                $retornoUsuario = $UsuarioModel->Save($Usuario);
                $apiAutenticacao->Autenticacao($Autenticacao);
                
                
                if($retorno['sucess'] && $retornoEmail['sucess'] && $retornoUsuario['sucess']) {
                    $this->PartialResultView('<h2>Parabéns '.$Pessoa->Nome.', você foi cadastrado em nosso sistema!</h2>
                        <p>Enviamos um e-mail para você confirmar a sua conta, após confirmação o seu acesso estará liberado. <br>Faça seu login <a href="login"> <b>Aqui</b> </a>!</p>');
                }
                else {
                     $this->PartialResultView($retorno['feedback']." <br> ".$retornoEmail['feedback']." <br>".$retornoUsuario['feedback']);
                }

            }
        }

        public function esqueciasenha()
        {
            $this->title = "Esqueci a senha";
            $this->layout = "_layoutlogoff";

            if(!isset($_POST['button'])) $this->View();
            else
            {
                $api = new apiUsuario();
                $this->PartialResultView($api->SendEmail(new Usuario('POST', 'Usuario')));
            }
        }

        public function Validar()
        {
            $Usuario = new Usuario();
            $Usuario->Login = $this->getParams(0);

            $api = new apiUsuario();
            $this->PartialResultView($api->ValidateLogin($Usuario));
        }
        public function ValidarCpfCnpj()
        {
            $Pessoa = new Pessoa();
            $Pessoa->CpfCnpj = $this->getParams(0);

            $api = new apiPessoa();
            
            if(strlen($Pessoa->CpfCnpj) >= 11) $this->PartialResultView($api->ValidateCpfCnpj($Pessoa));
        }
        public function autenticacao()
        {   
            $this->title = "Autenticação de acesso";
            $this->layout = "_layoutlogoff";

            $apiAutenticacao = new apiAutenticacao();
            $Autenticacao = new Autenticacao();
            $Autenticacao->Nome = $this->getParams(0);

            if($apiAutenticacao->Validar($Autenticacao))
            {
                $this->dados = array(
                    'auth' => 'Autenticado'
                );
            }
            else
            {   
                $apiAutenticacao->Autenticar($Autenticacao);
                 $this->dados = array(
                    'auth' => 'Novo'
                    );
            }


            $this->View();

        }
    }