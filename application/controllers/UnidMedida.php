<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class UnidMedida extends CI_Controller {

    public function inserir(){

        //Sigla e Descrição recebidos via JSON e alocados em variáveis
        //Retornos possíveis:
        //1 - Unidade cadastrada corretamente (Banco)
        //2 - Faltou informar a sigla (FrontEnd)
        //3 - Quantidade de caracteres é superior a 3 (FrontEnd)
        //4 - Descrição não informada (FrontEnd)
        //5 - Usuário não informado
        //6 - Houve algum problema no salvamento do LOG, mas a unidade foi inclusa (LOG)

        $json = file_get_contents('php://input');
        $resultado = json_decode($json);

        $sigla     = trim($resultado->sigla);
        $descricao = trim($resultado->descricao);
        $usuario   = trim($resultado->usuario);

        //Faremos a validação para sabermos se todos os dados foram enviados corretamente
        if ($sigla == ''){
            $retorno = array('codigo' => 2, 'msg' => 'Sigla não informada');

        }
        else if (strlen($sigla) > 3){
            $retorno = array('codigo' => 3, 'msg' => 'Sigla pode conter no máximo três caracteres');

        }
        else if ($descricao == ''){
            $retorno = array('codigo' => 4, 'msg' => 'Descrição não informada');

        }
        else if ($usuario == ''){
            $retorno = array('codigo' => 5, 'msg' => 'Uusário não informado');

        }
        else{
            //Realizo a instância da model
            $this->load->model('m_unidmedida');

            //Atributo $retorno recebe array com informações
            $retorno = $this->m_unidmedida->inserir($sigla, $descricao, $usuario);

        }
        
        //Retorno no formato JSON
        echo json_encode($retorno);
    
    }

    public function consultar(){
        //Código, Sigla e Descrição recebidos via JSON e alocados em variáveis
        //Retornos possíveis:
        //1 - Dados consultados corretamnte (Banco) 
        //2 - Quantiade de caracteres da sigla é superior a 3 (FrontEnd)
        //6 - Dados não encontrados (Banco)

        $json = file_get_contents('php://input');
        $resultado = json_decode($json);

        $codigo     = trim($resultado->codigo);
        $sigla      = trim($resultado->sigla);
        $descricao  = trim($resultado->descricao);

        //Verifico somente a quantiade de caracteres da sigla, pode ter até três
        //Ou também nenhum para resultados em um SELECT * FROM [table];
        if(strlen($sigla) > 3){
            $retorno = array('codigo' => 2,
                             'msg' => 'Sigla pode conter no máximo três caracteres ou nenhuma para todas');

        }else{
            //Realizo a instância da model
            $this->load->model('m_unidmedida');

            //Atributo $retorno recebe o array com informações da consulta dos dados [result()]
            $retorno = $this->m_unidmedida->consultar($codigo, $sigla, $descricao);

        }
        //Retorno no formato JSON
        echo json_encode($retorno);

    }
}
?>