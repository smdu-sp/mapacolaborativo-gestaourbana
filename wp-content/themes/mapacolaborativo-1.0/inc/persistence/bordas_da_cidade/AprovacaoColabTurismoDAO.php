<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AprovacaoColabTurismoDAO
 *
 * @author d827421
 */
require_once dirname(dirname(__FILE__)) . '/AprovacaoDAO.php';
require_once dirname(dirname(__DIR__)) . '/entity/bordas_da_cidade/AprovacaoColabTurismo.php';
require_once dirname(dirname(__DIR__)) . '/entity/Usuario.php';
require_once dirname(dirname(__DIR__)) . '/entity/bordas_da_cidade/ColabTurismo.php';
class AprovacaoColabTurismoDAO extends AprovacaoDAO{
 private $formColab;
    function AprovacaoColabTurismoDAO($formColab, $formUser){
        $this->formColab = $formColab;
        $this->formUser = $formUser;
    }

    public function getAllByStatus($status, $limit = "") {
        $colaboracoes = [];
            $query = "        
            SELECT DISTINCT * FROM(SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), %s) AS Submitted
            , submit_time
            , MAX(IF(field_name='author_mail', field_value, NULL )) AS 'author_mail'
            , MAX(IF(field_name='latitude', field_value, NULL )) AS 'latitude'
            , MAX(IF(field_name='longitude', field_value, NULL )) AS 'longitude'
            , MAX(IF(field_name='tipoEmpreendimento', field_value, NULL )) AS 'tipoEmpreendimento'
            , MAX(IF(field_name='tipoEmpreendimentoOutros', field_value, NULL )) AS 'tipoEmpreendimentoOutros'
            , MAX(IF(field_name='tipoAcessoEmpreendimento', field_value, NULL )) AS 'tipoAcessoEmpreendimento'
            , MAX(IF(field_name='meioAcessoEmpreendimento', field_value, NULL )) AS 'meioAcessoEmpreendimento'
            , MAX(IF(field_name='principalFonteRenda', field_value, NULL )) AS 'principalFonteRenda'
            , MAX(IF(field_name='colaboradores', field_value, NULL )) AS 'colaboradores'
            , MAX(IF(field_name='principaisAtrativos', field_value, NULL )) AS 'principaisAtrativos'
            , MAX(IF(field_name='principaisAtrativosOutros', field_value, NULL )) AS 'principaisAtrativosOutros'
            , MAX(IF(field_name='imagem_colaboracao', field_value, NULL )) AS 'imagem_colaboracao'
            , MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
            , MAX(IF(field_name='plataformaApoioNumeroApoios', field_value, NULL )) AS 'plataformaApoioNumeroApoios'
            FROM wp_cf7dbplugin_submits
            WHERE form_name = '".$this->formColab."' GROUP BY submit_time)
            AS a JOIN 
                (SELECT    
                    MAX(IF(field_name='nome', field_value, NULL )) AS 'NomeUser'
                  , MAX(IF(field_name='emailCadastro', field_value, NULL )) AS 'EmailUser'
                  , MAX(IF(field_name='endereco', field_value, NULL )) AS 'EnderecoUser'
                  , DATE_FORMAT(MAX(IF(field_name='datanasc', field_value, NULL )),%s) AS 'datanasc'
                  , MAX(IF(field_name='sexo', field_value, NULL )) AS 'sexo'
                  , MAX(IF(field_name='raca', field_value, NULL )) AS 'raca'
                  , MAX(IF(field_name='cep', field_value, NULL )) AS 'cepUser'  FROM wp_cf7dbplugin_submits
                    WHERE form_name = '".$this->formUser."' GROUP BY submit_time) AS b WHERE a.plataformaStatus = '%s' 
                  AND a.author_mail = b.EmailUser ORDER BY a.plataformaApoioNumeroApoios DESC ".$limit;
        global $wpdb;
        $statement = $wpdb->prepare($query,'%b %e, %Y  %l:%i %p','%d/%m/%Y',$status);
        $results = $wpdb->get_results($statement);
        foreach ($results as $row){
            $colaboracao = new ColabTurismo();
            $user = new Usuario();
            $colaboracao->id = $row->submit_time;
            $colaboracao->author_email = $row->author_mail;
            $colaboracao->submitted = $row->Submitted;
            $colaboracao->latitude = $row->latitude;
            $colaboracao->longitude = $row->longitude;
            $colaboracao->tipoEmpreendimento = $row->tipoEmpreendimento;
            if($colaboracao->tipoEmpreendimentoOutros != "" || $colaboracao->tipoEmpreendimentoOutros != null)
                $colaboracao->tipoEmpreendimento .= ', '.$row->tipoEmpreendimentoOutros;
            $colaboracao->tipoAcessoEmpreendimento = $row->tipoAcessoEmpreendimento;
            $colaboracao->meioAcessoEmpreendimento = $row->meioAcessoEmpreendimento;
            $colaboracao->principalFonteRenda = $row->principalFonteRenda;
            $colaboracao->colaboradores = $row->colaboradores;
            $colaboracao->principaisAtrativos = $row->principaisAtrativos;
            if($colaboracao->principaisAtrativosOutros != "" || $colaboracao->principaisAtrativosOutros != null)
                $colaboracao->principaisAtrativos .= ', '.$row->principaisAtrativosOutros;
            $colaboracao->imagem = $row->imagem_colaboracao;
            $colaboracao->status = $row->plataformaStatus;
            $colaboracao->numApoios = $row->plataformaApoioNumeroApoios;
            $user->nome = $row->NomeUser;
            $user->email = $row->EmailUser;
            $user->endereco = $row->EnderecoUser;
            $user->cep = $row->cepUser;
            $user->datanasc = $row->datanasc;
            $user->sexo = $row->sexo;
            $user->raca = $row->raca;
            if($row->raca == "" || $row->raca == null)
                $user->raca = "Não Informada";
            $colaboracaoImovel = new AprovacaoColabTurismo($user,$colaboracao);
            array_push($colaboracoes,$colaboracaoImovel);
        }
        return $colaboracoes;   
    }

    public function getCountByStatus($status) {
        global $wpdb;
        $query = "SELECT count(*) FROM (SELECT MAX(IF(field_name='plataformaStatus', field_value, NULL )) 
            AS 'plataformaStatus' FROM wp_cf7dbplugin_submits WHERE form_name = '".$this->formColab."'
            GROUP BY submit_time) AS a WHERE a.plataformaStatus = '%s'";
        $statement = $wpdb->prepare($query, $status);
        $count = $wpdb->get_var($statement);
        return $count;
    }

}
