const fs = require('fs');
const path = require('path');
const mm = require('music-metadata');

const mp3Dir = path.join(__dirname, 'mp3');
const outputFile = path.join(__dirname, 'playlist.json');

async function generatePlaylist() {
   try {
      const files = fs.readdirSync(mp3Dir)
         .filter(file => file.endsWith('.mp3'));

      const playlist = [];

      console.log(`📝 Leyendo metadatos de ${files.length} archivos...`);

      for (const filename of files) {
         const filePath = path.join(mp3Dir, filename);

         try {
            const metadata = await mm.parseFile(filePath, {
               skipCovers: true,
               duration: true,
               skipPostHeaders: true
            });
            const { common, format } = metadata;

            playlist.push({
               filename: filename,
               artist: common.artist || 'Artista Desconocido',
               title: common.title || filename.replace('.mp3', ''),
               album: common.album || '',
               year: common.year || null,
               duration: format.duration || 0
            });

            console.log(`   ✓ ${common.artist || '?'} - ${common.title || filename}`);
         } catch (err) {
            // Intentar leer al menos el formato básico
            try {
               const metadata = await mm.parseFile(filePath, {
                  skipCovers: true,
                  duration: true,
                  native: false
               });
               const { common, format } = metadata;

               playlist.push({
                  filename: filename,
                  artist: common.artist || 'Artista Desconocido',
                  title: common.title || filename.replace('.mp3', ''),
                  album: common.album || '',
                  year: common.year || null,
                  duration: format.duration || 0
               });

               console.log(`   ⚠ ${filename} - Metadatos parciales: ${common.artist || '?'} - ${common.title || filename}`);
            } catch (err2) {
               // Si todo falla, usar el nombre del archivo
               console.log(`   ❌ ${filename} - ERROR: ${err2.message}`);
               playlist.push({
                  filename: filename,
                  artist: 'Artista Desconocido',
                  title: filename.replace('.mp3', ''),
                  album: '',
                  year: null,
                  duration: 0
               });
            }
         }
      }

      // Ordenar por artista, luego por título
      playlist.sort((a, b) => {
         const artistCompare = a.artist.localeCompare(b.artist);
         if (artistCompare !== 0) return artistCompare;
         return a.title.localeCompare(b.title);
      });

      fs.writeFileSync(outputFile, JSON.stringify(playlist, null, 2));
      console.log(`\n✅ Generado playlist.json con ${playlist.length} archivos`);
      console.log(`📁 Guardado en: ${outputFile}`);
   } catch (error) {
      console.error('❌ Error:', error.message);
      process.exit(1);
   }
}

generatePlaylist();
