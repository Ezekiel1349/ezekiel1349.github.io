<?php
date_default_timezone_set('America/Guayaquil');

$id = $_GET['id'] ?? '0';

$ip  = $_SERVER['REMOTE_ADDR'] ?? '';
$now = date('Y-m-d H:i:s');

// 1) Ignorar tus propias aperturas (edita esta lista)
$my_ips = [
  '2800:bf0:25d:efe:dc86:1300:877a:cbd2',
  // agrega aquí tu IPv4 pública si quieres
];

if ($ip && in_array($ip, $my_ips, true)) {
  header("Content-Type: image/png");
  readfile(__DIR__ . '/firma.png');
  exit;
}

// 2) Contador agregado por ID en JSON
$file = __DIR__ . '/opens.json';
$data = [];

if (file_exists($file)) {
  $json = file_get_contents($file);
  $data = json_decode($json, true) ?: [];
}

if (!isset($data[$id])) {
  $data[$id] = [
    'total'  => 0,
    'last'   => null,
    'unique' => 0,
    'ips'    => []
  ];
}

$data[$id]['total']++;
$data[$id]['last'] = $now;

if ($ip) {
  if (!isset($data[$id]['ips'][$ip])) {
    $data[$id]['ips'][$ip] = 0;
    $data[$id]['unique']++;
  }
  $data[$id]['ips'][$ip]++;
}

file_put_contents(
  $file,
  json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
  LOCK_EX
);

// 3) Mostrar tu firma real
header("Content-Type: image/png");
readfile(__DIR__ . '/firma.png');
