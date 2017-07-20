<?php


namespace model\preferencia;

use object\Preferencia;
use lib\Model;


class PreferenciaModel extends Model {
    public function GetbyId(Preferencia $obj)
    {
        return $this->db->First($this->db->Select("SELECT * FROM pessoaanimal WHERE id = '{$obj->Id}'"));  
    }
    public function GetList(Preferencia $obj, $limit)
    {
        foreach ($obj as $ind => $val)
        {
            if(!($ind == 'DtInclusao') && !($ind == 'DtAtualizacao') && !($ind == 'Id') && !($ind == 'EspecieId'))
            {      
                $where[] = "a.{$ind} " .($val == '0' || $val == '' ? " <> 0 " : " = '{$val}'");
            } 
        }

        $filtros = implode(' AND ', $where);
    // $this->db->Select
        return $this->db->Select(("SELECT b.Nome, c.Login 'Email', d.Numero 'Telefone' FROM preferencia a join pessoa b on a.pessoaid = b.id join usuario c on b.id = c.pessoaid join telefone d on b.id = d.pessoaid WHERE $filtros or a.racaid = 0 order by rand() limit $limit"));  
    }
    public function Save(Preferencia $obj)
    {
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'Preferencia');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'Preferencia');
        }
    }
}



