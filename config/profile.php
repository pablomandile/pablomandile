<?php

/*
|--------------------------------------------------------------------------
| Perfil público del sitio
|--------------------------------------------------------------------------
| Datos personales que se muestran en la home. Los campos con subclaves
| es/en se resuelven en el frontend según el idioma activo.
| No incluir teléfono ni dirección: este archivo alimenta un sitio público.
*/

return [

    'name' => 'Pablo Mandile',

    'headline' => [
        'es' => 'Programador Full-Stack PHP · Laravel · Vue',
        'en' => 'Full-Stack PHP Developer · Laravel · Vue',
    ],

    'tagline' => [
        'es' => 'Construyo aplicaciones web con Laravel y Vue.js, respaldado por más de 20 años de experiencia en tecnología.',
        'en' => 'I build web applications with Laravel and Vue.js, backed by 20+ years of experience in technology.',
    ],

    'about' => [
        'es' => [
            'Soy programador PHP full-stack radicado en Buenos Aires. Desde 2022 formo parte del equipo de desarrollo del Ministerio de Educación (DGPTE – SSTES), donde trabajo en el Proyecto Comedores: una aplicación construida con Laravel y Vue.js, con back-end y front-end separados conectados mediante API.',
            'Antes de dedicarme al desarrollo fui Administrador de Sistemas en Capital Markets Argentina durante más de 13 años, a cargo de infraestructura, redes y sistemas de comercio electrónico bursátil. Esa base en sistemas me da una mirada integral a la hora de construir y desplegar aplicaciones.',
            'Me formé como desarrollador full-stack en Educación IT y Codo a Codo (PHP, React, Python), con estudios de Ingeniería en Sistemas en la UAI y de desarrollo mobile en la UTN. Soy una persona proactiva, con capacidad de análisis y en capacitación constante.',
        ],
        'en' => [
            'I am a full-stack PHP developer based in Buenos Aires. Since 2022 I have been part of the development team at the Ministry of Education (DGPTE – SSTES), working on the "Comedores" project: an application built with Laravel and Vue.js, with separate back-end and front-end connected through an API.',
            'Before moving into development I worked as a Systems Administrator at Capital Markets Argentina for over 13 years, in charge of infrastructure, networking and stock-trading platforms. That systems background gives me an end-to-end perspective when building and deploying applications.',
            'I trained as a full-stack developer at Educación IT and Codo a Codo (PHP, React, Python), with Systems Engineering studies at UAI and mobile development at UTN. I am proactive, analytical and constantly learning.',
        ],
    ],

    'experience' => [
        [
            'role' => [
                'es' => 'Programador Full-Stack Laravel',
                'en' => 'Full-Stack Laravel Developer',
            ],
            'company' => 'Ministerio de Educación — DGPTE · SSTES',
            'period' => [
                'es' => 'Feb 2022 — Presente',
                'en' => 'Feb 2022 — Present',
            ],
        ],
        [
            'role' => [
                'es' => 'Administrador de Sistemas',
                'en' => 'Systems Administrator',
            ],
            'company' => 'Capital Markets Argentina Soc. Bolsa S.A.',
            'period' => [
                'es' => '2000 — 2013',
                'en' => '2000 — 2013',
            ],
        ],
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
