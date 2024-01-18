<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AprovacaoProposta
 *
 * @author d827421
 */
require_once 'Aprovacao.php';
class AprovacaoProposta extends Aprovacao{
    private $proposta;
    function AprovacaoProposta($usuario,$proposta){
        $this->proposta = $proposta;
        $this->usuario = $usuario;
    }
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }

}
