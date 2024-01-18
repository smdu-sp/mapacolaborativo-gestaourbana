<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ColabImovelDAO
 *
 * @author d827421
 */
require_once 'IColabImovelDAO.php';
require_once dirname(dirname(__DIR__)) . '/entity/funcao_social/ColabImovel.php';
require_once dirname(dirname(__DIR__)) . '/entity/Usuario.php';
class ColabImovelDAO implements IColabImovelDAO{
    
    private $formColab;
    private $formUser;
    function ColabImovelDAO($formColab,$formUser){
        $this->formColab = $formColab;
        $this->formUser = $formUser;
    }
    public function getColaboracoesApproved() {
        $colaboracoes = [];
        $query = "SELECT * FROM 
            (SELECT * FROM(SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), '%b %e, %Y  %l:%i %p') AS Submitted
            , submit_time
            , MAX(IF(field_name='author_mail', field_value, NULL )) AS 'author_mail'
            , MAX(IF(field_name='latitude', field_value, NULL )) AS 'latitude'
            , MAX(IF(field_name='longitude', field_value, NULL )) AS 'longitude'
            , MAX(IF(field_name='logradouro', field_value, NULL )) AS 'logradouro'
            , MAX(IF(field_name='numero', field_value, NULL )) AS 'numero'
            , MAX(IF(field_name='ponto-referencia', field_value, NULL )) AS 'pontoReferencia'
            , MAX(IF(field_name='como_e_o_imovel', field_value, NULL )) AS 'caracteristicasImovel'
            , MAX(IF(field_name='o_imovel_possui', field_value, NULL )) AS 'possesDoImovel'
            , MAX(IF(field_name='txtOutros', field_value, NULL )) AS 'textoOutros'
            , MAX(IF(field_name='tipo_aprovacao', field_value, NULL )) AS 'tipo_aprovacao'
            , MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
            , MAX(IF(field_name='plataformaApoioNumeroApoios', field_value, NULL )) AS 'plataformaApoioNumeroApoios'
            , MAX(IF(field_name='tempo_sem_utilizacao', field_value, NULL )) AS 'tempoInutilizado'
            FROM wp_cf7dbplugin_submits WHERE form_name = '" . $this->formColab . "' GROUP BY submit_time) 
            AS A WHERE A.plataformaStatus = 'S') AS B JOIN (SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), '%b %e, %Y  %l:%i %p') AS Submitted
            , submit_time AS 'idUser'
            , MAX(IF(field_name='nome', field_value, NULL )) AS 'usuarioNome'
            , MAX(IF(field_name='emailCadastro', field_value, NULL )) AS 'usuarioEmail'
            , MAX(IF(field_name='instituicao', field_value, NULL )) AS 'usuarioInstituicao'
            , MAX(IF(field_name='endereco', field_value, NULL )) AS 'usuarioEndereco'
            , MAX(IF(field_name='datanasc', field_value, NULL )) AS 'datanasc'
            , MAX(IF(field_name='CEP', field_value, NULL )) AS 'usuarioCEP'
            FROM wp_cf7dbplugin_submits WHERE form_name = '" . $this->formUser . "' GROUP BY submit_time)"
            . " AS C ON B.author_mail = C.usuarioEmail";
        global $wpdb;
        $results = $wpdb->get_results($query);
        foreach ($results as $row){
            $colaboracao = new ColabImovel();
            $autor = new Usuario();
            $autor->id = $row->idUser;
            $autor->nome = $row->usuarioNome;
            $autor->email = $row->usuarioEmail;
            $autor->instituicao = $row->usuarioInstituicao;
            $autor->endereco = $row->usuarioEndereco;
            $autor->cep = $row->usuarioCEP;
            $autor->datanasc = $row->datanasc;
            $colaboracao->id = $row->submit_time;
            $colaboracao->autor = $autor;
            $colaboracao->author_email = $row->author_mail;
            $colaboracao->latitude = $row->latitude;
            $colaboracao->longitude = $row->longitude;
            $colaboracao->logradouro = $row->logradouro;
            $colaboracao->numeroLogradouro = $row->numero;
            $colaboracao->caracteristicaImovel = $row->caracteristicasImovel;
            $colaboracao->possesDoImovel = $row->possesDoImovel;
            $colaboracao->tipoAprovacao = $row->tipo_aprovacao;
            if($colaboracao->possesDoImovel !== "" || $colaboracao->possesDoImovel !== null)
                $colaboracao->possesDoImovel .= ', '.$row->textoOutros;
            else
                $colaboracao->possesDoImovel = $row->textoOutros;
            $colaboracao->tempoInutilizado = $row->tempoInutilizado;
            $colaboracao->pontoReferencia = $row->pontoReferencia;
            $colaboracao->status = $row->plataformaStatus;
            $colaboracao->numApoios = $row->plataformaApoioNumeroApoios;
            array_push($colaboracoes, $colaboracao);
        }
        return $colaboracoes;
    }

}
