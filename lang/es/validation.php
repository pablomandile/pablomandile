<?php

return [
    'accepted' => 'El campo :attribute debe ser aceptado.',
    'alpha_dash' => 'El campo :attribute solo puede contener letras, números, guiones y guiones bajos.',
    'array' => 'El campo :attribute debe ser una lista.',
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'current_password' => 'La contraseña es incorrecta.',
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    'exists' => 'El valor seleccionado para :attribute no es válido.',
    'file' => 'El campo :attribute debe ser un archivo.',
    'image' => 'El campo :attribute debe ser una imagen.',
    'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'lowercase' => 'El campo :attribute debe estar en minúsculas.',

    'max' => [
        'array' => 'El campo :attribute no puede tener más de :max elementos.',
        'file' => 'El archivo :attribute no puede pesar más de :max kilobytes.',
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
    ],

    'min' => [
        'array' => 'El campo :attribute debe tener al menos :min elementos.',
        'file' => 'El archivo :attribute debe pesar al menos :min kilobytes.',
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],

    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'unique' => 'El valor de :attribute ya está en uso.',
    'url' => 'El campo :attribute debe ser una URL válida.',

    'attributes' => [
        'current_password' => 'contraseña actual',
        'cv' => 'CV',
        'demo_url' => 'URL de demo',
        'description.en' => 'descripción en inglés',
        'description.es' => 'descripción en español',
        'email' => 'correo electrónico',
        'name' => 'nombre',
        'password' => 'contraseña',
        'new_images' => 'imágenes',
        'new_images.*' => 'imagen',
        'repo_url' => 'URL de repositorio',
        'slug' => 'slug',
        'sort_order' => 'orden',
        'technologies' => 'tecnologías',
        'title' => 'título',
    ],
];
