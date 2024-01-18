<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ColabTurismo
 *
 * @author d827421
 */
require_once dirname(dirname(__FILE__)) . '/Colaboracao.php';
class ColabTurismo extends Colaboracao{
    private $tipoEmpreendimento;
    private $tipoAcessoEmpreendimento;
    private $principalFonteRenda;
    private $colaboradores;
    private $principaisAtrativos;
    private $imagem;
    
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
}
