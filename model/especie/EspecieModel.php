<?php

namespace model\especie;

use lib\Model;
use object\Especie;

class EspecieModel extends Model {

    public function getAll(){
        return $this->db->Select("SELECT id, nome FROM especie order by nome");
    }
    public function save(Especie $obj){
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'Especie');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'Especie');
        }
    }
}