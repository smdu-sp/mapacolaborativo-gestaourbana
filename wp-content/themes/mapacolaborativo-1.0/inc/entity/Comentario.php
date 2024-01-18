<?php


/**
 * Esta Classe representa um comentário feito no sistema.
 * Este tem o atributo idFeature, que representa o objeto em que este comentário está
 * vinculado.
 * @author SMDU
 * 
 */
require_once 'Colaboracao.php';
class Comentario extends Colaboracao{
    private $regiaoDescricaoPerimetro;
    private $titulo;
    private $comentario;
    private $idFeature;

    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
}
