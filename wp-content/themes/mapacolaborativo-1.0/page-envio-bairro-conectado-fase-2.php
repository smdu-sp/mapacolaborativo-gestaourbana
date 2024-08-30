<?php
/*
 * Template Name: Envio - Fase 2 Bairro Conectado
 */

header('Content-Type: application/json; charset=utf-8');
global $wpdb;
$tableEnvios = 'bairro_conectado_envios';
$tableContribuicoes = 'bairro_conectado_contribuicoes_fase_2';

include_once 'module_ip_address.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contribuicoes']) && count(json_decode(stripslashes($_POST['contribuicoes']), true)) > 0) {
  $contribuicoes = json_decode(stripslashes($_POST['contribuicoes']), true);
  $ip = getRealUserIP();

  $dataEnvios = [
    'fase' => 2,
    'ip_address' => $ip
  ];

  $wpdb->insert($tableEnvios, $dataEnvios);
  $idEnvio = $wpdb->insert_id;

  foreach ($contribuicoes as $obj) {
    foreach ($obj['escolhas'] as $escolha) {
      $dataContribuicoes = [
        'id_envio' => $idEnvio,
        'id_escadaria' => $obj['idEscadaria'],
        'rota' => $obj['rota'],
        'escadaria' => $obj['numEscadaria'],
        'opcao' => $escolha,
      ];
  
      $wpdb->insert($tableContribuicoes, $dataContribuicoes);
    }
  }

  echo json_encode(['status' => 200]);

} else {
  http_response_code(500);
  echo json_encode(['status' => 500]);
}
