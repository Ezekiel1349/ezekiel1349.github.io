const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');
const mm = require('music-metadata');

const mp3Dir = path.join(__dirname, 'mp3');
const outputFile = path.join(__dirname, 'playlist.json');

// Intentar leer con ffprobe como fallback
function getMetadataWithFFprobe(filePath) {
   try {
      const cmd = `ffprobe -v quiet -print_format json -show_format "${filePath}"`;
      const output = execSync(cmd, { encoding: 'utf8' });
      const data = JSON.parse(output);
      const tags = data.format.tags || {};

      return {
         artist: tags.artist || tags.ARTIST || null,
         title: tags.title || tags.TITLE || null,
         album: tags.album || tags.ALBUM || null,
         year: tags.date || tags.DATE || null,
         duration: parseFloat(data.format.duration) || 0
      };
   } catch (err) {
      return null;
   }
}

async function generatePlaylist() {
   try {
      const files = fs.readdirSync(mp3Dir)
         .filter(file => file.endsWith('.mp3'));

      const playlist = [];

      console.log(`📝 Leyendo metadatos de ${files.length} archivos...`);

      for (const filename of files) {
         const filePath = path.join(mp3Dir, filename);

         // Intentar con music-metadata primero
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
            // Intentar con ffprobe
            console.log(`   ⚙️ ${filename} - Intentando con ffprobe...`);
            const ffprobeData = getMetadataWithFFprobe(filePath);

            if (ffprobeData && (ffprobeData.artist || ffprobeData.title)) {
               playlist.push({
                  filename: filename,
                  artist: ffprobeData.artist || 'Artista Desconocido',
                  title: ffprobeData.title || filename.replace('.mp3', ''),
                  album: ffprobeData.album || '',
                  year: ffprobeData.year || null,
                  duration: ffprobeData.duration || 0
               });
               console.log(`   ✓ (ffprobe) ${ffprobeData.artist || '?'} - ${ffprobeData.title || filename}`);
            } else {
               // Si todo falla, usar el nombre del archivo
               console.log(`   ❌ ${filename} - Sin metadatos válidos`);
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
