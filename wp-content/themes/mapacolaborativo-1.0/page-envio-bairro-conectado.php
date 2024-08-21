<?php
/*
 * Template Name: Envio - Bairro Conectado
 */

header('Content-Type: application/json; charset=utf-8');
global $wpdb;
$tableEnvios = 'bairro_conectado_envios';
$tableContribuicoes = 'bairro_conectado_contribuicoes';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['escolhas'])) {
  $escolhas = json_decode(stripslashes($_POST['escolhas']), true);
  $ip = getRealUserIP();

  $dataEnvios = ['ip_address' => $ip];

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
  echo json_encode((object)[]);
}

function getRealUserIp(){
  if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]) && filter_var($_SERVER["HTTP_CF_CONNECTING_IP"], FILTER_VALIDATE_IP)) {
    return $_SERVER["HTTP_CF_CONNECTING_IP"];
  } else if (!empty($_SERVER['HTTP_X_REAL_IP']) && filter_var($_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
    return $_SERVER['HTTP_X_REAL_IP'];
  } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
    return $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else if (!empty($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
    return $_SERVER['REMOTE_ADDR'];
  }
  
  return "1.1.1.1";
}
?>
