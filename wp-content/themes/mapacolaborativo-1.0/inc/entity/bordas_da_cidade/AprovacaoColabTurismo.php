<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AprovacaoColabTurismo
 *
 * @author d827421
 */
require_once dirname(dirname(__FILE__)) . '/Aprovacao.php';
class AprovacaoColabTurismo extends Aprovacao{
    private $colabTurismo;
    function AprovacaoColabTurismo($usuario,$colabTurismo){
        $this->colabTurismo =  $colabTurismo;
        $this->usuario = $usuario;
    }
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
}
