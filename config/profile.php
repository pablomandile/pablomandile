<?php

/*
|--------------------------------------------------------------------------
| Perfil público del sitio
|--------------------------------------------------------------------------
| Datos personales que se muestran en la home. Los campos con subclaves
| es/en se resuelven en el frontend según el idioma activo.
| No incluir teléfono ni dirección: este archivo alimenta un sitio público.
|
| El texto "Sobre mí" y la Experiencia se editan desde el panel admin y
| viven en la base de datos (tablas settings y experiences), no aquí.
*/

return [

    'name' => 'Pablo Mandile',

    // Ruta relativa dentro del disco 'public' (storage/app/public).
    'photo' => 'img/profile/pablo.jpeg',

    'headline' => [
        'es' => 'Programador Full-Stack PHP · Laravel · Vue',
        'en' => 'Full-Stack PHP Developer · Laravel · Vue',
    ],

    'tagline' => [
        'es' => 'Construyo aplicaciones web con Laravel y Vue.js, respaldado por más de 20 años de experiencia en tecnología.',
        'en' => 'I build web applications with Laravel and Vue.js, backed by 20+ years of experience in technology.',
    ],

    'email' => 'pablo.mandile@gmail.com',

    'location' => [
        'es' => 'Buenos Aires, Argentina',
        'en' => 'Buenos Aires, Argentina',
    ],

    'socials' => [
        'github' => 'https://github.com/pablomandile',
        'linkedin' => 'https://www.linkedin.com/in/pablo-mandile/',
    ],

];
