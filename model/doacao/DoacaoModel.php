<?php


namespace model\doacao;

use object\PessoaAnimal;
use lib\Model;


class DoacaoModel extends Model {
    public function GetbyId(\object\Doacao $obj){
        return $this->db->First($this->db->Select("SELECT * FROM doacao WHERE id = '{$obj->Id}'"));  
    }
    public function Save(\object\Doacao $obj){
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'doacao');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'doacao');
        }
    }
}