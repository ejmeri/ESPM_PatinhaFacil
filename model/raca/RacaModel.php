<?php

namespace model\Raca;

use lib\Model;
use object\Raca;

class RacaModel extends Model {

    public function getlist(Raca $obj){
        $EspecieId = $obj->EspecieId ? $obj->EspecieId : "0";

        return $this->db->Select("SELECT id, nome FROM raca where especieid = {$EspecieId}");
    }
    public function save(Raca $obj){
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'raca');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'raca');
        }
    }
}