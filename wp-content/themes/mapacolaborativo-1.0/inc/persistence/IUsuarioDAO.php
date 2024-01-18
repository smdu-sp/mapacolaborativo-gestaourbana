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
interface IUsuarioDAO {
    public function getCountEmail($email);
    public function updateEmail($submit_time);
    
}
