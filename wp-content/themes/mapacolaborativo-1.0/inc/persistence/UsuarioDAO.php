<?php


/**
 * Classe responsável pelas interações com o Banco de dados condizentes ao
 * objeto Usuario.
 *
 * @author SMDU
 */
require_once 'IUsuarioDAO.php';
require_once dirname(dirname(__FILE__)) . '/entity/Usuario.php';
class UsuarioDAO implements IUsuarioDAO{
    private $formUser;
    private $formApoio;
    function UsuarioDAO($formUser){
        $this->formUser = $formUser;
    }
    /**
     * Retorna a quantidade de registros encontrados que tenham o email condizente ao
     * que foi passado como parametro e se este já foi validado pelo usuário.
     * 
     * @param string $email
     * @return int $count - Número de registros encontrados com o email em questão
     * 
     */
    public function getCountEmail($email) {
        global $wpdb;
        $query = "select count(*) from (select submit_time, MAX(IF(field_name='emailCadastro', field_value, NULL )) as email,
		 MAX(IF(field_name='plataformaApoioConfirmadoEmail', field_value, NULL )) as confirmadoEmail 
		 from wp_cf7dbplugin_submits where form_name = '".$this->formUser."' GROUP BY submit_time) as a
		 where a.email = '%s' and confirmadoEmail = 'S'";
        $count = $wpdb->get_var($wpdb->prepare($query,$email));
        return $count;
    }
    /**
     * Atualiza o status de confirmação do email do usuário.
     * @param float $submit_time
     */
    public function updateEmail($submit_time) {
        $queryUpdateConfirmadoEmail = "
                UPDATE wp_cf7dbplugin_submits
                SET field_value = 'S'
                WHERE submit_time = '%s'
                  AND field_name = 'plataformaApoioConfirmadoEmail'
          ";
        global $wpdb;
        $updateConfirmadoEmail = $wpdb->query($wpdb->prepare($queryUpdateConfirmadoEmail,$submit_time));
        if($updateConfirmadoEmail){
            echo "<br><h3>Usuário validado com sucesso!</h3><br>";
            echo "Clique <a href='../bordas-da-cidade/'>aqui</a> para voltar para a plataforma de participação do bordas da cidade.";
            echo "<br><br>Clique <a href='../planos-regionais/'>aqui</a> para voltar para a plataforma de participação dos planos regionais das subprefeituras.";
            echo "<br><br>Clique <a href='../funcao-social/'>aqui</a> para voltar para a plataforma de participação da função social da propriedade.";
        }else{
            //echo "Deu ruim";
            die();
        }
    }
    /**
     * Retorna o submit_time(id do usuario) pelo token de validação de email
     * @param string $token: Token a ser pesquisado
     * @return int $submitTime
     */
    public function getSubmitByToken($token){
        $query = "SELECT a.submit_time FROM( SELECT submit_time,
		 MAX(IF(field_name='plataformaApoioConfirmadoEmail', field_value, NULL )) AS 'confirmadoEmail',
		 MAX(IF(field_name='plataformaApoioToken', field_value, NULL )) AS 'userToken' FROM wp_cf7dbplugin_submits 
		 WHERE form_name='".$this->formUser."' GROUP BY submit_time) AS a  WHERE a.confirmadoEmail = 'N'
		 AND a.userToken = '%s'";
        global $wpdb;
        $submitTime = $wpdb->get_var($wpdb->prepare($query,$token));
        return $submitTime;
    }
    /**
     * Retorna a quantidade de Apoios de um email(usuário) em uma colaboração.
     * @global type $wpdb
     * @param string $email
     * @param string $idColaboracao
     * @return int $count1: Número de Apoios de um email em uma colaboração
     */
    public function getCountApoioColab($email, $idColaboracao) {
        global $wpdb;
        $query = "select count(*) from (select
		 MAX(IF(field_name='emailApoio', field_value, NULL )) as email
		 ,MAX(IF(field_name='idColab', field_value, NULL )) as idColab
		 ,submit_time
		from wp_cf7dbplugin_submits where form_name = '".$this->formApoio."' group by submit_time) as A where
		A.email = '%s' and A.idColab = '%s'";
        $statement = $wpdb->prepare($query,$email,$idColaboracao);
        $count1 = $wpdb->get_var($statement);
        return $count1;
    }
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }

}
