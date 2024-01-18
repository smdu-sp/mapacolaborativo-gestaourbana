<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ColabProdutorDAO
 *
 * @author d827421
 */
require_once 'IColabProdutorDAO.php';
require_once dirname(dirname(__DIR__)) . '/entity/bordas_da_cidade/ColabProdutor.php';
require_once dirname(dirname(__DIR__)) . '/entity/Usuario.php';
class ColabProdutorDAO implements IColabProdutorDAO{
    private $formColab;
    private $formUser;
    function ColabProdutorDAO($formColab,$formUser){
        $this->formColab = $formColab;
        $this->formUser = $formUser;
    }
    public function getColaboracoesApproved() {
        $colaboracoes = [];
            $query = "SELECT * FROM(        
            SELECT * FROM(SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), '%b %e, %Y  %l:%i %p') AS Submitted
            , submit_time
            , MAX(IF(field_name='author_mail', field_value, NULL )) AS 'author_mail'
            , MAX(IF(field_name='latitude', field_value, NULL )) AS 'latitude'
            , MAX(IF(field_name='longitude', field_value, NULL )) AS 'longitude'
            , MAX(IF(field_name='areaUnidadeProdutiva', field_value, NULL )) AS 'areaUnidadeProdutiva'
            , MAX(IF(field_name='unidadeAreaUnidadeProdutiva', field_value, NULL )) AS 'unidadeAreaUnidadeProdutiva'
            , MAX(IF(field_name='areaCultivada', field_value, NULL )) AS 'areaCultivada'
            , MAX(IF(field_name='unidadeAreaCultivada', field_value, NULL )) AS 'unidadeAreaCultivada'
            , MAX(IF(field_name='nascenteCorrego', field_value, NULL )) AS 'nascenteCorrego'
            , MAX(IF(field_name='produtosCultivados', field_value, NULL )) AS 'produtosCultivados'
            , MAX(IF(field_name='produtosCultivadosOutros', field_value, NULL )) AS 'produtosCultivadosOutros'
            , MAX(IF(field_name='caracteristicasProducao', field_value, NULL )) AS 'caracteristicasProducao'
            , MAX(IF(field_name='caracteristicasProducaoOutros', field_value, NULL )) AS 'caracteristicasProducaoOutros'
            , MAX(IF(field_name='finalidadeProducao', field_value, NULL )) AS 'finalidadeProducao'
            , MAX(IF(field_name='principalFonteRenda', field_value, NULL )) AS 'principalFonteRenda'
            , MAX(IF(field_name='assistenciaTecnica', field_value, NULL )) AS 'assistenciaTecnica'
            , MAX(IF(field_name='CAR_regularizado', field_value, NULL )) AS 'CAR_regularizado'
            , MAX(IF(field_name='possuiDAP', field_value, NULL )) AS 'possuiDAP'
            , MAX(IF(field_name='colaboradoresNaUnidade', field_value, NULL )) AS 'colaboradoresNaUnidade'
            , MAX(IF(field_name='permiteVisitantes', field_value, NULL )) AS 'permiteVisitantes'
            , MAX(IF(field_name='sugestoes_criticas_comentarios', field_value, NULL )) AS 'sugestoes_criticas_comentarios'
            , MAX(IF(field_name='imagem_colaboracao', field_value, NULL )) AS 'imagem_colaboracao'
            , MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
            , MAX(IF(field_name='plataformaApoioNumeroApoios', field_value, NULL )) AS 'plataformaApoioNumeroApoios'
            FROM wp_cf7dbplugin_submits
            WHERE form_name = '".$this->formColab."' GROUP BY submit_time)
            AS A WHERE A.plataformaStatus = 'S') AS B JOIN 
                (SELECT    
                    MAX(IF(field_name='nome', field_value, NULL )) AS 'NomeUser'
                  , MAX(IF(field_name='emailCadastro', field_value, NULL )) AS 'EmailUser'
                  , MAX(IF(field_name='endereco', field_value, NULL )) AS 'EnderecoUser'
                  , DATE_FORMAT(MAX(IF(field_name='datanasc', field_value, NULL )),'%d/%m/%Y') AS 'datanasc'
                  , MAX(IF(field_name='sexo', field_value, NULL )) AS 'sexo'
                  , MAX(IF(field_name='raca', field_value, NULL )) AS 'raca'
                  , MAX(IF(field_name='cep', field_value, NULL )) AS 'cepUser'  FROM wp_cf7dbplugin_submits
                    WHERE form_name = '".$this->formUser."' GROUP BY submit_time) AS C 
                    ON B.author_mail = C.EmailUser";
        global $wpdb;
        $results = $wpdb->get_results($query);
        foreach ($results as $row){
            $colaboracao = new ColabProdutor();
            $user = new Usuario();
            $colaboracao->id = $row->submit_time;
            $colaboracao->author_email = $row->author_mail;
            $colaboracao->submitted = $row->Submitted;
            $colaboracao->latitude = $row->latitude;
            $colaboracao->longitude = $row->longitude;
            $colaboracao->areaUnidadeProdutiva = $row->areaUnidadeProdutiva ." ". $row->unidadeAreaUnidadeProdutiva;
            $colaboracao->areaCultivada = $row->areaCultivada ." ". $row->unidadeAreaCultivada;
            $colaboracao->nascenteCorrego = $row->nascenteCorrego;
            $colaboracao->produtosCultivados = $row->produtosCultivados;
            if($colaboracao->produtosCultivados !== "" || $colaboracao->produtosCultivados !== null)
                $colaboracao->produtosCultivados .= ', '.$row->produtosCultivadosOutros;
            else
                $colaboracao->produtosCultivados = $row->produtosCultivadosOutros;
            $colaboracao->caracteristicasProducao = $row->caracteristicasProducao;
            if($colaboracao->caracteristicasProducao !== "" || $colaboracao->caracteristicasProducao !== null)
                $colaboracao->caracteristicasProducao .= ', '.$row->caracteristicasProducaoOutros;
            else
                $colaboracao->caracteristicasProducao = $row->caracteristicasProducaoOutros;
            $colaboracao->finalidadeProducao = $row->finalidadeProducao;
            $colaboracao->principalFonteRenda = $row->principalFonteRenda;
            $colaboracao->assistenciaTecnica = $row->assistenciaTecnica;
            $colaboracao->CAR_regularizado = $row->CAR_regularizado;
            $colaboracao->possuiDAP = $row->possuiDAP;
            $colaboracao->colaboradoresNaUnidade = $row->colaboradoresNaUnidade;
            $colaboracao->permiteVisitantes = $row->permiteVisitantes;
            $colaboracao->sugestoes_criticas_comentarios = $row->sugestoes_criticas_comentarios;
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
            $colaboracao->usuario = $user;
            array_push($colaboracoes,$colaboracao);
        }
        return $colaboracoes;   
    }
}
