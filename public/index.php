<?php
require_once '../app/bootstrap.php';
require_once '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../app/views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('URLROOT', 'http://localhost/autoblitz');


// Init Core Library
$init = new Core;


// Create a new Router instance
$router = new Router();

// Define your routes
$router->addRoute('/', '/pages/index');
$router->addRoute('/about', 'pages/about.twig');
$router->addRoute('/posts/add', 'posts/add.twig');
$router->addRoute('/posts/edit', 'posts/edit.twig');
$router->addRoute('/posts/index', 'posts/index.twig');
$router->addRoute('/posts/show', 'posts/show.twig');
$router->addRoute('/users/register', 'users/register.twig');
$router->addRoute('/users/login', 'users/login.twig');

// Get the current URL
$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Dispatch the route
$router->dispatch($currentUrl);




