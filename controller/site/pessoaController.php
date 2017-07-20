<?php

namespace controller\site;

use object\Telefone;
use object\Pessoa;
use object\Email;
use object\Usuario;
use object\Endereco;
use object\Estado;
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


        if(isset($_POST['SavePeople'])) 
        {
            $this->general();
        }
        else if(isset($_POST['SaveAcesso'])) 
        {
            $this->acesso();
        }
        else if(isset($_POST['SaveEndereco']))
        {
            $this->endereco();
        }
        else if(isset($_POST['SaveDados']))
        {
            $this->telefone();
        }
        else if (isset($_POST['SavePreference']))
        {
            $this->preference();
        }

        $this->Load();
        $this->View();
    }
    public function alterarsenha() 
    {
        new Session();

        $this->title .= 'Alterar senha';
        $this->layout = '_layout';

        if(!isset($_POST['save'])) $this->View();
        else
        {
            $apiUsuario = new apiUsuario();
            $retorno = $apiUsuario->EditAcesso();

            $this->PartialResultView(json_encode($retorno,  JSON_UNESCAPED_UNICODE));
        }
    }
    public function pets()
    {
        new Session();

        $this->title .= 'Meu Animais';
        
        $this->LoadInfoPets();
        $this->View();
    }
    public function general()
    {
        $PessoaModel = new PessoaModel();
        $Pessoa = new Pessoa('POST', 'Pessoa');
        
        $retorno = $PessoaModel->Save($Pessoa);

        // $this->Load();
        // $this->PartialView();
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
                
        // $Endereco->Id = $this->getParams(0);
        $Endereco->PessoaId = $Pessoa->Id;


        $apiPessoa = new apiPessoa();           
        $NewEndereco = new Endereco('POST', 'Endereco');

        $NewEndereco->PessoaId = $Pessoa->Id;
        $retorno = $apiPessoa->SaveEndereco($NewEndereco);
        


        $endereco = $modelTelefone->GetEnderecoFullByPessoaId($Endereco);
        // $deserialize = json_decode($json = $endereco['JsonEndereco']);

        // $endereco['Logradouro'] = $deserialize->logradouro;
        // $endereco['Bairro'] = $deserialize->bairro;
        // $endereco['Cidade'] = $deserialize->cidade;
        // $endereco['UF'] = $deserialize->uf;
        // $endereco['CEP'] = $deserialize->cep;
        
        // $this->dados = array(
        //         'tipoendereco' => $modelTelefone->GetTipoEndereco(),
        //         'endereco' => $endereco
        // );
        
        // // $this->PartialView();  
    }
    public function acesso($value = '')
    {
        $apiUsuario = new apiUsuario();

        $apiUsuario->EditAcesso();
        
        // $this->Load();
        // $this->PartialView();
    }
    public function telefone($value = '')
    {
        new Session();
        error_reporting(!E_NOTICE);
        $Pessoa = new Pessoa();

        $Pessoa->Id = $_SESSION['PessoaId'];

        $Telefone = new Telefone();
        $Telefone->PessoaId = $Pessoa->Id;

        $model = new PessoaModel();
        $modelTelefone = new TelefoneModel();

        $NewTel = new Telefone('POST', 'Telefone');
        $NewTel->PessoaId = $Pessoa->Id;
        $retorno = $modelTelefone->Save($NewTel);


    }
    public function preference()
    {
        new Session();
        error_reporting(!E_NOTICE);

        $model = new PessoaModel();        
        $Pessoa = new Pessoa();

        $Pessoa->Id = $_SESSION['PessoaId'];

        $Preferencia = new \object\Preferencia('POST', 'Preferencia');
        $Preferencia->PessoaId = $Pessoa->Id;
        
        $retorno = $model->SavePreference($Preferencia);

    }
    private function Load()
    {

        $apiAnimal = new \api\apiAnimal();
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
        
        $Ddd = new \object\Ddd;

        $Ddd->EstadoId = $endereco['EstadoId'];

        if(!isset($Ddd->EstadoId)) $Ddd->EstadoId = 1;

        $pref = $model->GetPreferenceByPessoaId($Pessoa);

        $Especie = new \object\Especie();

        $Especie->Id = $pref['EspecieId'];

        $this->dados = array(
            'dados' => $model->GetbyId($Pessoa),
            'user' => $UsuarioModel->GetByPessoaId($Usuario),
            'emails' => $EmailModel->GetbyPessoaId($Email),
            'email' => $EmailModel->GetbyId($Email),
            'tipoemail' => $EmailModel->GetTipo(),
            'telefone' => $modelTelefone->GetFirstbyPessoaId($Telefone),
            'tipotelefone' => $modelTelefone->GetTipo(),
            'ddd' => $modelTelefone->GettDDDByUFId($Ddd),
            'estados' => $modelTelefone->GetUF(),
            'tipoendereco' => $modelTelefone->GetTipoEndereco(),
            'enderecos' => $modelTelefone->GetEnderecoByPessoaId($Endereco),
            'endereco' => $endereco,
            'estadospet' => $apiAnimal->GetAnimalByUF(),
            'preference' => $pref,
            'especie' => $modelAnimal->getEspecie(),
            'raca' => $modelAnimal->getRacaByEspecieId($Especie),
            'genero' => $modelAnimal->getGenero(),
            'pelagem' => $modelAnimal->getPelagem(),
            'porte' => $modelAnimal->getPorte(),
            'achadosperdidos' =>$apiAnimal->GetAnimalAchadosPerdidosByUfNome()
        );
    }
    private function LoadInfoPets()
    {
        new Session();

        $apiAnimal = new \api\apiAnimal();
        $Pessoa = new Pessoa();     
        $Pessoa->Id = $_SESSION['PessoaId'];

        $model = new PessoaModel();
        $modelAnimal = new AnimalModel();

        $this->dados = array(
            'pets' => $modelAnimal->GetByPessoaId($Pessoa),
            'especie' => $modelAnimal->getEspecie(),
            'genero' => $modelAnimal->getGenero(),
            'pelagem' => $modelAnimal->getPelagem(),
            'porte' => $modelAnimal->getPorte(),
            'area' => $modelAnimal->GetArea(),
            'estadospet' => $apiAnimal->GetAnimalByUF(),
            'achadosperdidos' =>$apiAnimal->GetAnimalAchadosPerdidosByUfNome()
        );
    }
    public function ListaPet()
    {
        // error_reporting(!E_NOTICE);

        new Session();

        $modelAnimal = new AnimalModel();
        $apiPessoa = new apiPessoa();
        $FilterPet = new \object\FilterPet('POST', 'FilterPet');

        $Pessoa = new Pessoa();     
        $Pessoa->Id = $_SESSION['PessoaId'];

        $Area = new \object\Area();   
        $Area->Id = $_POST['AreaId'];

        $this->dados = array(
            'list' => $apiPessoa->ListaPet($FilterPet, $Pessoa, $Area)
        );

  
        $this->PartialView();
    }
    public function DDD()
    {

        $Estado = new Estado();
        $Estado->Sigla = $this->getParams(0);
        
        $model = new TelefoneModel();

        $retorno = $model->GettDDDByUF($Estado);
        $teste= '';
        $html = '<select class="form-control"  id="SelectDDD" name="EnderecoDddId" required>';
        foreach ($retorno as $key => $value) {
           $html .=  
                    '<option value="'.$value['Id'].'"> ('.$value['Numero'].') - '.$value['Regiao'].' </option>';
            
        }
        $html .= '</select>';
        // $this->PartialResultView(json_encode($retorno, 256));
        $this->PartialResultView($html);
    }
    public function DDDByUFId()
    {

        $Ddd = new \object\Ddd();
        $Ddd->EstadoId = $this->getParams(0);
        
        $model = new TelefoneModel();

        if($Ddd->EstadoId == null) $Ddd->EstadoId = 0;
        
        $retorno = $model->GettDDDByUFId($Ddd);
        $teste= '';
        $html = '<select class="form-control"  id="SelectDDD" name="FilterPetDddId" required>';
        $html .= '<option value="0"> Selecione a Região </option>';
        foreach ($retorno as $key => $value) {
           $html .=  
                    '<option value="'.$value['Id'].'"> ('.$value['Numero'].') - '.$value['Regiao'].' </option>';
            
        }
        $html .= '</select>';
        // $this->PartialResultView(json_encode($retorno, 256));
        $this->PartialResultView($html);
    }
}
