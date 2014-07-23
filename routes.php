<?php

$routes['PayPal{/orderId}'] = array(
    'name' => 'PayPal',
    'plugin' => 'PayPal',
    'controller' => 'SiteController',
    'action' => 'subscribe'
);


$routes['PayPalIPN'] = array(
    'name' => 'PayPal_ipn',
    'plugin' => 'PayPal',
    'controller' => 'PublicController',
    'action' => 'ipn'
);
