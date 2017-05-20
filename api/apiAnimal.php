<?php

namespace api;

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

        $retornoPessoaAnimalModel = $PessoaAnimalModel->Save($PessoaAnimal);

        $image = $Upload->SaveFile($_FILES['image']);

        $AnimalImagem->AnimalId = $retornoAnimal['Identity'];

        $AnimalImagem->Nome = $image;

        $AnimalModel->SaveImage($AnimalImagem);

        if($retornoPessoaAnimalModel['sucess']) 
        {
            echo  '<h2>Parabéns o '.$obj->Nome.', foi cadastrado com sucesso!</h2>';
            echo  '<p>Veja os detalhes <a href="pets/detalhes/'.$PessoaAnimal->AnimalId.'"> <b class="link-blue">Aqui</b> </a>!</p>';
        }
        else echo $retornoPessoaAnimalModel['feedback'];
        
    }
    
}

?>
