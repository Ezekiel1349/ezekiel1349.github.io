# Reproductor de Música - Embriogenesis

## ¿Cómo funciona?

El reproductor lee automáticamente los metadatos ID3 de tus archivos MP3 (artista, título, álbum, duración) y permite ordenarlos de diferentes formas.

## Instalación

```bash
npm install
```

## Actualizar la lista de canciones

Cuando agregues o elimines archivos MP3 en la carpeta `mp3/`, ejecuta:

```bash
npm run generate
```

El script automáticamente:
1. Intenta leer metadatos con `music-metadata`
2. Si falla, usa `ffprobe` (FFmpeg) como fallback
3. Genera `playlist.json` con todos los metadatos

**Requisito:** FFmpeg debe estar instalado en tu sistema.

## Antes de subir a Cloudflare Pages

1. Agrega tus archivos MP3 a la carpeta `mp3/`
2. Ejecuta `npm run generate` para generar el playlist.json
3. Sube todo el contenido (HTML, JSON y MP3)

## Funcionalidades

- ✅ Lectura automática de metadatos ID3
- ✅ Ordenar por: Artista, Título o Álbum
- ✅ Loop infinito de toda la playlist
- ✅ Muestra duración de cada canción
- ✅ No indexable por buscadores
- ✅ Fallback a ffprobe para archivos problemáticos
- 🔒 Protección con contraseña simple (cambia en index.html: `CORRECT_PASSWORD`)

## Protección con Contraseña

La página incluye protección con contraseña. Para cambiarla:

1. Abre `index.html`
2. Busca: `const CORRECT_PASSWORD = 'mimusica2026';`
3. Cambia `'mimusica2026'` por tu contraseña
4. Guarda y sube a Cloudflare Pages

**Contraseña por defecto:** `mimusica2026`

## Archivos importantes

- `index.html` - Reproductor web
- `playlist.json` - Lista generada automáticamente con metadatos
- `generate-playlist.js` - Script principal (music-metadata)
- `generate-playlist-ffprobe.js` - Script alternativo (ffprobe/FFmpeg)
- `package.json` - Dependencias
- `mp3/` - Carpeta con los archivos de audio
