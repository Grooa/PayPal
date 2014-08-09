<?php

$routes['paypal/pay/{paymentId}/{securityCode}'] = array(
    'name' => 'PayPal_pay',
    'plugin' => 'PayPal',
    'controller' => 'SiteController',
    'action' => 'pay'
);


$routes['paypal/ipn'] = array(
    'name' => 'PayPal_ipn',
    'plugin' => 'PayPal',
    'controller' => 'PublicController',
    'action' => 'ipn'
);


$routes['paypal/userback'] = array(
    'name' => 'PayPal_userBack',
    'plugin' => 'PayPal',
    'controller' => 'PublicController',
    'action' => 'userBack'
);

$routes['paypal/status/{paymentId}/{securityCode}'] = array(
    'name' => 'PayPal_status',
    'plugin' => 'PayPal',
    'controller' => 'SiteController',
    'action' => 'status'
);
