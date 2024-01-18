<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuario
 *
 * @author d827421
 */

class Usuario {
    //put your code here
    private $nome;
    private $email;
    private $endereco;
    private $cep;
    private $instituicao;
    private $datanasc;
    private $sexo;
    private $raca;
    private $token;
    private $id;
    private $validado;
    private $comentarios;

    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
}
