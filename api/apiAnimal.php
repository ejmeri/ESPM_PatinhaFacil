<?php

namespace api;

use object\FilterPet;
use object\TipoPessoa;
use object\PessoaAnimal;
use object\Pessoa;
use object\Animal;
use object\AnimalImagem;
use model\pessoa\PessoaModel;
use model\pessoaanimal\PessoaAnimalModel;
use model\animal\AnimalModel;
use helper\Upload;

class apiAnimal
{
    public function Save(Animal $obj, Pessoa $Pessoa)
    {   
        $AnimalImagem = new AnimalImagem();
        $AnimalModel = new AnimalModel();
        $retornoAnimal = $AnimalModel->Save($obj);

        $PessoaAnimalModel = new PessoaAnimalModel();        
        $PessoaAnimal = new PessoaAnimal();
        $TipoPessoa = new TipoPessoa();
        $PessoaModel = new PessoaModel();
        $Upload = new Upload();

        $TipoPessoa->Nome = 'Doador';
        $TipoPessoa = $PessoaModel->GetTipoPessoaByName($TipoPessoa);

        $PessoaAnimal->TipoPessoaId = $TipoPessoa['Id'];
        $PessoaAnimal->AnimalId = $retornoAnimal['Identity'];
        $PessoaAnimal->PessoaId = $Pessoa->Id;
       

        if(isset($obj->Id)){
            $pessoalanimal = $AnimalModel->GetPessoaAnimalByAnimalId($obj);
            $animalimagem = $AnimalModel->GetImagemByAnimalId($obj);
            $PessoaAnimal->Id = $pessoalanimal['Id'];
            $AnimalImagem->Id = $animalimagem['Id'];
        }

        $retornoPessoaAnimalModel = $PessoaAnimalModel->Save($PessoaAnimal);        
        $image = $Upload->SaveFile($_FILES['image']);

        $AnimalImagem->AnimalId = $retornoAnimal['Identity'];

        $AnimalImagem->Nome = $image;

        $AnimalModel->SaveImage($AnimalImagem);

        if($retornoPessoaAnimalModel['sucess']) 
        {
            echo print_r($retornoPessoaAnimalModel);
            echo '<br>';
            echo print_r($PessoaAnimal);
            echo '<br>';
            echo  '<h2>Obrigado por estar ajudando mais um animalzinho</h2>';
            echo  '<p>Veja os detalhes <a href="pets/detalhes/'.$PessoaAnimal->AnimalId.'"> <b class="link-blue">Aqui</b> </a>!</p>';
        }
        else {
            echo $retornoPessoaAnimalModel['feedback'];
            echo '<br>';
            
        }
    }
    public function ListaPet(FilterPet $FilterPet)
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->ListaPet($FilterPet);
    }
    public function GetTenRandom($random = 10)
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetTenRandom();
    }
    public function ConfirmarAdocao(Animal $Obj)
    {
        if(!(isset($_SESSION['PessoaId']))) 
        {
            echo '<h3> Cadastre-se ou efetue seu login <a href="login/index/pets/4">Aqui </a>';
        }
        else
        {
            echo 'logado';
        }
    }
}

?>
