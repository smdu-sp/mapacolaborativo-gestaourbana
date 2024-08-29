<?php
/*
 * Template Name: Envio - Bairro Conectado
 */

header('Content-Type: application/json; charset=utf-8');
global $wpdb;
$tableEnvios = 'bairro_conectado_envios';
$tableContribuicoes = 'bairro_conectado_contribuicoes';

include_once 'module_ip_address.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['escolhas']) && count(json_decode(stripslashes($_POST['escolhas']), true)) > 0) {
  $escolhas = json_decode(stripslashes($_POST['escolhas']), true);
  $ip = getRealUserIP();

  $dataEnvios = [
    'fase' => 1,
    'ip_address' => $ip
  ];

  $wpdb->insert($tableEnvios, $dataEnvios);
  $idEnvio = $wpdb->insert_id;

  foreach ($escolhas as $arr) {
    $dataContribuicoes = [
      'id_envio' => $idEnvio,
      'rota' => $arr[0],
      'latitude' => $arr[1],
      'longitude' => $arr[2],
      'opcao' => $arr[3],
    ];

    $wpdb->insert($tableContribuicoes, $dataContribuicoes);
  }

  echo json_encode(['status' => 200]);

} else {
  http_response_code(500);
  echo json_encode(['status' => 500]);
}
