<?php


/**
 * Classe responsável pelas interações com o Banco de dados condizentes ao
 * objeto Proposta.
 *
 * @author SMDU
 */
require_once 'IPropostaDAO.php';
require_once dirname(dirname(__FILE__)) . '/entity/Proposta.php';
require_once dirname(dirname(__FILE__)) . '/entity/Usuario.php';
class PropostaDAO implements IPropostaDAO{
    private $formProposta;
    private $formUser;
    function PropostaDAO($formProposta,$formUser){
        $this->formProposta = $formProposta;
        $this->formUser = $formUser;
    }
    /**
     * 
     * Retorna todas as Propostas que foram aprovadas em moderação.
     * @return array $propostas: Array de Propostas Aprovadas.
     */
    public function getPropostasApproved() {
        $propostas = [];
        $query = "SELECT * FROM
            (SELECT * FROM(SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), '%b %e, %Y  %l:%i %p') AS Submitted
            , submit_time
            , MAX(IF(field_name='author_mail', field_value, NULL )) AS 'author_mail'
            , MAX(IF(field_name='latitude', field_value, NULL )) AS 'latitude'
            , MAX(IF(field_name='longitude', field_value, NULL )) AS 'longitude'
            , MAX(IF(field_name='colaboracao', field_value, NULL )) AS 'colaboracao'
            , MAX(IF(field_name='titulo', field_value, NULL )) AS 'titulo'
            , MAX(IF(field_name='plataformaStatus', field_value, NULL )) AS 'plataformaStatus'
            , MAX(IF(field_name='plataformaApoioNumeroApoios', field_value, NULL )) AS 'plataformaApoioNumeroApoios'
            , MAX(IF(field_name='coordsPerimetro', field_value, NULL )) AS 'coordsPerimetro'
            FROM wp_cf7dbplugin_submits WHERE form_name = '".$this->formProposta."' GROUP BY submit_time) 
            AS A WHERE A.plataformaStatus = 'S') AS B JOIN (SELECT
            DATE_FORMAT(FROM_UNIXTIME(submit_time), '%b %e, %Y  %l:%i %p') AS Submitted
            , submit_time AS 'idUser'
            , MAX(IF(field_name='nome', field_value, NULL )) AS 'usuarioNome'
            , MAX(IF(field_name='emailCadastro', field_value, NULL )) AS 'usuarioEmail'
            , MAX(IF(field_name='instituicao', field_value, NULL )) AS 'usuarioInstituicao'
            , MAX(IF(field_name='endereco', field_value, NULL )) AS 'usuarioEndereco'
            , MAX(IF(field_name='datanasc', field_value, NULL )) AS 'datanasc'
            , MAX(IF(field_name='CEP', field_value, NULL )) AS 'usuarioCEP'
            FROM wp_cf7dbplugin_submits WHERE form_name = '".$this->formUser."' GROUP BY submit_time) AS C
         	ON B.author_mail = C.usuarioEmail";
        global $wpdb;
        //echo $query;
        $results = $wpdb->get_results($query);
        foreach($results as $row){
            $proposta = new Proposta();
            $autor = new Usuario();
            $autor->id = $row->idUser;
            $autor->nome = $row->usuarioNome;
            $autor->email = $row->usuarioEmail;
            $autor->instituicao = $row->usuarioInstituicao;
            $autor->endereco = $row->usuarioEndereco;
            $autor->cep = $row->usuarioCEP;
            $autor->datanasc = $row->datanasc;
            $proposta->id = $row->submit_time;
            $proposta->autor = $autor;
            $proposta->submitted = $row->Submitted;
            $proposta->latitude = $row->latitude;
            $proposta->longitude = $row->longitude;
            $proposta->colaboracao = $row->colaboracao;
            $proposta->titulo = $row->titulo;
            $proposta->status = $row->plataformaStatus;
            $proposta->feature = $row->coordsPerimetro;
            $proposta->numApoios = $row->plataformaApoioNumeroApoios;
            array_push($propostas, $proposta);
        }
        return $propostas;
    }
}
