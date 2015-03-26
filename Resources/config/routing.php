<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('languara_pull', new Route('/languara/pull', array(
    '_controller' => 'LanguaraSymfonyBundle:Languara:pull',
)));
$collection->add('languara_push', new Route('/languara/push', array(
    '_controller' => 'LanguaraSymfonyBundle:Languara:push',
)));

return $collection;
