<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SimpleCollection
 *
 *
 * @author d827421
 */
class SimpleCollection {
    private $collection = array();
    
    public function addItem($obj, $key = null) {
            if (isset($this->$collection[$key])) {
                throw new KeyHasUseException("Key $key already in use.");
            }
            else {
                $this->$collection[$key] = $obj;
            }
    }

    public function deleteItem($key) {
        if (isset($this->$collection[$key])) {
            unset($this->$collection[$key]);
        }
        else {
            throw new KeyInvalidException("Invalid key $key.");
        }
    }

    public function getItem($key) {
        if (isset($this->$collection[$key])) {
            return $this->$collection[$key];
        }
        else {
            throw new KeyInvalidException("Invalid key $key.");
        }
    }
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

    public function __get($atrib){
        return $this->$atrib;
    }
    
    public function keys() {
        return array_keys($this->$collection);
    }
    public function length() {
        return count($this->$collection);
    }
    public function keyExists($key) {
        return isset($this->$collection[$key]);
    }
}
