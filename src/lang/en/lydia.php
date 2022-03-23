<?php

use Pythagus\Lydia\Contracts\PaymentErrorCode;

return [

    'payment' => [
        PaymentErrorCode::INVALID_RECIPIENT => "Invalid email address",
        PaymentErrorCode::FLOODED_RECIPIENT => "Given email address is temporarily blocked by Lydia service",
        PaymentErrorCode::BLOCKED_RECIPIENT => "Given email address is temporarily blocked by Lydia service",
    ],

];
