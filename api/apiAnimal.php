<?php

namespace api;

use object\FilterPet;
use object\TipoPessoa;
use object\PessoaAnimal;
use object\Pessoa;
use object\Animal;
use object\AnimalImagem;
use object\Estado;
use object\Doacao;
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
       

        if(isset($obj->Id))
        {
            $pessoalanimal = $AnimalModel->GetPessoaAnimalByAnimalId($obj);
            $animalimagem = $AnimalModel->GetImagemByAnimalId($obj);
            $PessoaAnimal->Id = $pessoalanimal['Id'];
            $AnimalImagem->Id = $animalimagem['Id'];
        }

        $retornoPessoaAnimalModel = $PessoaAnimalModel->Save($PessoaAnimal);        
        
        if($_FILES['image']['size'] != 0) {

            if(isset($_POST['ImagemId']))
                $AnimalImagem->Id = $_POST['ImagemId'];
            
            $error = 'image';
            $image = $Upload->SaveFile($_FILES['image']);
            $AnimalImagem->AnimalId = $retornoAnimal['Identity'];
            $AnimalImagem->Nome = $image;
            

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
                        <div class="panel-body">
                            <h2> Obrigado por estar ajudando mais um animalzinho </h2>
                            <h4> Não esqueca de ir ao seu perfil e cadastrar seu endereço </h4>
                            <a href="home"> Voltar à home </a>
                        </div>
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
    public function ListaAchadosPerdidos(FilterPet $FilterPet, $Pagina = '0', $Area)
    {
        if($Pagina > 0) $Pagina *= 20;

        $AnimalModel = new AnimalModel();
        return $AnimalModel->ListaPetAchadosPerdidos($FilterPet, $Pagina, $Area);
    }
    public function ListaPet(FilterPet $FilterPet, $Pagina = '0')
    {

        if($Pagina > 0) $Pagina *= 20;

        $AnimalModel = new AnimalModel();
        return $AnimalModel->ListaPetDoacao($FilterPet, $Pagina);
    }
    public function ListaAdotaveis(FilterPet $FilterPet, $Pagina = '0', $PessoaId = '0')
    {
        $PessoaId = $_SESSION['PessoaId'];

        if($Pagina > 0) $Pagina *= 40;

        $AnimalModel = new AnimalModel();
        return $AnimalModel->ListaAdotaveis($FilterPet, $Pagina, $PessoaId);
    }
    public function ListaAdotaveisRandom($Pagina = '0', $PessoaId = '0')
    {
        $PessoaId = $_SESSION['PessoaId'];

        if($Pagina > 0) $Pagina *= 20;

        $AnimalModel = new AnimalModel();
        return $AnimalModel->ListaAdotaveisRandom($Pagina, $PessoaId);
    }
    public function GetAllByUf(Estado $obj)
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetAllByUf($obj);
    }
    public function GetAllAchadosPerdidosByUf(Estado $obj)
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetAllAchadosPerdidosByUf($obj);
    }
    public function GetAllAchadosPerdidosByUfNome(Estado $obj)
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetAllAchadosPerdidosByUfNome($obj);
    }
    public function GetAllByUfNome(Estado $obj)
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetAllByUfNome($obj);
    }
    public function GetRandom(Estado $obj, $Random = '20')
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetRandomDoacao($obj, $Random);
    }
    public function GetRandomAchadosPerdidos(Estado $obj, $Random = '20')
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetRandomAchadosPerdidos($obj, $Random);
    }
    public function ConfirmarAdocao(Animal $obj, $PorqueAdotar = '')
    {   
        if(!(isset($_SESSION['PessoaId']))) 
        {   
            echo '
            <div class="alert alert-info alert-dismissable text-center" style="background-color: #FFF; border-radius: 10px">
                <h1 style="color:black">Nos ajude! <i class="fa fa-heart-o" style="color:red"></i></h1> 
                <hr style="max-width: 50px; border: 1px solid black">
                <h2 style="color:black"> Para continuar, cadastre-se ou faça seu login gratuitamente! </h2>  
                <h3><a href="login/index/goto/pets/adotar/'.$obj->Id . hash('md5', $obj->Id) . '" class="btn btn-purple btn-lg">Clique aqui</a> </h3>
            </div>';
        }
        else
        {
            $DoacaoModel = new \model\doacao\DoacaoModel();
            $PessoaModel = new PessoaModel();
            $AnimalModel = new AnimalModel();
            $PessoaAnimalModel = new PessoaAnimalModel();

            $objPessoaAnimal = $PessoaAnimalModel->GetByAnimalId($obj);

            $Animal = $AnimalModel->GetbyId($obj);

            $Doacao = new Doacao();
            $Pessoa = new Pessoa();

            $Pessoa->Id = $Animal['PessoaId'];
            $objPessoa = $PessoaModel->GetDadosById($Pessoa);

            $Pessoa->Id = $_SESSION['PessoaId'];
            $objPessoaLogada = $PessoaModel->GetDadosById($Pessoa);

            $Doacao->PorqueAdotar = $PorqueAdotar;
            $Doacao->AdotadorId = $objPessoaLogada['Id'];
            $Doacao->PessoaAnimalId = $objPessoaAnimal['Id'];

            $retornoDoacao = $DoacaoModel->Save($Doacao);

            $SendEmail = new \helper\sendmail\sendEmail();
        
            $message = file_get_contents('content/site/shared/emails/header-email.html');
            $message .= file_get_contents('content/site/shared/emails/_adotar.html');
            $message .= file_get_contents('content/site/shared/emails/footer-email.html');

            $replacements = array(
                '({petnome})' => $Animal['Nome'],
                '({petespecie})' => $Animal['Especie'],
                '({petraca})' => $Animal['Raca'],
                '({petimagem})' => $Animal['Imagem'],
                '({petpelagem})' => $Animal['Pelagem'],
                '({petidade})' => $Animal['DtNascimento'],
                '({petid})' => $Animal['Id'] .'/'. hash('md5',$a['Id']),
                '({donopet})' => $objPessoa['Nome'],
                '({pessoanome})' => $objPessoaLogada['Nome'],
                '({pessoaemail})' => $objPessoaLogada['Email'],
                '({pessoatelefone})' => $objPessoaLogada['Telefone'],
                '({porqueadotar})' => $PorqueAdotar
            );

            $message = preg_replace( array_keys( $replacements ), array_values( $replacements ), $message );
            
            $retorno = $SendEmail->Send( $objPessoa['Email'], 'Pedido de adoção', $message, 'contato', $objPessoaLogada['Email'], $objPessoaLogada['Nome']);

            if($retorno == 'ok')
            {
                // echo '<br>Enviado.<br>';
                // echo print_r($objPessoa);
                // echo '<br>';
                // echo print_r($objPessoaLogada);
                // echo '<br>';
                echo '<div class="alert alert-info alert-dismissable text-center" style="background-color: rgba(200,200,200,0.2); border-radius: 40px">
                        <h1 style="color:black">Obrigado por ajudar! <i class="fa fa-heart-o" style="color:red"></i></h1> 
                        <hr style="max-width: 50px; border: 1px solid black">
                        <h2 style="color:black"> Enviamos um e-mail para '. $objPessoa['Nome'] .  '! </h2>
                        <p style="color: red"> Fique atento a sua caixa de "Spam" ou "Lixo eletrônico". </p>
                     </div>';
            }
            else
            {
                echo 'Ocorreu um erro: '.$retorno;
            }

        }
    }
    public function GetAnimalByUF()
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetAnimalByUf();
    }
    public function GetAnimalAchadosPerdidosByUfNome()
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetAnimalAchadosPerdidosByUfNome();
    }
    public function GetCountByPessoaId(Pessoa $obj)
    {
        $AnimalModel = new AnimalModel();
        return $AnimalModel->GetCountByPessoaId($obj);
    }
}

?>
