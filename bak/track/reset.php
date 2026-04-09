<?php
// track/reset.php
$KEY = 'CAMBIA_ESTA_CLAVE';

if (($_GET['key'] ?? '') !== $KEY) {
  http_response_code(403);
  echo "Forbidden";
  exit;
}

$file = __DIR__ . '/opens.json';
if (file_exists($file)) unlink($file);

echo "OK";
