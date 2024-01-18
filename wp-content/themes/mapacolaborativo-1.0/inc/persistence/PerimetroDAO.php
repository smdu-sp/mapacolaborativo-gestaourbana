<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PerimetroDAO
 *
 * @author d827421
 */
require_once 'IPerimetroDAO.php';
require_once dirname(dirname(__FILE__)) . '/entity/Perimetro.php';
class PerimetroDAO implements IPerimetroDAO{
    public function getPerimetro($id) {
        global $wpdb;
        $query = "SELECT * FROM wp_perimetros WHERE idQuino = '%s'";
        $statement = $wpdb->prepare($query,$id);
        $row = $wpdb->get_row($statement);
        $perimetro = new Perimetro();
        $perimetro->id = $row->idQuino;
        $perimetro->nome = $row->nome;
        $perimetro->tipo = $row->tipo;
        $perimetro->localizacao = $row->localizacao;
       // $perimetro->acao = $row->acao;
        $perimetro->caracterizacao = $row->caracterizacao;
        /*$perimetro->subsEnvolvidas = $row->subs;
        $perimetro->secEnvolvidas = $row->sec;
        $perimetro->agentegov = $row->agentegov;
        $perimetro->agentengov = $row->agentengov;*/
        $perimetro->objetivos = $row->objetivos;
        $perimetro->diretrizes = $row->diretrizes;
        //$perimetro->observacoes = $row->observacoes;
        return $perimetro;
    }

    public function getAllPerimetros() {
        global $wpdb;
        $perimetros = [];
        $query = "SELECT * FROM wp_perimetros";
        $results = $wpdb->get_results($query);
        foreach ($results as $row){
            $perimetro = new Perimetro();
            $perimetro->id = $row->idQuino;
            $perimetro->nome = $row->nome;
            $perimetro->localizacao = $row->localizacao;
            $perimetro->tipo = $row->tipo;
            //$perimetro->acao = $row->acao;
            $perimetro->caracterizacao = $row->caracterizacao;
            /*$perimetro->subsEnvolvidas = $row->subs;
            $perimetro->secEnvolvidas = $row->sec;
            $perimetro->agentegov = $row->agentegov;
            $perimetro->agentengov = $row->agentengov;*/
            $perimetro->objetivos = $row->objetivos;
            $perimetro->diretrizes = $row->diretrizes;
            //$perimetro->observacoes = $row->observacoes;
            array_push($perimetros, $perimetro);
        }
        return $perimetros;
    }

}
