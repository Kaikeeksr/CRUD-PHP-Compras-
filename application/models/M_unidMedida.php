<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class M_unidmedida extends CI_Model{

    public function inserir($sigla, $descricao, $usuario){
        //Query de inserção dos dados
        $sql = "insert into unid_medida (sigla, descricao, usucria)
                values ('$sigla', '$descricao','$usuario')";

        $this->db->query($sql);

        //Verificação (Inserção ocorreu com sucesso ou não)
        if($this->db->affected_rows() > 0){
            //Fazemos a inserção no Log na nuvem
            //Fazemos a instância da model M_log
            $this->load->model('m_log');

            //Fazemos a chamado do método de inserção do Log
            //$retorno_log irá receber um JSON com o resultado (código)
            $retorno_log = $this->m_log->inserir_log($usuario, $sql);

            if($retorno_log['codigo'] == 1){
                $dados = array('codigo' => 1,
                               'msg' => 'Unidade de medida cadastrada corretamente');

            }else{
                $dados = array('codigo' => 7,
                               'msg' => 'Houve algum problema no salvamento do Log, porém,
                                         Unidade de Medida cadastrada corretamente');
            }
        }else{
            $dados = array('codigo' => 6,
                           'msg' => 'Houve algum problema na inserção na tabela unidade de media');

        }

    //Envia o array dados com as informações tratadas acima pela estrutura de decisão IF
    return $dados;

    }

    public function consultar($codigo, $sigla, $descricao){

      
        $sql = "select * from usuarios where estatus = '' ";

        if($codigo != '') {
            $sql = $sql . "and codigo = '$codigo' ";
        }

        if($sigla != '') {
            $sql = $sql . "and sigla = '$sigla' ";
        }

        if($descricao != '') {
            $sql = $sql . "and descricao like '%$descricao%' ";

        }

        $retorno = $this->db->query($sql);


        if($retorno->num_rows() > 0) {
            
            $dados = array('codigo' => 1,
            'msg' => 'Consulta realizada com sucesso',
            'dados' => $retorno->resultado());   
            
        }else{
            $dados = array('codigo' => 6, 'msg' => 'Dados não encontrados');
        }

        return $dados;

    }


}
?>