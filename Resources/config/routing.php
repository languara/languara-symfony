<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('languara_symfony_homepage', new Route('/hello/{name}', array(
    '_controller' => 'LanguaraSymfonyBundle:Default:index',
)));

return $collection;
