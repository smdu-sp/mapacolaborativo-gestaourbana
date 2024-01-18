<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ComentarioDAO
 * 
 * @author d827421
 */
require_once 'IComentarioDAO.php';
require_once dirname(dirname(__FILE__)) . '/entity/Comentario.php';
require_once dirname(dirname(__FILE__)) . '/entity/Usuario.php';
class ComentarioDAO implements IComentarioDAO{
    /** 
     * Retorna todos os comentários aprovados.
     * O atributo $submit_time_comentario é booleano, caso true
     * ele interpretará o atributo $idPerimetro como um submit_time do comentario,
     * do contrário, será interpretado como id(ou submit_time) do perimetro.
     * O atributo $regiaoComentada filtra a busca por tipo de comentario, se a String for vazia, retorna
     * todos os comentarios do perimetro ou, caso o segundo parametro for true, retornará o comentario.
     * Retorno = Atributo do tipo List - Classe: SimpleCollection  */
    private $formComment;
    private $formApoioComment;
    private $formUser;
    function ComentarioDAO($formComment,$formUser,$formApoioComment){
        $this->formComment = $formComment;
        $this->formUser = $formUser;
        $this->formApoioComment = $formApoioComment;
    }
    public function getAllCommentsApproved($idFeature, $regiaoComentada) {
        switch($regiaoComentada){
            case "Todo o Texto": $param2 = " AND a.regiaoComentada = 'Todo o Texto'";
                                 break;
            case "Caracterização": $param2 = " AND a.regiaoComentada = 'Caracterização'";
                                    break;
            case "Objetivos": $param2 = " AND a.regiaoComentada = 'Objetivos'";
                                 break;
            case "Diretrizes": $param2 = " AND a.regiaoComentada = 'Diretrizes'";
                                 break;
            case "Observação": $param2 = " AND a.regiaoComentada = 'Observação'";
                                 break;
            default:            $param2 = "";
                
        }
        
        $query = "SELECT a.*,b.* FROM (SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), '%s') AS Submitted
            , submit_time
            , MAX(IF(field_name='author_mail', field_value, NULL )) AS 'author_mail'
            , MAX(IF(field_name='regiaoComentada', field_value, NULL )) AS 'regiaoComentada'
            , MAX(IF(field_name='colaboracao', field_value, NULL )) AS 'colaboracao'
            , MAX(IF(field_name='titulo', field_value, NULL )) AS 'titulo'
            , MAX(IF(field_name='concordaDiscorda', field_value, NULL )) AS 'posicionamento'
            , MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
            , MAX(IF(field_name='plataformaApoioNumeroApoios', field_value, NULL )) AS 'plataformaApoioNumeroApoios'
            , MAX(IF(field_name='idFeature', field_value, NULL )) AS 'idFeature'
            FROM wp_cf7dbplugin_submits
            WHERE form_name = '".$this->formComment."' GROUP BY submit_time)
            AS a JOIN (SELECT  submit_time AS 'idUser'
            , MAX(IF(field_name='nome', field_value, NULL )) AS 'usuarioNome'
            , MAX(IF(field_name='emailCadastro', field_value, NULL )) AS 'usuarioEmail'
            , MAX(IF(field_name='instituicao', field_value, NULL )) AS 'usuarioInstituicao'
            , MAX(IF(field_name='endereco', field_value, NULL )) AS 'usuarioEndereco'
            , MAX(IF(field_name='CEP', field_value, NULL )) AS 'usuarioCEP'
            , MAX(IF(field_name='datanasc', field_value, NULL )) AS 'datanasc'
            FROM wp_cf7dbplugin_submits
            WHERE form_name = '".$this->formUser."' GROUP BY submit_time) AS b 
            ON a.author_mail = b.usuarioEmail WHERE a.plataformaStatus = 'S' AND a.idFeature = '%s' ".$param2."
        ORDER BY a.plataformaApoioNumeroApoios DESC";
        //ON a.author_mail = b.email WHERE a.plataformaStatus = 'S' and ".$param." = '".$idPerimetro."'".$param2."
        global $wpdb;
        $statement = $wpdb->prepare($query,"%b %e, %Y  %l:%i %p",$idFeature);
        $results = $wpdb->get_results($statement);
        $comentarios = [];
        foreach ($results as $row){
            //echo json_encode($row);
            $autor = new Usuario();
            $comentario = new Comentario();
            $autor->id = $row->idUser;
            $autor->nome = $row->usuarioNome;
            $autor->email = $row->usuarioEmail;
            $autor->instituicao = $row->usuarioInstituicao;
            $autor->endereco = $row->usuarioEndereco;
            $autor->cep = $row->usuarioCEP;
            $autor->datanasc = $row->datanasc;
            $comentario->id = $row->submit_time;
            $comentario->autor = $autor;
            $comentario->author_mail = $row->author_mail;
            $comentario->author = $row->nomeUser;
            $comentario->instituicao = $row->instituicao;
            $comentario->posicionamento = $row->posicionamento;
            $comentario->submitted = $row->Submitted;
            $comentario->regiaoDescricaoPerimetro = $row->regiaoComentada;
            $comentario->titulo = $row->titulo;
            $comentario->comentario = $row->colaboracao;
            $comentario->status = $row->plataformaStatus;
            $comentario->idFeature = $row->idFeature;
            $comentario->numApoios = $row->plataformaApoioNumeroApoios;
            array_push($comentarios, $comentario);
        }
        return $comentarios;
    }
    /** 
     * Retorna todos os comentários
     * Retorno = Atributo do tipo Array de Objetos - Classe: Comentario  */
    public function getAllCommentsRecentsApproved(){
        $query = "
         SELECT a.*,b.nomeUser,b.instituicao FROM (SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), '%b %e, %Y  %l:%i %p') AS Submitted
            , submit_time
            , MAX(IF(field_name='author_mail', field_value, NULL )) AS 'author_mail'
            , MAX(IF(field_name='regiaoComentada', field_value, NULL )) AS 'regiaoComentada'
            , MAX(IF(field_name='colaboracao', field_value, NULL )) AS 'colaboracao'
            , MAX(IF(field_name='titulo', field_value, NULL )) AS 'titulo'
            , MAX(IF(field_name='concordaDiscorda', field_value, NULL )) AS 'posicionamento'
            , MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
            , MAX(IF(field_name='plataformaApoioNumeroApoios', field_value, NULL )) AS 'plataformaApoioNumeroApoios'
            , MAX(IF(field_name='idFeature', field_value, NULL )) AS 'idFeature'
            FROM wp_cf7dbplugin_submits
            WHERE form_name = '".$this->formComment."' GROUP BY submit_time)
            AS a  JOIN (SELECT  submit_time AS 'idUser'
            , MAX(IF(field_name='nome', field_value, NULL )) AS 'usuarioNome'
            , MAX(IF(field_name='email', field_value, NULL )) AS 'usuarioEmail'
            , MAX(IF(field_name='instituicao', field_value, NULL )) AS 'usuarioInstituicao'
            , MAX(IF(field_name='endereco', field_value, NULL )) AS 'usuarioEndereco'
            , MAX(IF(field_name='CEP', field_value, NULL )) AS 'usuarioCEP'
            FROM wp_cf7dbplugin_submits
            WHERE form_name = '".$this->formUser."' GROUP BY submit_time) AS b 
	ON a.author_mail = b.usuarioEmail WHERE a.plataformaStatus = 'S' 
        ORDER BY a.submit_time DESC";
        
        
        
        global $wpdb;
        $results = $wpdb->get_results($query);
        $comentarios = [];
        foreach ($results as $row){
            $autor = new Usuario();
            $comentario = new Comentario();
            $autor->id = $row->idUser;
            $autor->nome = $row->usuarioNome;
            $autor->email = $row->usuarioEmail;
            $autor->instituicao = $row->usuarioInstituicao;
            $autor->endereco = $row->usuarioEndereco;
            $autor->cep = $row->usuarioCEP;
            $comentario->id = $row->submit_time;
            $comentario->author_mail = $row->author_mail;
            $comentario->submitted = $row->Submitted;
            $comentario->author = $row->nomeUser;
            $comentario->instituicao = $row->instituicao;
            $comentario->posicionamento = $row->posicionamento;
            $comentario->regiaoDescricaoPerimetro = $row->regiaoComentada;
            $comentario->titulo = $row->titulo;
            $comentario->comentario = $row->colaboracao;
            $comentario->status = $row->plataformaStatus;
            $comentario->idFeature = $row->idFeature;
            $comentario->numApoios = $row->plataformaApoioNumeroApoios;
            array_push($comentarios, $comentario);
        }
        return $comentarios;
    }
    /** 
     * Retorna a contagem de todos os comentários aprovados.
     * O atributo $submit_time_comentario é booleano, caso true
     * ele interpretará o atributo $idPerimetro como um submit_time do comentario,
     * do contrário, será interpretado como id(ou submit_time) do perimetro.
     * Retorno = $results, lista com relação Região comentada - count
     * $results->regiaoComentada - $results->count  */
    public function getCountAllCommentsApproved($idFeature, $submit_time_comentario) {
        if($submit_time_comentario){
            $param = 'a.submit_time';
        }else{
            $param = 'a.idFeature';
        }
        $query = "
            SELECT a.regiaoComentada,COUNT(*) as cont FROM (SELECT submit_time
                , MAX(IF(field_name='regiaoComentada', field_value, NULL )) AS 'regiaoComentada'
                , MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
                , MAX(IF(field_name='idFeature', field_value, NULL )) AS 'idFeature'
                FROM wp_cf7dbplugin_submits
                WHERE form_name = '".$this->formComment."' GROUP BY submit_time)
                AS a WHERE a.plataformaStatus = 'S' and ".$param." = '".$idFeature." 
                GROUP BY a.regiaoComentada
            ";
        global $wpdb;
        $count = $wpdb->get_results($query);
        return $count;
    }
    public function getCountApoioComment($email, $idComment) {
        global $wpdb;
        $query = "select count(*) from (select
		 MAX(IF(field_name='emailApoio', field_value, NULL )) as email
		 ,MAX(IF(field_name='idComment', field_value, NULL )) as idComment
		 ,submit_time
		from wp_cf7dbplugin_submits where form_name = '".$this->formApoioComment."' group by submit_time) as A where
		A.email = '%s' and A.idComment = '%s'";
        $statement = $wpdb->prepare($query,$email,$idComment);
        $count1 = $wpdb->get_var($statement);
        return $count1;
    }

}
