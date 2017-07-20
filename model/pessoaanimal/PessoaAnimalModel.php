<?php


namespace model\pessoaanimal;

use object\PessoaAnimal;
use lib\Model;


class PessoaAnimalModel extends Model {
    public function GetbyId(PessoaAnimal $obj)
    {
        return $this->db->First($this->db->Select("SELECT * FROM pessoaanimal WHERE id = '{$obj->Id}'"));  
    }
    public function Save(PessoaAnimal $obj)
    {
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'pessoaanimal');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'pessoaanimal');
        }
    }
    public function GetByAnimalId(\object\Animal $obj)
    {
        return $this->db->First($this->db->Select("SELECT * FROM pessoaanimal WHERE animalid = '{$obj->Id}'"));  
    }
}



