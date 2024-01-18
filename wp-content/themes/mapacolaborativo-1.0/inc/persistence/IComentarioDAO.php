<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author d827421
 */
interface IComentarioDAO {
    public function getAllCommentsApproved($idPerimetro,$regiaoComentada);
    public function getCountAllCommentsApproved($idPerimetro, $submit_time_comentario);
    public function getAllCommentsRecentsApproved();
    public function getCountApoioComment($email,$id);
}
