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
        
        if($_FILES['image']['size'] != 0) {

            $error = 'aimge';
            $image = $Upload->SaveFile($_FILES['image']);
            $AnimalImagem->AnimalId = $retornoAnimal['Identity'];
            $AnimalImagem->Nome = $image;
            $AnimalImagem->Id = $_POST['ImagemId'];

            $AnimalModel->SaveImage($AnimalImagem);
        }
        

        if($retornoPessoaAnimalModel['sucess']) 
        {
            $jsonretorno = array(
                'Status' => true,
                'Do' => '',
                'Mensagem' => '<h2>Obrigado por estar ajudando mais um animalzinho</h2>
                               <p>Veja os detalhes <a href="pets/detalhes/'.$PessoaAnimal->AnimalId.'"> Aqui!</a></p> <br>'
            );
        }
        else {
             $jsonretorno = array(
                'Status' => false,
                'Do' => '',
                'Mensagem' => $retornoPessoaAnimalModel['feedback']
            );  
        }

        echo json_encode($jsonretorno);

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
    public function ConfirmarAdocao(Animal $obj)
    {
        if(!(isset($_SESSION['PessoaId']))) 
        {   
            echo '<h3> Cadastre-se ou efetue seu login <a href="login/index/pets/adotar/'.$obj->Id.'">aqui</a>';
        }
        else
        {
            echo 'logado';
        }
    }
}

?>
