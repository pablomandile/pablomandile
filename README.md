# Pablo Mandile — Portfolio personal

> Sitio portfolio SPA, bilingüe y con panel de administración, construido con Laravel + Vue.
>
> 🌐 **En vivo: [pablomandile.com.ar](https://pablomandile.com.ar)**

![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Inertia](https://img.shields.io/badge/Inertia.js_2-9553E9?style=for-the-badge&logo=inertia&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue_3-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white)
![TypeScript](https://img.shields.io/badge/TypeScript-3178C6?style=for-the-badge&logo=typescript&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)

---

## ✨ El sitio

Una única página con diseño **glassmorphism sobre fondo oscuro**: gradientes violeta→cyan, tarjetas de vidrio esmerilado y animaciones sutiles al hacer scroll.

- **Hero** con presentación y enlaces a GitHub / LinkedIn
- **Sobre mí** con biografía y experiencia laboral
- **Tecnologías** agrupadas por categoría (back-end, front-end, bases de datos, herramientas)
- **Proyectos** en tarjetas con imagen de vista previa, badges de tecnologías y enlaces a demo/código
- **Contacto** directo por email
- **Bilingüe 🇦🇷 ES / 🇬🇧 EN** con toggle instantáneo (vue-i18n, persistido en el navegador)
- **Responsive** mobile-first y con respeto por `prefers-reduced-motion`

## 🔐 Panel de administración

CRUD completo de proyectos protegido con login (registro público deshabilitado):

- Alta, edición y baja de proyectos con **subida de imagen de preview**
- Descripciones en ambos idiomas
- Selección de tecnologías, orden manual, y flags de *destacado* y *publicado* (borradores)

## 🛠️ Stack técnico

| Capa | Tecnología |
|---|---|
| Back-end | Laravel 12 (PHP 8.3) |
| Puente SPA | Inertia.js 2 |
| Front-end | Vue 3 + TypeScript (`<script setup>`) |
| Estilos | Tailwind CSS + componentes del starter kit oficial (shadcn-vue / radix-vue) |
| Internacionalización | vue-i18n (UI) + campos JSON `{es, en}` en base de datos (contenido) |
| Base de datos | MySQL 8 — `projects`, `technologies` y pivot `project_technology` |
| Bundler | Vite |
| Tests | PHPUnit — suite de features que cubre la home, el auth y el CRUD con subida de imagen |

Los datos del perfil (bio, experiencia, redes) viven en [`config/profile.php`](config/profile.php); los proyectos y tecnologías en la base, sembrados inicialmente por [`database/seeders`](database/seeders).

## 🚀 Instalación local

Requisitos: PHP ≥ 8.2, Composer, Node 22+, MySQL 8.

```bash
git clone https://github.com/pablomandile/pablomandile.git
cd pablomandile

composer install
npm install

cp .env.example .env
php artisan key:generate
# Configurar en .env la conexión MySQL (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
# y opcionalmente ADMIN_EMAIL / ADMIN_PASSWORD para el usuario del panel

php artisan migrate --seed
php artisan storage:link

npm run dev        # Vite con hot-reload
php artisan serve  # o el virtual host de Laragon/Valet
```

El sitio queda con datos de ejemplo desde el primer `--seed`. El panel se accede en `/login` con las credenciales definidas en `.env`.

### Tests

```bash
php artisan test
```

## 📄 Licencia

Código abierto bajo licencia [MIT](https://opensource.org/licenses/MIT).

---

**Pablo Mandile** — Programador Full-Stack PHP · Laravel · Vue
[pablomandile.com.ar](https://pablomandile.com.ar) · [GitHub](https://github.com/pablomandile) · [LinkedIn](https://www.linkedin.com/in/pablo-mandile/)
