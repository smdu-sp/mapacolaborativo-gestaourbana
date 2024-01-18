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
abstract class AprovacaoDAO{
    protected $formUser;
    
 /**
 * a função getAllByStatus($status) a função de retornar todos os comentários ou propostas(Dependerá do contexto da classe de instancia) de acordo com o critério de status:
 * S - Aprovados
 * N - Pendentes
 * A - Arquivados
 *  */
    public abstract function getAllByStatus($status);
    public abstract function getCountByStatus($status);
    
    public static function updateColaboracaoStatus($status,$id){
        global $wpdb;
        $query = "UPDATE wp_cf7dbplugin_submits SET field_value = '%s' WHERE submit_time = '%s'
                  AND field_name = 'plataformaStatus'";
        $wpdb->query($wpdb->prepare($query,$status,$id));
    }
    
    public static function updateLatitudeLongitude($id,$lat,$lon){
        global $wpdb;
        $query = "UPDATE wp_cf7dbplugin_submits SET field_value = '%s' WHERE submit_time = '%s'
                  AND field_name = '%s'";
        $wpdb->query($wpdb->prepare($query,$lat,$id,'latitude'));
        $wpdb->query($wpdb->prepare($query,$lon,$id,'longitude'));
    }



}
