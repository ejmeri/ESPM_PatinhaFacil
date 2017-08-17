<?php

namespace controller\site;

use lib\Controller;
use helper\Session;

class homeController extends Controller {
    public function __construct()
    {
        parent::__construct();

        new Session();
        $this->layout = '_layouthome';
    }
    public function index()
    {
        
        $this->title .= 'Home';

        $apiAnimal = new \api\apiAnimal();        
        $AnimalModel = new \model\animal\AnimalModel();
        $PessoaModel = new \model\pessoa\PessoaModel();

        $Autenticacao = new \object\Autenticacao();
        $AutenticacaoModel = new \model\autenticacao\AutenticacaoModel();

        $Autenticacao->PessoaId = $_SESSION['PessoaId'];

        $PessoaAnimal = new \object\PessoaAnimal();

        $PessoaAnimal->PessoaId = $_SESSION['PessoaId'];

        $this->dados = array(
            'lista' => $AnimalModel->GetList(),
            'estadospet' => $apiAnimal->GetAnimalByUF(),
            'achadosperdidos' =>$apiAnimal->GetAnimalAchadosPerdidosByUfNome(),
            'end' => $PessoaModel->GetEnderecoByPessoaId($PessoaAnimal),
            'auth' => $AutenticacaoModel->GetByPessoaId($Autenticacao)
        );

        $this->view();
    }
    public function paraquemdoar()
    {

        // new Session();

        $this->title .= 'Para quem doar ?';
        $this->layout = '_layout';

        $apiAnimal = new \api\apiAnimal();
        $Pessoa = new \object\Pessoa();
        $AnimalModel = new \model\animal\AnimalModel();

        $Pessoa->Id = $_SESSION['PessoaId'];


        $this->dados = array(
            'pets' => $AnimalModel->GetByPessoaId($Pessoa),
            'especie' =>$AnimalModel->getEspecie(),
            'porte' =>$AnimalModel->getPorte(),
            'genero' =>$AnimalModel->getGenero(),
            'pelagem' =>$AnimalModel->getPelagem(),
            'estadospet' => $apiAnimal->GetAnimalByUF(),
            'achadosperdidos' =>$apiAnimal->GetAchadosPerdidosByUfNome(),
            'random' => $apiAnimal->ListaAdotaveisRandom()
        );
        
        $this->View();
    }
    public function ListaAdotaveis() 
    {

        // error_reporting(!E_NOTICE);

        $apiAnimal = new \api\apiAnimal();
        $FilterPet = new  \object\FilterPet('POST', 'FilterPet');

        $Pagina = $this->getParams(0);
        $PessoaId = $_SESSION['PessoaId'];

        $Pessoa = new \object\Pessoa();
        $Pessoa->Id = $_SESSION['PessoaId'];

        
        if(!isset($Pagina))
            $Pagina = 0;
            
        
        $lista = $apiAnimal->ListaAdotaveis($FilterPet, $Pagina);

            
        $this->dados = array(
            'list' => $lista,
            'all' => $apiAnimal->GetCountByPessoaId($Pessoa),
            'pagina' => $Pagina
        );
        
        // echo print_r($apiAnimal->GetAllByNome($Estado));
  
        $this->PartialView();
    }
}