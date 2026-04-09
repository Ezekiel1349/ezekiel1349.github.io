<?php
$dir = __DIR__ . '/mp3';
$files = array_merge(
   glob($dir . '/*.mp3'),
   glob($dir . '/*.wav')
);

?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <title>Embriogenesis</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <div class="container py-4">
      <div class="row">
         <div class="col-lg-6 mx-auto">
            <img src="img/logo_n.png?v=1" class="d-block mx-auto img-fluid mb-3" style="max-width: 400px">
            <!-- TEMA ACTIVO -->
            <h5 id="current-title" class="fw-bold mb-3 text-center">
               Selecciona un tema
            </h5>
            <!-- REPRODUCTOR -->
            <audio id="main-player" controls style="width:100%;" preload="metadata"></audio>
            <!-- PLAYLIST -->
            <ul class="list-group mt-4" id="playlist">
               <?php foreach ($files as $file):
                  $name = basename($file);
               ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center playlist-item"
                     data-src="mp3/<?php echo $name; ?>"
                     data-title="<?php echo $name; ?>"
                     style="cursor:pointer">
                     <span><?php echo $name; ?></span>
                     <span class="badge bg-secondary duration">--:--</span>
                  </li>
               <?php endforeach; ?>
            </ul>
         </div>
      </div>
   </div>
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script>
      jQuery(function($) {
         const player = $('#main-player')[0];
         const items = $('.playlist-item');
         let currentIndex = -1;
         function playTrack(index) {
            const item = items.eq(index);
            if (!item.length) return;
            currentIndex = index;
            items.removeClass('active');
            item.addClass('active');
            const src = item.data('src');
            const title = item.data('title');
            $('#current-title').text(title);
            player.pause();
            player.src = src;
            player.load();
            player.play();
         }
         // Click en lista
         items.on('click', function() {
            playTrack(items.index(this));
         });
         // Autoplay siguiente
         player.addEventListener('ended', function() {
            const next = currentIndex + 1;
            if (next < items.length) {
               playTrack(next);
            }
         });
         // Obtener duración real
         items.each(function() {
            const li = $(this);
            const audio = new Audio(li.data('src'));
            audio.addEventListener('loadedmetadata', function() {
               const d = audio.duration;
               const min = Math.floor(d / 60);
               const sec = Math.floor(d % 60).toString().padStart(2, '0');
               li.find('.duration').text(min + ':' + sec);
            });
         });
      });
   </script>
</body>
</html>