<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Colaboracao
 *
 * @author d827421
 */
class Colaboracao {
    protected $id;
    protected $autor;
    protected $instituicao;
    protected $submitted;
    protected $status;
    protected $numApoios;
    protected $posicionamento;
    public static function computarApoioColaboracao($idColaboracao) {
        global $wpdb;
        $query = "UPDATE wp_cf7dbplugin_submits set field_value = field_value + 1 
                 where submit_time = '%s' and field_name = 'plataformaApoioNumeroApoios'";
        $statement = $wpdb->prepare($query,$idColaboracao);
        $wpdb->query($statement);
    }
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
}
