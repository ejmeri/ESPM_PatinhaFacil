<?php

    namespace model\autenticacao;

    use lib\Model;
    use object\Autenticacao;

    Class AutenticacaoModel extends Model {
        public function Save(Autenticacao $obj)
        {
            if(empty($obj->Id)){
                return $this->db->Insert($obj, 'autenticacao');
            }
            else {
                return $this->db->Update($obj,array('Id'=>$obj->Id),'autenticacao');
            }
        }
        public function GetByNome(Autenticacao $obj)
        {
            return $this->db->First($this->db->Select("SELECT Id, Nome, Autenticado, PessoaId FROM autenticacao WHERE nome = '{$obj->Nome}'"));
        }
        public function GetByPessoaId(Autenticacao $obj)
        {
            return $this->db->First($this->db->Select("SELECT Id, Nome, Autenticado, PessoaId FROM autenticacao WHERE pessoaid = '{$obj->PessoaId}'"));
        }
    }
?>