<?php


namespace model\pessoa;

use object\PessoaAnimal;
use object\Pessoa;
use object\TipoPessoa;
use lib\Model;
use helper\CpfCnpj;


class PessoaModel extends Model {
    public function GetbyId(Pessoa $obj)
    {
        return $this->db->First($this->db->Select("SELECT * FROM pessoa WHERE id = '{$obj->Id}'"));  
    }
    public function GetDadosById(Pessoa $obj)
    {
        return $this->db->First($this->db->Select("SELECT a.Id, a.Nome, b.Login 'Email', c.Numero 'Telefone' FROM pessoa a join usuario b
        on a.id = b.pessoaid join telefone c
        on a.id = c.pessoaid WHERE  a.id = '{$obj->Id}'"));
    }
    public function getlist()
    {
        return $this->db->Select("SELECT * FROM pessoa");
    }
    public function GetTipoPessoaByName(TipoPessoa $obj)
    {
       return $this->db->First($this->db->Select("SELECT * from tipopessoa where nome = '{$obj->Nome}'"));
    }
    public function Save(Pessoa $obj)
    {
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'pessoa');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'pessoa');
        }
    }
    public function SaveEndereco(Pessoa $obj)
    {
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'pessoa');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'pessoa');
        }
    }
    public function SavePreference(\object\Preferencia $obj)
    {
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'preferencia');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'preferencia');
        }
    }
    public function GetEnderecoByPessoaId(PessoaAnimal $obj)
    {
        return $this->db->First($this->db->Select("SELECT a.Logradouro, a.Cep, b.Numero 'Ddd', b.Regiao, c.Nome 'Estado' from endereco a join ddd b on 
        a.dddid = b.id join estado c on
        b.estadoid = c.id where a.pessoaid = '{$obj->PessoaId}'"));
    }
    public function GetEstado()
    {
        return  $this->db->Select("SELECT * FROM estado order by nome");
    }
    public function GettDDDByUF(Estado $obj)
    {
        return  $this->db->Select("select a.Id, a.Numero, a.Regiao, b.Sigla from ddd a join estado b on
                a.estadoid = b.id where b.sigla = {$obj->Sigla} order by a.Numero");
    }
    public function GetPreferenceByPessoaId(Pessoa $obj)
    {
        return $this->db->First($this->db->Select("SELECT Id, EspecieId, RacaId, GeneroId, PorteId, PelagemId, Email from preferencia where pessoaid = {$obj->Id}"));
    }
    public function ListaAdotaveis(FilterPet $obj, $Pagina, $PessoaId)
    {
        
        foreach ($obj as $ind => $val){
            if(!($ind == 'DtInclusao') && !($ind == 'DtAtualizacao') && !($ind == 'Id') && !($ind == 'DddId')){        
                
                if($ind == 'EspecieId') $where[] = "g.$ind" .($val == '0' || $val == '' ? " <> 0 " : " = '{$val}'");
                else $where[] = " {$ind} " .($val == '0' ? " <> 0 " : " = '{$val}'");
            } 
        }

        $filtros = implode(' AND ', $where);
        
       return $this->db->Select("
        SELECT 
            a.Nome,
            b.Nome,
            g.nome 'Raca',
            b.peso 'Peso',
            i.nome 'Imagem',
            b.dtnascimento 'Idade',
            e.Numero 'Ddd',
            f.Nome 'Estado',
            e.Regiao
            FROM
                pessoa a
                    JOIN
                preferencia b ON a.id = b.pessoaid
                    JOIN
                telefone c on a.id = c.pessoaid
                    JOIN
                usuario d on a.id = d.pessoaid
            WHERE $filtros and a.PessoaId = $PessoaId and b.Adotado = 0 limit $Pagina, 20");  
    }
    public function ListaPet($filtros, $Pessoa, \object\Area $Area)
    { 
        $Area = $Area->Id ? ' = '.$Area->Id : ' <> 0';

        return $this->db->Select("SELECT a.id, a.nome, b.nome 'raca', peso, e.nome 'imagem', a.dtnascimento FROM animal a join raca b on
        a.racaid = b.id join genero c on
        a.generoid = c.id join porte d on
        a.porteid = d.id join animalimagem e on
        a.id = e.animalid join pessoaanimal f on
        a.id = f.animalid join especie g on
        b.especieid = g.id where $filtros and f.pessoaid = '{$Pessoa->Id}' and a.AreaId $Area");
    }
}