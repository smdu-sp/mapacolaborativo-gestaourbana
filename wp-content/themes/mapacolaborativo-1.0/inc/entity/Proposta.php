<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Proposta
 *
 * @author d827421
 */
require_once 'Colaboracao.php';
class Proposta extends Colaboracao{
    private $latitude;
    private $longitude;
    private $feature;

    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
    public function jsonSerialize() {
        return [
            'proposta' => $this
        ];
    }
    public static function jsonSerializeObjPropostas($propostas) {
        return [
            'propostas' => $propostas
        ];
    }
}
