<?php

namespace api;

use helper\Session;
use helper\CpfCnpj;
use lib\Controller;
use object\Telefone;
use object\Pessoa;
use object\Email;
use object\Usuario;
use model\usuario\UsuarioModel;
use model\pessoa\PessoaModel;
use model\email\EmailModel;
use model\telefone\TelefoneModel;

Class apiPessoa
{
   public function ValidateCpfCnpj(Pessoa $obj)
    {
        $validar = new CpfCnpj();
        
        if(!$validar->isCPF($obj->CpfCnpj) && (strlen($obj->CpfCnpj) == 11)){
            echo "CPF inválido.";  
        }
        else if (!$validar->isCPF($obj->CpfCnpj) && (strlen($obj->CpfCnpj) == 14)){
            echo "CNPJ inválido.";  
        }
    }
    public function LoadInfo()
    {
        $Usuario = new Usuario();
        $Telefone = new Telefone();
        $Email = new Email();
        $Pessoa = new Pessoa();
        
        
        $Pessoa->Id = $_SESSION['PessoaId'];    
        $Email->PessoaId = $Pessoa->Id;
        $Telefone->PessoaId = $Pessoa->Id;
        $Usuario->PessoaId = $Pessoa->Id;

        $model = new PessoaModel();
        $UsuarioModel = new UsuarioModel();
        $EmailModel = new EmailModel();
        $modelTelefone = new TelefoneModel();

       return $this->dados = array(
            'dados' => $model->GetbyId($Pessoa),
            'user' => $UsuarioModel->GetByPessoaId($Usuario),
            'emails' => $EmailModel->GetbyPessoaId($Email),
            'email' => $EmailModel->GetbyId($Email),
            'tipoemail' => $EmailModel->GetTipo(),
            'telefones' => $modelTelefone->GetbyPessoaId($Telefone),
            'tipotelefone' => $modelTelefone->GetTipo(),
            'ddd' => $modelTelefone->GetDdd()
        );
    }
}

?>
