<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Perimetro
 *
 * @author d827421
 */


class Perimetro {
    //put your code here
    private $id;
    private $nome;
    private $tipo;
    private $localizacao;
    private $acao;
    private $caracterizacao;
    private $subsEnvolvidas;
    private $secEnvolvidas;
    private $agentegov;
    private $agentengov;
    private $objetivos;
    private $diretrizes;
    private $observacoes;
    private $comentarios;
    private $numApoios;


    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
    
    
}
