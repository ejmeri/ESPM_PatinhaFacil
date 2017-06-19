<?php

namespace controller\site;

use object\Telefone;
use object\Pessoa;
use object\Email;
use object\Usuario;
use object\Endereco;
use helper\Session;
use lib\Controller;
use api\apiUsuario;
use api\apiPessoa;
use model\usuario\UsuarioModel;
use model\pessoa\PessoaModel;
use model\email\EmailModel;
use model\telefone\TelefoneModel;
use model\animal\AnimalModel;

class pessoaController extends Controller
{
    public function index()
    {
        new Session();

        $this->title .= 'Meu Perfil';
        $this->Load();
        $this->View();
    }
    public function general()
    {
        $PessoaModel = new PessoaModel();
        $Pessoa = new Pessoa('POST', 'Pessoa');
        
        $retorno = $PessoaModel->Save($Pessoa);

        $this->Load();
        $this->PartialView();
    }
    public function email($value = '')
    {
        // error_reporting(!E_NOTICE);
        
        new Session();
        
        $Pessoa = new Pessoa();
        $Pessoa->Id = $_SESSION['PessoaId'];
        $Email = new Email();
        $EmailModel = new EmailModel();
        
        $Email->Id = $this->getParams(0);
        $Email->PessoaId = $Pessoa->Id;

        
        if ($_POST['save']) {
            $NewEmail = new Email('POST', 'Email');
            $NewEmail->PessoaId = $Pessoa->Id;
            $retorno = $EmailModel->Save($NewEmail);
        }


        $this->dados = array(
            'emails' => $EmailModel->GetbyPessoaId($Email),
            'email' => $EmailModel->GetbyId($Email),
            'tipoemail' => $EmailModel->GetTipo()
        );

        $this->PartialView();
    }
    public function endereco()
    {
        // error_reporting(!E_NOTICE);
        
        new Session();
        
        $modelTelefone = new TelefoneModel();
        $Pessoa = new Pessoa();
        $Endereco = new Endereco();


        $Pessoa->Id = $_SESSION['PessoaId'];
                
        $Endereco->Id = $this->getParams(0);
        $Endereco->PessoaId = $Pessoa->Id;

        if ($_POST['save']) {
            $apiPessoa = new apiPessoa();           
            $NewEndereco = new Endereco('POST', 'Endereco');

            $array = array(
                'cep' => $_POST['CEP'],
                'logradouro' => $_POST['logradouro'],
                'bairro' => $_POST['bairro'],
                'cidade' => $_POST['cidade'],
                'uf' => $_POST['uf']
            );

            $NewEndereco->JsonEndereco = json_encode($array, JSON_UNESCAPED_UNICODE);
            $NewEndereco->PessoaId = $Pessoa->Id;
            $retorno = $apiPessoa->SaveEndereco($NewEndereco);
        }


        $endereco = $modelTelefone->GetEnderecoFullByPessoaId($Endereco);
        $deserialize = json_decode($json = $endereco['JsonEndereco']);

        $endereco['Logradouro'] = $deserialize->logradouro;
        $endereco['Bairro'] = $deserialize->bairro;
        $endereco['Cidade'] = $deserialize->cidade;
        $endereco['UF'] = $deserialize->uf;
        $endereco['CEP'] = $deserialize->cep;
        
        $this->dados = array(
                'tipoendereco' => $modelTelefone->GetTipoEndereco(),
                'endereco' => $endereco
        );
        
        $this->PartialView();  
    }
    public function acesso($value = '')
    {
        $apiUsuario = new apiUsuario();

        $apiUsuario->EditAcesso();
        
        $this->Load();
        $this->PartialView();
    }
    public function telefone($value = '')
    {
        new Session();
        // error_reporting(!E_NOTICE);
        $Pessoa = new Pessoa();

        $Pessoa->Id = $_SESSION['PessoaId'];

        $Telefone = new Telefone();
        $Telefone->Id = $this->getParams(0);
        $Telefone->PessoaId = $Pessoa->Id;

        $model = new PessoaModel();
        $modelTelefone = new TelefoneModel();

        

        if ($_POST['save']) {
            $NewTel = new Telefone('POST', 'Telefone');
            $NewTel->PessoaId = $Pessoa->Id;
            $retorno = $modelTelefone->Save($NewTel);
        }


        $this->dados = array(
            'telefones' => $modelTelefone->GetbyPessoaId($Telefone),
            'telefone' => $modelTelefone->GetById($Telefone),
            'tipotelefone' => $modelTelefone->GetTipo(),
            'ddd' => $modelTelefone->GetDdd()
        );
        
        $this->PartialView();
    }
    private function Load()
    {
        $Usuario = new Usuario();
        $Telefone = new Telefone();
        $Email = new Email();
        $Pessoa = new Pessoa();
        $Endereco = new Endereco();
        
        $Pessoa->Id = $_SESSION['PessoaId'];
        $Email->PessoaId = $Pessoa->Id;
        $Telefone->PessoaId = $Pessoa->Id;
        $Usuario->PessoaId = $Pessoa->Id;
        $Endereco->PessoaId = $Pessoa->Id;

        $model = new PessoaModel();
        $UsuarioModel = new UsuarioModel();
        $EmailModel = new EmailModel();
        $modelTelefone = new TelefoneModel();
        $modelAnimal = new AnimalModel();
        
        $endereco = $modelTelefone->GetEnderecoFullByPessoaId($Endereco);
        $deserialize = json_decode($json = $endereco['JsonEndereco']);

        $endereco['Logradouro'] = $deserialize->logradouro;
        $endereco['Bairro'] = $deserialize->bairro;
        $endereco['Cidade'] = $deserialize->cidade;
        $endereco['UF'] = $deserialize->uf;
        $endereco['CEP'] = $deserialize->cep;

        $this->dados = array(
            'dados' => $model->GetbyId($Pessoa),
            'user' => $UsuarioModel->GetByPessoaId($Usuario),
            'emails' => $EmailModel->GetbyPessoaId($Email),
            'email' => $EmailModel->GetbyId($Email),
            'tipoemail' => $EmailModel->GetTipo(),
            'telefones' => $modelTelefone->GetbyPessoaId($Telefone),
            'tipotelefone' => $modelTelefone->GetTipo(),
            'ddd' => $modelTelefone->GetDdd(),
            'tipoendereco' => $modelTelefone->GetTipoEndereco(),
            'enderecos' => $modelTelefone->GetEnderecoByPessoaId($Endereco),
            'endereco' => $endereco,
            'pets' => $modelAnimal->GetByPessoaId($Pessoa)
        );
    }
}
