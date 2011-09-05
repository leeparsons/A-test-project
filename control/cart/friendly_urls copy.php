<?php

$uri = $_SERVER['REQUEST_URI'];



switch ($uri) {
    default:
    break;
    case '/cart/': case 'cart': case '/cart':
        request 'cart/cart.php';
    die;
    break;
}