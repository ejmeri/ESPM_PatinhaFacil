<?php

namespace model\animal;

use object\Estado;
use object\FilterPet;
use object\Animal;
use object\AnimalImagem;
use object\Pessoa;
use lib\Model;

Class AnimalModel extends Model {

    public function GetAnimalById(Animal $obj)
    {
        return $this->db->First($this->db->Select("SELECT * FROM animal where id = {$obj->Id}"));
    }
    public function GetImagemByAnimalId(Animal $obj)
    {
        return $this->db->First($this->db->Select("SELECT * FROM animalimagem where animalid = {$obj->Id}"));
    }
    public function GetbyId(Animal $obj)
    {
        return $this->db->First($this->db->Select("SELECT a.Id, a.Nome,DtNascimento,e.Nome 'Pelagem',Peso,Descricao, b.Nome 'Raca', d.Nome 'Porte', f.nome 'Imagem', g.PessoaId FROM animal a join raca b on
                a.racaid = b.id join genero c on
                a.generoid = c.id join porte d on
                a.porteid = d.id join pelagem e on
                a.pelagemid = e.id join animalimagem f on
                a.id = f.animalid join pessoaanimal g on
                a.id = g.animalid where a.id = '{$obj->Id}';"));

    }
    public function getlist(Animal $obj)
    {
        return $this->db->Select("SELECT a.id, a.nome, b.nome 'raca', peso, e.nome 'imagem' FROM animal a join raca b on
        a.racaid = b.id join genero c on
        a.generoid = c.id join porte d on
        a.porteid = d.id join animalimagem e on
        a.id = e.animalid where b.especieid = '{$obj->EspecieId}'");
    }
    public function GetTenRandom(Estado $obj)
    {
        $uf = '"uf":"'.$obj->Sigla.'"';

        return $this->db->Select("SELECT a.id, a.nome, f.nome 'raca', peso, e.nome 'imagem', d.jsonendereco from animal a join pessoaanimal b on
                a.id = b.animalid join pessoa c on
                b.pessoaid = c.id join endereco d on
                c.id = d.pessoaid join animalimagem e on
                a.id = e.animalid join raca f on
                a.racaid = f.id   join especie g on
                f.especieid = g.id where d.jsonendereco like '%{$uf}%'
                order by rand() and a.dtinclusao asc limit 10");

        // return $this->db->Select("SELECT a.id, a.nome, b.nome 'raca', peso, e.nome 'imagem' FROM animal a join raca b on
        // a.racaid = b.id join genero c on
        // a.generoid = c.id join porte d on
        // a.porteid = d.id join animalimagem e on
        // a.id = e.animalid ");
    }
    public function GetByPessoaId(Pessoa $obj)
    {
        return $this->db->Select("SELECT a.id, a.nome, b.nome 'raca', peso, e.nome 'imagem' FROM animal a join raca b on
            a.racaid = b.id join genero c on
            a.generoid = c.id join porte d on
            a.porteid = d.id join animalimagem e on
            a.id = e.animalid join pessoaanimal f on
            a.id = f.animalid where f.pessoaid = '{$obj->Id}'");
    }
    public function GetPessoaAnimalByAnimalId(Animal $obj)
    {
        return $this->db->First($this->db->Select("SELECT * from pessoaanimal where animalid = {$obj->Id}"));
    }
    public function Save(Animal $obj)
    {
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'animal');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'animal');
        }
    }
    public function getPorte()
    {
         return $this->db->Select("SELECT * FROM porte order by nome");
    }
    public function getGenero()
    {
         return $this->db->Select("SELECT * FROM genero order by nome");
    }
    public function getEspecie()
    {
        return $this->db->Select("SELECT * FROM especie order by nome");
    }
    public function getPelagem()
    {
        return $this->db->Select("SELECT * FROM pelagem order by nome");
    }
    public function getRaca()
    {
        return $this->db->Select("SELECT * FROM raca order by nome");
    }
    public function SaveImage(AnimalImagem $obj)
    {
       if (empty($obj->Id)){
            return $this->db->Insert($obj,'animalimagem');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'animalimagem');
        }
    }
    public function ListaPet(FilterPet $obj)
    {
        
        foreach ($obj as $ind => $val){
            if(!($ind == 'DtInclusao') && !($ind == 'DtAtualizacao') && !($ind == 'Localizacao') && !($ind == 'Id')){        
                
                if($ind == 'EspecieId') $where[] = "f.$ind" .($val == '0' ? " <> 0 " : " = '{$val}'");
                else $where[] = " {$ind} " .($val == '0' ? " <> 0 " : " = '{$val}'");
            } 
        }

        $sql = implode(' AND ', $where);
        
        $uf = '"uf":"'.$obj->Localizacao.'"';

        return $this->db->Select("SELECT a.id, a.nome, f.nome 'raca', peso, e.nome 'imagem', d.jsonendereco from animal a join pessoaanimal b on
                a.id = b.animalid join pessoa c on
                b.pessoaid = c.id join endereco d on
                c.id = d.pessoaid join animalimagem e on
                a.id = e.animalid join raca f on
                a.racaid = f.id   join especie g on
                f.especieid = g.id where $sql and d.jsonendereco like '%{$uf}%'");  
    }
}