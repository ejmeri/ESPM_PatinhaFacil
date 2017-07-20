<?php

namespace controller\site;

use lib\Controller;
use helper\Session;

class ajudaController extends Controller {
    public function __construct()
    {
        parent::__construct();

        $this->layout = '_layout';
    }
    public function termos()
    {
        $this->layout = '_layoutlogoff';
        $this->title .= 'Termos de uso';

        $this->View();
    }
    public function pets()
    {
        new Session();
        $this->title .= 'Meus pets';
        
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
            'achadosperdidos' =>$apiAnimal->GetAnimalAchadosPerdidosByUfNome(),
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
    public function paraquemdoar()
    {

        new Session();
        $this->title .= 'Para quem doar?';

        
        $AnimalModel = new \model\animal\AnimalModel();        
        $Animal = new \object\Animal();

        $Animal->Id = $this->getParams(0);
        $email = $this->getParams(1);

        $Preferencia = new \object\Preferencia();
        $ApiPreferencia = new \api\apiPreferencia();
        $apiAnimal = new \api\apiAnimal;
        

        $animal = $AnimalModel->GetAnimalById($Animal);

        $Preferencia->GeneroId = $animal['GeneroId'];
        $Preferencia->PelagemId = $animal['PelagemId'];
        $Preferencia->PorteId = $animal['PorteId'];
        $Preferencia->RacaId = $animal['RacaId'];


        if(isset($email) && count($lista) > 0)
        {
            $lista = $ApiPreferencia->GetList($Preferencia, '5');
            
            $ApiAjuda = new \api\apiAjuda();

            foreach ($lista as $li => $a)
            {
                $ApiAjuda->EmailParaQuemDoar($a['Email'], $a['Nome'], $Animal->Id);
            }

            $this->PartialResultView('<h3>E-mails enviados.</h3>');
        }
        else 
        { 
            $lista = $ApiPreferencia->GetList($Preferencia, '45');

            $this->dados = array(
                'petid' => $Animal->Id,
                'estadospet' => $apiAnimal->GetAnimalByUF(),
                'achadosperdidos' =>$apiAnimal->GetAnimalAchadosPerdidosByUfNome(),
                'listpeople' => $lista
            );

            $this->View();
        }
    }
    public function achadoseperdidos()
    {
        // new Session();
        if(!isset($_SESSION['PessoaId'])) $this->layout = '_layoutlogoff';
        else $this->layout = '_layout';

        $this->title .= "Achados e Perdidos";

        
        $apiAnimal = new \api\apiAnimal;
        $model = new \model\especie\EspecieModel;
        $modelAnimal = new \model\animal\AnimalModel;
        $modelTelefone = new \model\telefone\TelefoneModel;

    
        $Estado = new \object\Estado;
        $Estado->Sigla = $this->getParams(0);
        

        $this->dados = array(
            'estado' =>  $Estado->Sigla,
            'especie' =>$modelAnimal->getEspecie(),
            'porte' =>$modelAnimal->getPorte(),
            'area' => $modelAnimal->GetAreaAchadosPerdidos(),
            'genero' =>$modelAnimal->getGenero(),
            'pelagem' =>$modelAnimal->getPelagem(),
            'estados' => $modelTelefone->GetUF(),
            'random' => $apiAnimal->GetRandomAchadosPerdidos($Estado),
            'all' => $apiAnimal->GetAllAchadosPerdidosByUf($Estado),
            'estadospet' => $apiAnimal->GetAnimalByUF(),
            'achadosperdidos' =>$apiAnimal->GetAnimalAchadosPerdidosByUfNome()
        );

        $this->View();
    }
    public function encontrado()
    {
        // new Session();
        // error_reporting(!E_NOTICE);

        if(!isset($_SESSION['PessoaId'])) $this->layout = '_layoutlogoff';
        else $this->layout = '_layout';
        
        $this->title .= " Encontrado";

        $Pessoa = new \object\Pessoa();
        $Animal = new \object\Animal();
        $Telefone = new \object\Telefone();
        $Usuario = new \object\Usuario();
        $PessoaAnimal = new \object\PessoaAnimal();

        $Animal->Id = $this->getParams(0);

        $apiAnimal = new \api\apiAnimal();
        $animalModel = new \model\animal\AnimalModel();
        $PessoaModel = new \model\pessoa\PessoaModel();
        $telefoneModel = new \model\telefone\TelefoneModel();
        $UsuarioModel = new \model\usuario\UsuarioModel();

        $pets = $animalModel->GetById($Animal);

        

        $Telefone->PessoaId = $pets['PessoaId'];
        $Usuario->PessoaId = $pets['PessoaId'];
        $Pessoa->Id = $pets['PessoaId'];
        $PessoaAnimal->PessoaId = $Pessoa->Id;

        $endereco = $PessoaModel->GetEnderecoByPessoaId($PessoaAnimal);

            if(count($pets) < 1) header("Location: ". APP_ROOT. "/pets/index/SP");

        $this->dados = array(
            'pet' => $pets,
            'pessoa' => $PessoaModel->GetById($Pessoa),
            'telefones' => $telefoneModel->GetbyPessoaId($Telefone),
            'email' => $UsuarioModel->GetByPessoaIdNoPass($Usuario),
            'endereco' => $endereco,
            'achadosperdidos' =>$apiAnimal->GetAnimalAchadosPerdidosByUfNome(),
            'estadospet' => $apiAnimal->GetAnimalByUF()
        );

        $this->View();
        
    }
    public function ListaPet() 
    {   

        error_reporting(!E_NOTICE);

        $apiAnimal = new \api\apiAnimal;
        $FilterPet = new \object\FilterPet('POST', 'FilterPet');

        $Estado = new \object\Estado;
        $Estado->Sigla = $this->getParams(0);
        $Pagina = $this->getParams(1);


        if(isset($Estado->Sigla) && strlen($Estado->Sigla) < 3)
        {
            $this->dados = array(
                'random' => $apiAnimal->GetRandomAchadosPerdidos($Estado),
                'all' => $apiAnimal->GetAllAchadosPerdidosByUf($Estado)
            );
        }
        else 
        {
            if(!isset($Pagina))
                $Pagina = 0;
                
            
            $Area = $_POST['AreaId'];

            $lista = $apiAnimal->ListaAchadosPerdidos($FilterPet, $Pagina, $Area);

            $this->dados = array(
                'list' => $lista,
                'pagina' => $Pagina, 
            );
        }
        
        // echo print_r($apiAnimal->GetAllByNome($Estado));
  
        $this->PartialView();
    }
}