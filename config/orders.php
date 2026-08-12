<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Self-service order cancellation window (minutes)
    |--------------------------------------------------------------------------
    |
    | Customers may cancel and receive an automatic refund within this period
    | after the order is placed, while the order is still being prepared.
    |
    */

    'self_cancel_minutes' => (int) env('ORDER_SELF_CANCEL_MINUTES', 60),
];
