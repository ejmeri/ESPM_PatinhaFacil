<?php

namespace controller\site;


use lib\Controller;
use model\animal\AnimalModel;
use model\pessoa\PessoaModel;
use model\telefone\TelefoneModel;
use model\email\EmailModel;
use model\raca\RacaModel;
use model\especie\EspecieModel;
use object\Animal;
use object\Raca;
use object\Pessoa;
use object\Telefone;
use object\Especie;
use object\Email;
use object\FilterPet;
use object\PessoaAnimal;
use helper\Session;
use api\apiAnimal;
use api\apiPessoa;

class petsController extends Controller {
    public function index()
    {

        new Session();

        $this->title .= "Pets";

        
        $model = new EspecieModel();
        $modelAnimal = new AnimalModel();
        $modelTelefone = new TelefoneModel();

        $this->dados = array(
            'especie' =>$modelAnimal->getEspecie(),
            'porte' =>$modelAnimal->getPorte(),
            'genero' =>$modelAnimal->getGenero(),
            'pelagem' =>$modelAnimal->getPelagem(),
            'estados' => $modelTelefone->GetUF(),
            'random' =>$modelAnimal->GetTenRandom()
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

            $PessoaAnimal = $AnimalModel->GetPessoaAnimalByAnimalId($Animal);

            if($PessoaAnimal['PessoaId'] != $_SESSION['PessoaId']){
                header("Location: ". APP_ROOT. "/home");
            }  

            $this->dados = array(
                'especie'=> $AnimalModel->getEspecie(),
                'porte'=> $AnimalModel->getPorte(),
                'genero' =>$AnimalModel->getGenero(),
                'pelagem' =>$AnimalModel->getPelagem(),
                'imagem' => $AnimalModel->GetImagemByAnimalId($Animal),
                'animal' => $AnimalModel ->GetAnimalById($Animal)
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
        new Session();
        
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
    public function confirmar($AnimalId ='')
    {
        new Session();
        error_reporting(!E_NOTICE);

        $this->title .= "Confirmar adoção";

        if(!$_POST['button'])
        {
            $Pessoa = new Pessoa();
            $Animal = new Animal();
            $Telefone = new Telefone();
            $Email = new Email();
            $PessoaAnimal = new PessoaAnimal();

            $Animal->Id = $this->getParams(0);

            $animalModel = new AnimalModel();
            $PessoaModel = new PessoaModel();
            $telefoneModel = new TelefoneModel();
            $emailModel = new EmailModel();

            $pets = $animalModel->GetById($Animal);

            $Telefone->PessoaId = $pets['PessoaId'];
            $Email->PessoaId = $pets['PessoaId'];
            $Pessoa->Id = $pets['PessoaId'];
            $PessoaAnimal->PessoaId = $Pessoa->Id;
            
            $endereco = $PessoaModel->GetEnderecoByPessoaId($PessoaAnimal);

            $deserialize = json_decode($json = $endereco['JsonEndereco']);

            $endereco['Logradouro'] = $deserialize->logradouro;
            $endereco['Bairro'] = $deserialize->bairro;
            $endereco['Cidade'] = $deserialize->cidade;
            $endereco['UF'] = $deserialize->uf;
            $endereco['CEP'] = $deserialize->cep;

            $this->dados = array(
                'pet' => $pets,
                'pessoa' => $PessoaModel->GetById($Pessoa),
                'telefones' => $telefoneModel->GetbyPessoaId($Telefone),
                'emails' => $emailModel->GetByPessoaId($Email),
                'endereco' => $endereco
            );


            $this->View();
        } 
    }
    public function ListaPet() 
    {   
        $apiAnimal = new apiAnimal();
        $FilterPet = new FilterPet('POST', 'FilterPet');

        $random = $apiAnimal->GetTenRandom();
        $lista = $apiAnimal->ListaPet($FilterPet);

        // echo '<br>';
        // print_r($random);
        // echo '<br>';
        // print_r($lista);
        // echo '<br>';

        $this->dados = array(
            'list' => $lista,
            'random' => $random
        );
        
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