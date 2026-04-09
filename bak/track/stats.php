<?php
date_default_timezone_set('America/Guayaquil');

// track/stats.php

$file = __DIR__ . '/opens.json';
$data = file_exists($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$sort   = $_GET['sort'] ?? 'last';  // last | total | unique | id
$dir    = $_GET['dir'] ?? 'desc';   // asc | desc

// filtrar
if ($search !== '') {
  $data = array_filter($data, function($v, $k) use ($search) {
    return stripos($k, $search) !== false;
  }, ARRAY_FILTER_USE_BOTH);
}

// ordenar
$items = [];
foreach ($data as $id => $row) {
  $row['id'] = $id;
  $items[] = $row;
}

usort($items, function($a, $b) use ($sort, $dir) {
  $va = $a[$sort] ?? '';
  $vb = $b[$sort] ?? '';
  if ($sort === 'last') { $va = strtotime($va ?: '1970-01-01'); $vb = strtotime($vb ?: '1970-01-01'); }
  if ($va == $vb) return 0;
  $cmp = ($va < $vb) ? -1 : 1;
  return ($dir === 'asc') ? $cmp : -$cmp;
});

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$totalAll = 0;
$uniqueAll = 0;
foreach ($items as $it) {
  $totalAll += (int)($it['total'] ?? 0);
  $uniqueAll += (int)($it['unique'] ?? 0);
}

?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mail Tracking - Stats</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:20px;}
    .top{display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;margin-bottom:12px}
    .card{border:1px solid #ddd;border-radius:10px;padding:12px;display:inline-block}
    table{border-collapse:collapse;width:100%;margin-top:12px}
    th,td{border:1px solid #ddd;padding:8px;vertical-align:top;font-size:14px}
    th{background:#f7f7f7;text-align:left}
    small{color:#666}
    details{max-width:520px}
    .muted{color:#666}
    a{color:#0b57d0;text-decoration:none}
    a:hover{text-decoration:underline}
    input{padding:8px 10px;border:1px solid #ccc;border-radius:8px}
    button{padding:8px 10px;border:1px solid #ccc;border-radius:8px;background:#fff;cursor:pointer}
    .pill{display:inline-block;padding:2px 8px;border:1px solid #ddd;border-radius:999px;font-size:12px}
  </style>
</head>
<body>

<h2>Mail Tracking</h2>

<div class="top">
  <div class="card">
    <div><strong>Total cargas:</strong> <?= (int)$totalAll ?></div>
    <div><strong>IPs únicas sumadas:</strong> <?= (int)$uniqueAll ?></div>
    <div><small class="muted">Nota: Gmail/Apple pueden cachear y mostrar IPs proxy.</small></div>
  </div>

  <form method="get" class="card">
    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap">
      <label>
        <small class="muted">Buscar ID</small><br>
        <input name="q" value="<?= h($search) ?>" placeholder="eze_001 / cliente_45">
      </label>

      <label>
        <small class="muted">Orden</small><br>
        <input name="sort" value="<?= h($sort) ?>" placeholder="last|total|unique|id" style="width:180px">
      </label>

      <label>
        <small class="muted">Dir</small><br>
        <input name="dir" value="<?= h($dir) ?>" placeholder="asc|desc" style="width:110px">
      </label>

      <div>
        <br>
        <button type="submit">Aplicar</button>
      </div>
    </div>
  </form>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Total</th>
      <th>Únicas</th>
      <th>Última</th>
      <th>IPs (detalle)</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!$items): ?>
      <tr><td colspan="5" class="muted">No hay data todavía.</td></tr>
    <?php else: ?>
      <?php foreach ($items as $it): ?>
        <?php
          $id = $it['id'];
          $total = (int)($it['total'] ?? 0);
          $unique = (int)($it['unique'] ?? 0);
          $last = $it['last'] ?? '';
          $ips = $it['ips'] ?? [];
          arsort($ips);
        ?>
        <tr>
          <td>
            <strong><?= h($id) ?></strong><br>
            <span class="pill"><?= $total ?> loads</span>
          </td>
          <td><?= $total ?></td>
          <td><?= $unique ?></td>
          <td><?= h($last) ?></td>
          <td>
            <details>
              <summary>Ver IPs (<?= count($ips) ?>)</summary>
              <div style="margin-top:8px">
                <?php if (!$ips): ?>
                  <small class="muted">Sin IPs registradas.</small>
                <?php else: ?>
                  <?php foreach ($ips as $ip => $count): ?>
                    <div><code><?= h($ip) ?></code> — <?= (int)$count ?></div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </details>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<p class="muted">
  Tip: usa IDs distintos por destinatario: <code>?id=cliente_001</code>, <code>?id=cliente_002</code>...
</p>

</body>
</html>
