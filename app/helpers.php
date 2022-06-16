<?php

function getMethodName($payment_method){
    $payment = [
        'momo' => 'Momo',
        'cash' => 'Tiền mặt',
        'transfer' => 'Chuyển khoản',
    ];

    return $payment[$payment_method] ?? '';
}