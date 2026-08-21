# Logos de tecnologías

SVG alojados en el repo (no hay CDN en runtime). Vite los emite con hash de
contenido y `resources/js/lib/techIcons.ts` los resuelve por el nombre del
archivo, que es el slug del logo.

- **Origen:** [Devicon](https://devicon.dev) (`icons/<marca>/<marca>-original.svg`).
  `ollama.svg`, `openai.svg` y `pwa.svg` vienen de
  [Simple Icons](https://simpleicons.org), porque Devicon no los tiene.
- **Una variante por marca.** El campo `icon` de la tabla `technologies` acepta
  tanto `laravel-original` como la clase vieja `devicon-laravel-original`, y si
  la variante exacta no está, cae en la de la marca.
- **Se usa la variante `original` (a color), no `plain`**: las `plain` son
  monocromas en el color de la marca y sobre el fondo oscuro del sitio no se leen.
- **Marcas negras u oscuras** (Symfony, MySQL, WordPress, Ollama, OpenAI, PWA): los
  fills con luminancia < 0.20 se reemplazaron por `#e2e8f0`, que es la versión
  para fondo oscuro que esas marcas publican en sus guías.

Del logo de PWA se usa el dibujo de Simple Icons en blanco, no el oficial de
dos tonos: sus dos colores (`#3d3d3d` y `#5a0fc8`) tienen luminancia 0.05-0.07 y
sobre el fondo del sitio la "W" violeta queda como un hueco oscuro.

Para agregar una tecnología: bajá el SVG `original`, revisá que se lea sobre
oscuro (si no, aclarale los fills como arriba), dejalo acá y elegí el logo desde
el panel (Tecnologías → editar).
