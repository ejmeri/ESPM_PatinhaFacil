<?php

namespace model\animal;

use object\Estado;
use object\FilterPet;
use object\Animal;
use object\AnimalImagem;
use object\Pessoa;
use object\Especie;
use lib\Model;

Class AnimalModel extends Model {

    public function GetAnimalById(Animal $obj)
    {
        return $this->db->First($this->db->Select("SELECT * FROM animal where id = {$obj->Id}"));
    }
    public function GetAnimalJoinRacaByAnimalId(Animal $obj)
    {
        return $this->db->First($this->db->Select("SELECT a.*, b.EspecieId FROM animal a join raca b on
                a.racaid = b.id where a.Id = {$obj->Id}"));
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
    public function GetAnimalByUf()
    {
        return $this->db->Select("SELECT f.Sigla, f.Nome, count(b.id) 'Quantidade' FROM
                pessoaanimal a
                    JOIN
                animal b ON a.animalid = b.id
                    JOIN
                pessoa c ON a.pessoaid = c.id
                    JOIN
                endereco d ON c.id = d.pessoaid
                    JOIN
                ddd e ON d.dddid = e.id
                    JOIN
                estado f ON e.estadoid = f.id
                group by f.Sigla, f.Nome");
    }
    public function GetRandom(Estado $obj, $random = '10')
    {

       return $this->db->Select("SELECT 
            b.Id,
            b.Nome,
            g.nome 'Raca',
            b.peso 'Peso',
            i.nome 'Imagem',
            b.dtnascimento 'Idade',
            e.Numero 'Ddd',
            f.Nome 'Estado',
            e.Regiao
            FROM
                pessoaanimal a
                    JOIN
                animal b ON a.animalid = b.id
                    JOIN
                pessoa c ON a.pessoaid = c.id
                    JOIN
                endereco d ON c.id = d.pessoaid
                    JOIN
                ddd e ON d.dddid = e.id
                    JOIN
                estado f ON e.estadoid = f.id
                    JOIN
                raca g ON b.racaid = g.id
                    JOIN
                especie h ON g.especieid = h.id
                    JOIN
                animalimagem i ON b.id = i.animalid
            WHERE f.Sigla = '{$obj->Sigla}' and b.Adotado = 0
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
    public function getRacaByEspecieId(Especie $obj)
    {
        return $this->db->Select("SELECT * FROM raca where EspecieId = {$obj->Id} order by nome");
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
            if(!($ind == 'DtInclusao') && !($ind == 'DtAtualizacao') && !($ind == 'Id')){        
                
                if($ind == 'EspecieId') $where[] = "g.$ind" .($val == '0' || $val == '' ? " <> 0 " : " = '{$val}'");
                else $where[] = " {$ind} " .($val == '0' ? " <> 0 " : " = '{$val}'");
            } 
        }

        $filtros = implode(' AND ', $where);
        
        return $this->db->Select("
        SELECT 
            b.Id,
            b.Nome,
            g.nome 'Raca',
            b.peso 'Peso',
            i.nome 'Imagem',
            b.dtnascimento 'Idade',
            e.Numero 'Ddd',
            f.Nome 'Estado',
            e.Regiao
            FROM
                pessoaanimal a
                    JOIN
                animal b ON a.animalid = b.id
                    JOIN
                pessoa c ON a.pessoaid = c.id
                    JOIN
                endereco d ON c.id = d.pessoaid
                    JOIN
                ddd e ON d.dddid = e.id
                    JOIN
                estado f ON e.estadoid = f.id
                    JOIN
                raca g ON b.racaid = g.id
                    JOIN
                especie h ON g.especieid = h.id
                    JOIN
                animalimagem i ON b.id = i.animalid
            WHERE $filtros and b.Adotado = 0");  
    }
}