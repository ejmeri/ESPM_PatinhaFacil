<?php

namespace model\animal;

use object\Animal;
use object\AnimalImagem;
use object\Pessoa;
use lib\Model;

Class AnimalModel extends Model {

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
    public function getlist(Animal $obj){
        return $this->db->Select("SELECT a.id, a.nome, b.nome 'raca', peso, e.nome 'imagem' FROM animal a join raca b on
        a.racaid = b.id join genero c on
        a.generoid = c.id join porte d on
        a.porteid = d.id join animalimagem e on
        a.id = e.animalid where b.especieid = '{$obj->EspecieId}'");
    }
    public function GetTenRandom()
    {
        return $this->db->Select("SELECT a.id, a.nome, b.nome 'raca', peso, e.nome 'imagem' FROM animal a join raca b on
        a.racaid = b.id join genero c on
        a.generoid = c.id join porte d on
        a.porteid = d.id join animalimagem e on
        a.id = e.animalid order by rand() and a.dtinclusao asc limit 15");
    }
    public function GetByPessoaId(Pessoa $obj){
         return $this->db->Select("SELECT a.id, a.nome, b.nome 'raca', peso, e.nome 'imagem' FROM animal a join raca b on
        a.racaid = b.id join genero c on
        a.generoid = c.id join porte d on
        a.porteid = d.id join animalimagem e on
        a.id = e.animalid join pessoaanimal f on
        a.id = f.animalid where f.pessoaid = '{$obj->Id}'");
    }
    public function Save(Animal $obj){
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
    public function SaveImage(AnimalImagem $obj)
    {
       if (empty($obj->Id)){
            return $this->db->Insert($obj,'animalimagem');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'animalimagem');
        }
    }
}