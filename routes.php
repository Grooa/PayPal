<?php

$routes['PayPal/{paymentId}/{securityCode}'] = array(
    'name' => 'PayPal_pay',
    'plugin' => 'PayPal',
    'controller' => 'SiteController',
    'action' => 'pay'
);


$routes['PayPalIPN'] = array(
    'name' => 'PayPal_ipn',
    'plugin' => 'PayPal',
    'controller' => 'PublicController',
    'action' => 'ipn'
);


$routes['PayPalUserBack'] = array(
    'name' => 'PayPal_userBack',
    'plugin' => 'PayPal',
    'controller' => 'PublicController',
    'action' => 'userBack'
);

$routes['PayPalStatus/{paymentId}/{securityCode}'] = array(
    'name' => 'PayPal_status',
    'plugin' => 'PayPal',
    'controller' => 'SiteController',
    'action' => 'status'
);
