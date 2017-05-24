<?php


namespace model\email;

use object\Email;
use lib\Model;

class EmailModel extends Model {
    public function Save(Email $obj){
        if (empty($obj->Id)){
            return $this->db->Insert($obj,'email');
        } else {
            return $this->db->Update($obj,array('Id'=>$obj->Id),'email');
        }
    }
    public function GetbyPessoaId(Email $obj)
    {
        return $this->db->Select("SELECT a.Id, a.Nome, b.Nome 'Tipo', TipoEmailId FROM email a join tipoemail b on a.tipoemailid = b.id WHERE pessoaid = '{$obj->PessoaId}'");
    }
    public function GetFirstbyPessoaId(Email $obj)
    {
        return $this->db->First($this->db->Select("SELECT a.Id, a.Nome, b.Nome 'Tipo', TipoEmailId FROM email a join tipoemail b on a.tipoemailid = b.id WHERE pessoaid = '{$obj->PessoaId}'"));
    }
    public function GetbyId(Email $obj)
    {
        return $this->db->First($this->db->Select("SELECT a.Id, a.Nome, b.Nome 'Tipo', TipoEmailId FROM email a join tipoemail b on a.tipoemailid = b.id WHERE a.id = '{$obj->Id}'"));
    }
    public function GetTipo()
    {
        return $this->db->Select('SELECT Id, Nome from tipoemail order by nome;');
    }
}