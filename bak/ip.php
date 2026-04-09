<?php
header('Content-Type: application/json; charset=utf-8');

$keys = ['HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_REAL_IP','REMOTE_ADDR'];
$ip = null;
foreach($keys as $k){
    if(!empty($_SERVER[$k])){
        $parts = explode(',', $_SERVER[$k]);
        $ip = trim($parts[0]);
        break;
    }
}
echo json_encode([
  'observed_by_server' => $ip,
  'is_ipv4' => filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false,
  'is_ipv6' => filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false
]);
