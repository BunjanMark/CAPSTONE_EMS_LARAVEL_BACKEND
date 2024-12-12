<?php

return [
    'paths' => ['api/*'], // Ensure this matches your API routes
    'allowed_methods' => ['*'], // Allow all methods
    // 'allowed_origins' => ['http://localhost:3000'], // Allow your frontend origin #FIXME
    'allowed_origins' => ['*'], // Allow your frontend origin
    'allowed_headers' => ['*'], // Allow all headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];

