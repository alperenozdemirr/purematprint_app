<?php

return [
    'username' => env('SHIPINK_USERNAME'),
    'password' => env('SHIPINK_PASSWORD'),
    'base_url' => env('SHIPINK_BASE_URL', 'https://api.dev.shipink.io'),
    'warehouse_id' => env('SHIPINK_WAREHOUSE_ID'),
    'carrier_account_id' => env('SHIPINK_CARRIER_ACCOUNT_ID'),
    'carrier_id' => env('SHIPINK_CARRIER_ID', 'aras'),
    'carrier_service_id' => env('SHIPINK_CARRIER_SERVICE_ID', 'aras_standart'),
    'card_id' => env('SHIPINK_CARD_ID'),
    'sales_channel' => [
        'id' => env('SHIPINK_SALES_CHANNEL_ID', 'api'),
        'name' => env('SHIPINK_SALES_CHANNEL_NAME', env('APP_NAME', 'PureMatPrint')),
    ],
    'shipment_cancel_minutes' => (int) env('SHIPINK_SHIPMENT_CANCEL_MINUTES', 60),
    'stale_sync_hours' => (int) env('SHIPINK_STALE_SYNC_HOURS', 6),
    'create_lock_seconds' => (int) env('SHIPINK_CREATE_LOCK_SECONDS', 120),

    'default_package' => [
        'weight' => (int) env('SHIPINK_DEFAULT_WEIGHT', 1),
        'weight_unit' => 'kg',
        'length' => (int) env('SHIPINK_DEFAULT_LENGTH', 20),
        'width' => (int) env('SHIPINK_DEFAULT_WIDTH', 15),
        'height' => (int) env('SHIPINK_DEFAULT_HEIGHT', 10),
        'dimension_unit' => 'cm',
    ],
];
