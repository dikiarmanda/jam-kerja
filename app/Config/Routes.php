<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Dashboard::index');

$routes->get('/projects/create', 'Projects::create');
$routes->post('/projects', 'Projects::store');
$routes->get('/projects/(:num)', 'Projects::show/$1');
$routes->get('/projects/(:num)/edit', 'Projects::edit/$1');
$routes->post('/projects/(:num)/update', 'Projects::update/$1');
$routes->post('/projects/(:num)/delete', 'Projects::delete/$1');

$routes->get('/tags', 'Tags::index');
$routes->post('/tags', 'Tags::store');
$routes->post('/tags/(:num)/update', 'Tags::update/$1');
$routes->post('/tags/(:num)/delete', 'Tags::delete/$1');

$routes->post('/timer/start/(:num)', 'Timer::start/$1');
$routes->post('/timer/stop/(:num)', 'Timer::stop/$1');
$routes->get('/timer/status', 'Timer::status');

$routes->get('/sessions', 'Sessions::index');
$routes->get('/sessions/create', 'Sessions::create');
$routes->get('/projects/(:num)/sessions/create', 'Sessions::create/$1');
$routes->post('/sessions', 'Sessions::store');
$routes->get('/sessions/(:num)/edit', 'Sessions::edit/$1');
$routes->post('/sessions/(:num)/update', 'Sessions::update/$1');
$routes->post('/sessions/(:num)/delete', 'Sessions::delete/$1');
