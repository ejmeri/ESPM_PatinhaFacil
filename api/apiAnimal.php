<?php

namespace api;

use object\FilterPet;
use object\TipoPessoa;
use object\PessoaAnimal;
use object\Pessoa;
use object\Animal;
use object\AnimalImagem;
use object\Estado;
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
                'Mensagem' => '
                    <div class="panel panel-primary" id="panelpetsave">
                        <button type="button" class="close" 
                            data-target="#panelpetsave" 
                            data-dismiss="alert"
                            style="padding: 10px">
                            <span aria-hidden="true" style="color:white">&times;</span><span class="sr-only" style="color:white">Close</span>
                        </button>
                        <div class="panel-heading">Informações Salvas!</div>
                        <div class="panel-body">Obrigado por estar ajudando mais um animalzinho</div>
                    </div>    
                   <!-- <p>Veja os detalhes <a href="pets/detalhes/'.$PessoaAnimal->AnimalId.'"> Aqui!</a></p> -->
                '
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
    public function ListaPet(FilterPet $FilterPet, $Pagina = '0')
    {   

        if($Pagina > 0) $Pagina *= 10;

        $AnimalModel = new AnimalModel();
        return $AnimalModel->ListaPet($FilterPet, $Pagina);
    }
    public function GetAllByUf(Estado $obj)
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetAllByUf($obj);
    }
    public function GetAllByUfNome(Estado $obj)
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetAllByUfNome($obj);
    }
    public function GetRandom(Estado $obj, $Random = '20')
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetRandom($obj, $Random);
    }
    public function ConfirmarAdocao(Animal $obj)
    {   
        if(!(isset($_SESSION['PessoaId']))) 
        {   
            echo '
            <div class="alert alert-info alert-dismissable text-center" style="background-color: rgba(200,200,200,0.2); border-radius: 40px">
                <h1 style="color:black">Nos ajude! <i class="fa fa-heart-o" style="color:red"></i></h1> 
                <hr style="max-width: 50px; border: 1px solid black">
                <h2 style="color:black"> Para continuar, cadastre-se ou faça seu login gratuitamente! </h2>  
                <h3><a href="login/index/goto/pets/adotar/'.$obj->Id.'" class="btn btn-purple btn-lg">Clique aqui</a> </h3>
            </div>';
        }
        else
        {
            echo 'logado';
        }
    }
    public function GetAnimalByUF()
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetAnimalByUf();
    }
}

?>
