<?php

namespace controller\site;


use lib\Controller;
use model\animal\AnimalModel;
use model\pessoa\PessoaModel;
use model\telefone\TelefoneModel;
use model\usuario\UsuarioModel;
use model\raca\RacaModel;
use model\especie\EspecieModel;
use object\Estado;
use object\Animal;
use object\Raca;
use object\Pessoa;
use object\Telefone;
use object\Especie;
use object\Usuario;
use object\FilterPet;
use object\PessoaAnimal;
use helper\Session;
use api\apiAnimal;
use api\apiPessoa;

class petsController extends Controller {
    public function index()
    {
        // new Session();
        if(!isset($_SESSION['PessoaId'])) $this->layout = '_layoutlogoff';
        else $this->layout = '_layout';

        $this->title .= "Pets";

        
        $apiAnimal = new ApiAnimal();
        $model = new EspecieModel();
        $modelAnimal = new AnimalModel();
        $modelTelefone = new TelefoneModel();

    
        $Estado = new Estado();
        $Estado->Sigla = $this->getParams(0);

        
        $this->dados = array(
            'estado' =>  $Estado->Sigla,
            'especie' =>$modelAnimal->getEspecie(),
            'porte' =>$modelAnimal->getPorte(),
            'genero' =>$modelAnimal->getGenero(),
            'pelagem' =>$modelAnimal->getPelagem(),
            'estados' => $modelTelefone->GetUF(),
            'random' => $apiAnimal->GetRandom($Estado)
        );

        $this->View();
    }
    public function novo()
    {
        new Session();

        $this->title .= "Doar um Pet";

        if(!isset($_POST['botao']))
        {
            $AnimalModel = new AnimalModel();

            $this->dados = array(
                'especie'=> $AnimalModel->getEspecie(),
                'porte'=> $AnimalModel->getPorte(),
                'genero' =>$AnimalModel->getGenero(),
                'pelagem' =>$AnimalModel->getPelagem()
            );

            $this->View();
        }
        else 
        {
            $ApiAnimal = new apiAnimal();
            $Pessoa = new Pessoa();
            $Pessoa->Id = $_SESSION['PessoaId'];

            $this->PartialResultView($ApiAnimal->Save(new Animal('POST', 'Animal'),$Pessoa));
        }
    }
    public function editar()
    {
        new Session();

        $this->title .= "Editar";

        if(!isset($_POST['botao']))
        {
            
            
            $Animal = new Animal();
            $Animal->Id = $this->getParams(0);

            $AnimalModel = new AnimalModel();
            $Especie = new Especie();

            $PessoaAnimal = $AnimalModel->GetPessoaAnimalByAnimalId($Animal);

            if($PessoaAnimal['PessoaId'] != $_SESSION['PessoaId'])
            {
                header("Location: ". APP_ROOT. "/home");
            }

            $animal = $AnimalModel->GetAnimalJoinRacaByAnimalId($Animal);

            $Especie->Id = $animal['EspecieId'];


            $this->dados = array(
                'especie'=> $AnimalModel->getEspecie(),
                'raca' => $AnimalModel->getRacaByEspecieId($Especie),
                'porte'=> $AnimalModel->getPorte(),
                'genero' =>$AnimalModel->getGenero(),
                'pelagem' =>$AnimalModel->getPelagem(),
                'imagem' => $AnimalModel->GetImagemByAnimalId($Animal),
                'animal' => $animal
            );

            $this->View();
            
        }
        else 
        {
            $ApiAnimal = new apiAnimal();
            $Pessoa = new Pessoa();
            $Pessoa->Id = $_SESSION['PessoaId'];

            $this->PartialResultView($ApiAnimal->Save(new Animal('POST', 'Animal'),$Pessoa));
        }
    }
    public function detalhes()
    {
        // new Session();
        
        $this->title .= "Detalhes do Pet";
        $Animal = new Animal();
        $PessoaAnimal = new PessoaAnimal();

        $Animal->Id = $this->getParams(0);

        $model = new AnimalModel();
        $PessoaModel = new PessoaModel();

        $pessoaanimal = $model->GetPessoaAnimalByAnimalId($Animal);
        $PessoaAnimal->PessoaId = $pessoaanimal['PessoaId'];

        $endereco = $PessoaModel->GetEnderecoByPessoaId($PessoaAnimal);

        $deserialize = json_decode($json = $endereco['JsonEndereco']);

        $endereco['Logradouro'] = $deserialize->logradouro;
        $endereco['Bairro'] = $deserialize->bairro;
        $endereco['Cidade'] = $deserialize->cidade;
        $endereco['UF'] = $deserialize->uf;
        $endereco['CEP'] = $deserialize->cep;

        $this->dados = array(
            'dados' => $model->GetbyId($Animal),
            'endereco' => $endereco
        );

        $this->View();   
    }
    public function adotar($AnimalId ='')
    {
        // new Session();
        // error_reporting(!E_NOTICE);

        if(!isset($_SESSION['PessoaId'])) $this->layout = '_layoutlogoff';
        else $this->layout = '_layout';
        
        $this->title .= "Adotar";

        if(!isset($_POST['save']))
        {
            $Pessoa = new Pessoa();
            $Animal = new Animal();
            $Telefone = new Telefone();
            $Usuario = new Usuario();
            $PessoaAnimal = new PessoaAnimal();

            $Animal->Id = $this->getParams(0);

            $animalModel = new AnimalModel();
            $PessoaModel = new PessoaModel();
            $telefoneModel = new TelefoneModel();
            $UsuarioModel = new UsuarioModel();

            $pets = $animalModel->GetById($Animal);

            $Telefone->PessoaId = $pets['PessoaId'];
            $Usuario->PessoaId = $pets['PessoaId'];
            $Pessoa->Id = $pets['PessoaId'];
            $PessoaAnimal->PessoaId = $Pessoa->Id;

            $endereco = $PessoaModel->GetEnderecoByPessoaId($PessoaAnimal);

            $this->dados = array(
                'pet' => $pets,
                'pessoa' => $PessoaModel->GetById($Pessoa),
                'telefones' => $telefoneModel->GetbyPessoaId($Telefone),
                'email' => $UsuarioModel->GetByPessoaIdNoPass($Usuario),
                'endereco' => $endereco
            );

            $this->View();
        }
        else 
        {
            $ApiAnimal = new apiAnimal();

            $this->PartialResultView($ApiAnimal->ConfirmarAdocao(new Animal('POST', 'Animal')));
        }
    }
    public function ListaPet() 
    {   

        error_reporting(!E_NOTICE);

        $apiAnimal = new apiAnimal();
        $FilterPet = new FilterPet('POST', 'FilterPet');


        $Estado = new Estado();
        $Estado->Sigla = $this->getParams(0);

        if($Estado->Sigla != '0' && $Estado->Sigla != '')
        {
            $this->dados = array(
            'random' => $apiAnimal->GetRandom($Estado)
            );
        }
        else 
        {
            $this->dados = array(
             'list' =>  $apiAnimal->ListaPet($FilterPet)
            );
        }
        // echo print_r($Estado);


       
        
        $this->PartialView();
    }
    public function ListaRaca()
    {

        $Raca = new Raca();
        $Raca->EspecieId = $this->getParams(0);

        $model = new RacaModel();
        
        $this->dados = array(
            'list' => $model->getlist($Raca)
        );
        
        $this->PartialView();
    }
    public function FilterListaRaca()
    {

        $Raca = new Raca();
        $Raca->EspecieId = $this->getParams(0);

        $model = new RacaModel();
        
        $this->dados = array(
            'list' => $model->getlist($Raca)
        );
        
        $this->PartialView();
    }
}