<?php

/**
 * 
 * A classe AprovacaoComentario é uma especialização de Aprovacao, na qual 
 * ela tem a responsabilidade de representar um objeto de aprovação de comentários
 * @author SMDU
 */
require_once 'Aprovacao.php';
class AprovacaoComentario extends Aprovacao{
    private $comentario;
    /**
     * Método Construtor
     * @param Usuario $usuario - Objeto do tipo usuário
     * @param Comentario $comentario - Objeto do tipo comentário
    */
    function AprovacaoComentario($usuario,$comentario){
        $this->comentario = $comentario;
        $this->usuario = $usuario;
    }
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
}
