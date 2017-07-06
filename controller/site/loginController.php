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
            error_reporting(!E_NOTICE);

            $this->layout = "_layoutlogin";
            $this->title .= "Login";

            $goto = $this->getParams(0);

            if(isset($goto))
                $Page = $this->getParams(1).'/'.$this->getParams(2).'/'.$this->getParams(3);

            $AnimalModel = new \model\animal\AnimalModel;
            $TelfoneModel = new \model\telefone\TelefoneModel();
            

            if(isset($_POST['enter']))
            {   
                $apiUsuario = new apiUsuario();
                $this->PartialResultView($apiUsuario->Enter(new Usuario('POST', 'Usuario'), $Page));
            }
            else if(isset($_POST['button']))
            {
                $Hash = new GerarHash();
                $apiAutenticacao = new apiAutenticacao();
                $PessoaModel = new PessoaModel();
                // $EmailModel = new EmailModel();
                $UsuarioModel = new UsuarioModel();

                $Autenticacao = new Autenticacao();
                $Pessoa = new Pessoa('POST', 'Pessoa');                
                $Usuario = new Usuario('POST', 'Usuario');
                $Telefone = new \object\Telefone('POST', 'Telefone');
                // $Email = new Email('POST', 'Email');
        
                $retorno = $PessoaModel->Save($Pessoa);
                $Pessoa->Id = $retorno['Identity'];

                $Telefone->PessoaId = $Pessoa->Id;
                $Usuario->PessoaId = $Pessoa->Id;
                // $Email->PessoaId = $Pessoa->Id;
                $Autenticacao->PessoaId = $Pessoa->Id;
                
                // $retornoEmail = $EmailModel->Save($Email);
                $retornoTelefone = $TelfoneModel->Save($Telefone);
                $Usuario->Senha = $Hash->Hash($Usuario->Senha);
                $retornoUsuario = $UsuarioModel->Save($Usuario);
                $retornoAutenticacao = $apiAutenticacao->Autenticacao($Autenticacao);
                                
                if($retorno['sucess'] && $retornoUsuario['sucess'] && $retornoTelefone['sucess'] && $retornoAutenticacao == 'ok') {
                    
                    if($Page != ''){

                        $retorno = array(
                            'Status' => true,
                            'Do' => $Page,
                            'Mensagem' => ''
                        );

                        $_SESSION['PessoaId'] = $Pessoa->Id;

                        $retorno =  json_encode($retorno,  JSON_UNESCAPED_UNICODE);

                    }
                    else {

                        $retorno = array(
                            'Status' => true,
                            'Do' => '',
                            'Mensagem' => '<div class="alert alert-info alert-dismissable fade in">
                                <a href="#" class="close" style="padding-right: 20px" data-dismiss="alert" aria-label="close">&times;</a>
                                <h2 style="color:#000">Parabéns '.$Pessoa->Nome.', você foi cadastrado em nosso sistema!</h2>
                                <p style="color:red">Enviamos um e-mail para você confirmar a sua conta, após confirmação o seu acesso estará liberado!</p>
                            </div>'
                        );

                        $retorno =  json_encode($retorno,  JSON_UNESCAPED_UNICODE);
                        
                    }
                }
                else {

                     $retorno = array(
                            'Status' => false,
                            'Do' => '',
                            'Mensagem' => $retorno['feedback']." <br> ".$retornoUsuario['feedback']."<br>".$retornoAutenticacao
                     );
                     
                     $retorno = json_encode($retorno,  JSON_UNESCAPED_UNICODE);

                }

                $this->PartialResultView($retorno);
                
            }
            else 
            { 
                $this->dados = array(
                    'redirect' => $goto.'/'.$Page,
                    'lista' => $AnimalModel->GetList(),
                    'tipotelefone' => $TelfoneModel->GetTipo()
                );

               $this->View();
            }

        }
        public function Close() 
        {
            $api = new apiUsuario();
            $api->Close();
        }
        public function novo()
        {
            $this->title = "Novo Cadastro";
            $this->layout = "_layoutlogoff";

            $EmailModel = new EmailModel();

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
                // $Email = new Email('POST', 'Email');
        
                $retorno = $PessoaModel->Save($Pessoa);
                $Pessoa->Id = $retorno['Identity'];

                $Usuario->PessoaId = $Pessoa->Id;
                // $Email->PessoaId = $Pessoa->Id;
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
            $this->layout = "_layoutlogin";

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
        public function ValidarPassword()
        {
            $Usuario = new Usuario();
            $Usuario->Senha = $this->getParams(0);

            $api = new apiUsuario();
            
            if(strlen($Usuario->Senha) >= 1) $this->PartialResultView($api->ValidarSenha($Usuario));
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