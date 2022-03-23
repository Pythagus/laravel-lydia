<?php

use Pythagus\Lydia\Contracts\PaymentErrorCode;

return [

    'payment' => [
        PaymentErrorCode::INVALID_RECIPIENT => "Adresse email invalide",
        PaymentErrorCode::FLOODED_RECIPIENT => "L'adresse email renseignée est temporairement bloquée par Lydia",
        PaymentErrorCode::BLOCKED_RECIPIENT => "L'adresse email renseignée est temporairement bloquée par Lydia",
    ],

] ;