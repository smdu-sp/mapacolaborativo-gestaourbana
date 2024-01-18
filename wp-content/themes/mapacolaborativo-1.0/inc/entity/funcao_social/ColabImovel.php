<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ColabImovel
 *
 * @author d827421
 */
require_once dirname(dirname(__FILE__)) . '/Colaboracao.php';
class ColabImovel extends Colaboracao{
    private $logradouro;
    private $numeroLogradouro;
    private $latitude;
    private $longitude;
    private $pontoReferencia;
    private $caracteristicaImovel;
    private $tipoAprovacao;
    private $possesDoImovel;
    private $tempoInutilizado;
    
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
}
