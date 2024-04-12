<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AprovacaoColabImovelDAO
 *
 * @author d827421
 */
require_once dirname(dirname(__FILE__)) . '/AprovacaoDAO.php';
require_once dirname(dirname(__DIR__)) . '/entity/funcao_social/AprovacaoColabImovel.php';
require_once dirname(dirname(__DIR__)) . '/entity/Usuario.php';
require_once dirname(dirname(__DIR__)) . '/entity/funcao_social/ColabImovel.php';
class AprovacaoColabImovelDAO extends AprovacaoDAO {
    
    private $formColab;
    function AprovacaoColabImovelDAO($formColab, $formUser){
        $this->formColab = $formColab;
        $this->formUser = $formUser;
    }
    /**
    * a função getAllByStatus($status), neste contexto, retorna todas as colaborações de acordo com o critério de status:
    * S - Aprovados
    * N - Pendentes
    * A - Arquivados
    *  */
    public function getAllByStatus($status, $limit = "",$tipo_aprovacao = "") {
        $colaboracoes = [];
            $query = "        
            SELECT DISTINCT * FROM(SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), %s) AS Submitted
            , submit_time
            , MAX(IF(field_name='latitude', field_value, NULL )) AS 'latitude'
            , MAX(IF(field_name='longitude', field_value, NULL )) AS 'longitude'
            , MAX(IF(field_name='logradouro', field_value, NULL )) AS 'logradouro'
            , MAX(IF(field_name='numero', field_value, NULL )) AS 'numero'
            , MAX(IF(field_name='ponto-referencia', field_value, NULL )) AS 'pontoReferencia'
            , MAX(IF(field_name='como_e_o_imovel', field_value, NULL )) AS 'caracteristicasImovel'
            , MAX(IF(field_name='tipo_aprovacao', field_value, NULL )) AS 'tipo_aprovacao'
            , MAX(IF(field_name='o_imovel_possui', field_value, NULL )) AS 'possesDoImovel'
            , MAX(IF(field_name='txtOutros', field_value, NULL )) AS 'textoOutros'
            , MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
            , MAX(IF(field_name='plataformaApoioNumeroApoios', field_value, NULL )) AS 'plataformaApoioNumeroApoios'
            , MAX(IF(field_name='tempo_sem_utilizacao', field_value, NULL )) AS 'tempoInutilizado'
            FROM wp_cf7dbplugin_submits
            WHERE form_name = '".$this->formColab."' GROUP BY submit_time)
            AS a
            WHERE a.plataformaStatus = '%s'" . $tipo_aprovacao . "
            ORDER BY a.submit_time ASC ".$limit;
        global $wpdb;
        $statement = $wpdb->prepare($query,'%b %e, %Y  %l:%i %p',$status);
        $results = $wpdb->get_results($statement);
        foreach ($results as $row){
            $colaboracao = new ColabImovel();
            $user = new Usuario();
            $colaboracao->id = $row->submit_time;
            $colaboracao->submitted = $row->Submitted;
            $colaboracao->latitude = $row->latitude;
            $colaboracao->longitude = $row->longitude;
            $colaboracao->logradouro = $row->logradouro;
            $colaboracao->numero = $row->numero;
            $colaboracao->caracteristicaImovel = $row->caracteristicasImovel;
            $colaboracao->possesDoImovel = $row->possesDoImovel;
            if($colaboracao->possesDoImovel !== "" || $colaboracao->possesDoImovel !== null)
                $colaboracao->possesDoImovel .= ', '.$row->textoOutros;
            else
                $colaboracao->possesDoImovel = $row->textoOutros;
            $colaboracao->tempoInutilizado = $row->tempoInutilizado;
            $colaboracao->pontoReferencia = $row->pontoReferencia;
            $colaboracao->status = $row->plataformaStatus;
            $colaboracao->numApoios = $row->plataformaApoioNumeroApoios;
            $colaboracaoImovel = new AprovacaoColabImovel($user,$colaboracao);
            array_push($colaboracoes,$colaboracaoImovel);
        }
        return $colaboracoes;
    }

    public function getCountByStatus($status,$tipo_aprovacao = "") {
        global $wpdb;
        $query = "SELECT count(*) FROM (
            SELECT MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
           ,MAX(IF(field_name='tipo_aprovacao', field_value, NULL )) AS 'tipo_aprovacao'
            FROM wp_cf7dbplugin_submits WHERE form_name = '".$this->formColab."'
            GROUP BY submit_time) AS a WHERE a.plataformaStatus = '%s'".$tipo_aprovacao;
        $statement = $wpdb->prepare($query, $status);
        $count = $wpdb->get_var($statement);
        return $count;
    }
    public function insertCategoriaProposta($id,$categoria){
        global $wpdb;
        $query = "SELECT count(*) FROM wp_cf7dbplugin_submits WHERE form_name = %s AND field_name = %s AND submit_time = %s";
        $statement = $wpdb->prepare($query, $this->formColab,"tipo_aprovacao",$id);
        $cont = $wpdb->get_var($statement);
        if($cont > 0){
            $query = "DELETE FROM wp_cf7dbplugin_submits WHERE form_name = %s AND field_name = %s AND submit_time = %s";
            $statement = $wpdb->prepare($query, $this->formColab,"tipo_aprovacao",$id);
        }
        $query = "INSERT INTO wp_cf7dbplugin_submits(submit_time,form_name,field_name,field_value) VALUES (%s,%s,%s,%s)";
        $statement = $wpdb->prepare($query,$id,$this->formColab,"tipo_aprovacao",$categoria);
        $wpdb->query($statement);
    }

}
