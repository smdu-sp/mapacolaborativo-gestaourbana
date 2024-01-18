<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AprovacaoComentarioDAO

 *
 * @author d827421
 */
require_once dirname(dirname(__FILE__)) . '/entity/AprovacaoComentario.php';
require_once dirname(dirname(__FILE__)) . '/entity/Usuario.php';
require_once dirname(dirname(__FILE__)) . '/entity/Comentario.php';
require_once 'AprovacaoDAO.php';
class AprovacaoComentarioDAO extends AprovacaoDAO{
    private $formComment;
    function AprovacaoComentarioDAO($formComment,$formUser){
        $this->formComment = $formComment;
        $this->formUser = $formUser;
    }
    
 /**
 * a função getAllByStatus($status), neste contexto, retorna todos os comentários de acordo com o critério de status:
 * S - Aprovados
 * N - Pendentes
 * A - Arquivados
 *  */
    public function getAllByStatus($status, $limit = "") {
        global $wpdb;
        $comentarios = [];
        $query = "        
            SELECT DISTINCT * FROM (SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), '%s') AS Submitted
            , submit_time
            , MAX(IF(field_name='author_mail', field_value, NULL )) AS 'author_mail'
            , MAX(IF(field_name='regiaoComentada', field_value, NULL )) AS 'regiaoComentada'
            , MAX(IF(field_name='colaboracao', field_value, NULL )) AS 'colaboracao'
            , MAX(IF(field_name='titulo', field_value, NULL )) AS 'titulo'
            , MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
            , MAX(IF(field_name='concordaDiscorda', field_value, NULL )) AS 'posicionamento'
            , MAX(IF(field_name='plataformaApoioNumeroApoios', field_value, NULL )) AS 'plataformaApoioNumeroApoios'
            , MAX(IF(field_name='idFeature', field_value, NULL )) AS 'idFeature'
            FROM wp_cf7dbplugin_submits
            WHERE form_name = '".$this->formComment."' GROUP BY submit_time)
            AS a JOIN 
                (SELECT    
                    MAX(IF(field_name='nome', field_value, NULL )) AS 'NomeUser'
                  , MAX(IF(field_name='emailCadastro', field_value, NULL )) AS 'EmailUser'
                  , MAX(IF(field_name='datanasc', field_value, NULL )) AS 'datanasc'
                  , MAX(IF(field_name='endereco', field_value, NULL )) AS 'EnderecoUser'
                  , MAX(IF(field_name='cep', field_value, NULL )) AS 'cepUser'  FROM wp_cf7dbplugin_submits
                    WHERE form_name = '".$this->formUser."' GROUP BY submit_time) AS b WHERE a.plataformaStatus = '%s' 
                  AND a.author_mail = b.EmailUser ORDER BY a.plataformaApoioNumeroApoios DESC ".$limit;
        $statement = $wpdb->prepare($query,'%b %e, %Y  %l:%i %p',$status);
        $results = $wpdb->get_results($statement);
        foreach($results as $row){
            $comentario = new Comentario();
            $user = new Usuario();
            $comentario->id = $row->submit_time;
            $comentario->author_email = $row->author_mail;
            $comentario->submitted = $row->Submitted;
            $comentario->regiaoDescricaoPerimetro = $row->regiaoComentada;
            $comentario->comentario = $row->colaboracao;
            $comentario->titulo = $row->titulo;
            $comentario->posicionamento = $row->posicionamento;
            $comentario->status = $row->plataformaStatus;
            $comentario->idFeature = $row->idFeature;
            $comentario->numApoios = $row->plataformaApoioNumeroApoios;
            $user->nome = $row->NomeUser;
            $user->email = $row->EmailUser;
            $user->endereco = $row->EnderecoUser;
            $user->cep = $row->cepUser;
            $user->datanasc = $row->datanasc;
            $aprovacao = new AprovacaoComentario($user, $comentario);
            array_push($comentarios, $aprovacao);
        }
        return $comentarios;
    }
    public function getCountByStatus($status){
        global $wpdb;
        $query = "SELECT count(*) FROM (SELECT MAX(IF(field_name='plataformaStatus', field_value, NULL )) 
            AS 'plataformaStatus' FROM wp_cf7dbplugin_submits WHERE form_name = '".$this->formComment."'
            GROUP BY submit_time) AS a WHERE a.plataformaStatus = '%s'";
        $statement = $wpdb->prepare($query, $status);
        $count = $wpdb->get_var($statement);
        return $count;
    }


//put your code here
}
