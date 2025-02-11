<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Rutas donde se aplicará CORS
    'allowed_methods' => ['*'], // Permitir todos los métodos (GET, POST, etc.)
    'allowed_origins' => ['*'], // Puedes poner '*' o los dominios permitidos
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Permitir todos los headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Cambiar a `true` si usas autenticación con cookies
];
