<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', base64_decode('TWlkLXNlcnZlci1sTWh6VkNnT0RPYmNVUjZrdF9jNmJmNkY=')),
    'client_key' => env('MIDTRANS_CLIENT_KEY', base64_decode('TWlkLWNsaWVudC1ld0VlZTdtU2VfYS1hSmJ3')),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', true),
    'is_sanitized' => true,
    'is_3ds' => true,
];
