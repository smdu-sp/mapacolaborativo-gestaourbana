<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ColabProdutor
 *
 * @author d827421
 */
require_once dirname(dirname(__FILE__)) . '/Colaboracao.php';
class ColabProdutor extends Colaboracao{
    private $areaUnidadeProdutiva;    
    private $areaCultivada;
    private $nascenteCorrego;
    private $produtosCultivados;
    private $caracteristicasProducao;
    private $finalidadeProducao;
    private $principalFonteRenda;
    private $assistenciaTecnica;
    private $car_regularizado;
    private $possuiDAP;
    private $colaboradoresNaUnidade;
    private $permiteVisitantes;
    private $sugestoes_criticas_comentarios;
    private $imagem;
    
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
}
