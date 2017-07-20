<?php

namespace api;

use helper\Session;
use helper\CpfCnpj;
use lib\Controller;
use object\Telefone;
use object\Endereco;
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
    public function SaveEndereco(Endereco $obj)
    {
        $TelefoneModel = new TelefoneModel();
        $TelefoneModel->SaveEndereco($obj);
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
    public function ListaAdotaveis(\object\Preferencia $obj, $Pagina = '0')
    {
         if($Pagina > 0) $Pagina *= 10;

        $PessoaModel = new PessoaModel();
        return $PessoaModel->ListaAdotaveis($FilterPet, $Pagina);
    }
    public function ListaPet(\object\FilterPet $obj, Pessoa $Pessoa, \object\Area $Area)
    {
       $PessoaModel = new PessoaModel();

        foreach ($obj as $ind => $val)
        {
            if(!($ind == 'DtInclusao') && !($ind == 'DtAtualizacao') && !($ind == 'Id') && !($ind == 'DddId') && !($ind == 'EstadoId')){        
                
                if($ind == 'EspecieId') $where[] = "b.$ind" .($val == '0' || $val == '' ? " <> 0 " : " = '{$val}'");
                else $where[] = " {$ind} " .($val == '0' ? " <> 0 " : " = '{$val}'");
            } 
        }

        $filtros = implode(' AND ', $where);

       return $PessoaModel->ListaPet($filtros, $Pessoa, $Area);
    }
    
}

?>
