# Logos de tecnologías

SVG alojados en el repo (no hay CDN en runtime). Vite los emite con hash de
contenido y `resources/js/lib/techIcons.ts` los resuelve por el nombre del
archivo, que es el slug del logo.

- **Origen:** [Devicon](https://devicon.dev) (`icons/<marca>/<marca>-original.svg`).
  `ollama.svg`, `openai.svg`, `pwa.svg`, `claude-code.svg`, `v0.svg`, `qwen.svg`, `gemini.svg`, `onedrive.svg`, `google-drive.svg`, `youtube.svg` y `cloudflare.svg` vienen de
  [Simple Icons](https://simpleicons.org), porque Devicon no los tiene.
- **Una variante por marca.** El campo `icon` de la tabla `technologies` acepta
  tanto `laravel-original` como la clase vieja `devicon-laravel-original`, y si
  la variante exacta no está, cae en la de la marca.
- **Se usa la variante `original` (a color), no `plain`**: las `plain` son
  monocromas en el color de la marca y sobre el fondo oscuro del sitio no se leen.
- **Marcas negras u oscuras** (Symfony, MySQL, WordPress, Ollama, OpenAI, PWA, v0, Grok): los
  fills con luminancia < 0.20 se reemplazaron por `#e2e8f0`, que es la versión
  para fondo oscuro que esas marcas publican en sus guías.

Del logo de PWA se usa el dibujo de Simple Icons en blanco, no el oficial de
dos tonos: sus dos colores (`#3d3d3d` y `#5a0fc8`) tienen luminancia 0.05-0.07 y
sobre el fondo del sitio la "W" violeta queda como un hueco oscuro.

`claude-code.svg` va en el naranja de la marca (`#d97757`), que se lee bien sobre oscuro.

`grok.svg` viene de [Lobe Icons](https://lobehub.com/icons) (Simple Icons no lo tiene). `qwen.svg` y `gemini.svg` van en el color de la marca (`#615ced`, `#8e75b2`); `onedrive.svg`, `google-drive.svg`, `youtube.svg` y `cloudflare.svg` también, con `#0078d4`, `#4285f4`, `#ff0000` y `#f38020`.

Para agregar una tecnología: bajá el SVG `original`, revisá que se lea sobre
oscuro (si no, aclarale los fills como arriba), dejalo acá y elegí el logo desde
el panel (Tecnologías → editar).
