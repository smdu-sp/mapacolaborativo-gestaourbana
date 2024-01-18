<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AprovacaoColabProdutor
 *
 * @author d827421
 */
require_once dirname(dirname(__FILE__)) . '/Aprovacao.php';
class AprovacaoColabProdutor extends Aprovacao{
    private $colabProdutor;
    function AprovacaoColabProdutor($usuario,$colabProdutor){
        $this->colabProdutor =  $colabProdutor;
        $this->usuario = $usuario;
    }
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
}
