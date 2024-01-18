<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AprovacaoPropostaDAO
 *
 * @author d827421
 */
require_once dirname(dirname(__FILE__)) . '/entity/AprovacaoProposta.php';
require_once dirname(dirname(__FILE__)) . '/entity/Usuario.php';
require_once dirname(dirname(__FILE__)) . '/entity/Proposta.php';
require_once 'AprovacaoDAO.php';
class AprovacaoPropostaDAO extends AprovacaoDAO{
    private $formProposta;
    function AprovacaoPropostaDAO($formProposta,$formUser){
        $this->formProposta = $formProposta;
        $this->formUser = $formUser;
    }
    
 /**
 * a função getAllByStatus($status), neste contexto, retorna todas as propostas de acordo com o critério de status:
 * S - Aprovados
 * N - Pendentes
 * A - Arquivados
 *  */
    public function getAllByStatus($status, $limit = "") {
        global $wpdb;
        $propostas = [];
        $query = "        
            SELECT DISTINCT * FROM (SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), '%s') AS Submitted
            , submit_time
            , MAX(IF(field_name='author_mail', field_value, NULL )) AS 'author_mail'
            , MAX(IF(field_name='latitude', field_value, NULL )) AS 'latitude'
            , MAX(IF(field_name='longitude', field_value, NULL )) AS 'longitude'
            , MAX(IF(field_name='colaboracao', field_value, NULL )) AS 'colaboracao'
            , MAX(IF(field_name='titulo', field_value, NULL )) AS 'titulo'
            , MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
            , MAX(IF(field_name='plataformaApoioNumeroApoios', field_value, NULL )) AS 'plataformaApoioNumeroApoios'
            , MAX(IF(field_name='coordsPerimetro', field_value, NULL )) AS 'coordsPerimetro'
            FROM wp_cf7dbplugin_submits
            WHERE form_name = '".$this->formProposta."' GROUP BY submit_time)
            AS a JOIN 
                (SELECT    
                    MAX(IF(field_name='nome', field_value, NULL )) AS 'NomeUser'
                  , MAX(IF(field_name='emailCadastro', field_value, NULL )) AS 'EmailUser'
                  , MAX(IF(field_name='endereco', field_value, NULL )) AS 'EnderecoUser'
                  , MAX(IF(field_name='datanasc', field_value, NULL )) AS 'datanasc'
                  , MAX(IF(field_name='cep', field_value, NULL )) AS 'cepUser'  FROM wp_cf7dbplugin_submits
                    WHERE form_name = '".$this->formUser."' GROUP BY submit_time) AS b WHERE a.plataformaStatus = '%s' 
                  AND a.author_mail = b.EmailUser ORDER BY a.plataformaApoioNumeroApoios DESC ".$limit;
        $statement = $wpdb->prepare($query,'%b %e, %Y  %l:%i %p',$status);
        $results = $wpdb->get_results($statement);
        foreach($results as $row){
            $proposta = new Proposta();
            $user = new Usuario();
            $proposta->id = $row->submit_time;
            $proposta->author_email = $row->author_mail;
            $proposta->submitted = $row->Submitted;
            $proposta->latitude = $row->latitude;
            $proposta->longitude = $row->longitude;
            $proposta->colaboracao = $row->colaboracao;
            $proposta->titulo = $row->titulo;
            $proposta->status = $row->plataformaStatus;
            $proposta->feature = $row->coordsPerimetro;
            $proposta->numApoios = $row->plataformaApoioNumeroApoios;
            $user->nome = $row->NomeUser;
            $user->email = $row->EmailUser;
            $user->endereco = $row->EnderecoUser;
            $user->cep = $row->cepUser;
            $user->datanasc = $row->datanasc;
            $aprovacaoProposta = new AprovacaoProposta($user, $proposta);
            array_push($propostas, $aprovacaoProposta);
        }
        return $propostas;
        
         
    }

    public function getCountByStatus($status) {
        global $wpdb;
        $query = "SELECT count(*) FROM (SELECT MAX(IF(field_name='plataformaStatus', field_value, NULL )) 
            AS 'plataformaStatus' FROM wp_cf7dbplugin_submits WHERE form_name = '".$this->formProposta."'
            GROUP BY submit_time) AS a WHERE a.plataformaStatus = '%s'";
        $statement = $wpdb->prepare($query, $status);
        $count = $wpdb->get_var($statement);
        return $count;
    }

//put your code here
}
