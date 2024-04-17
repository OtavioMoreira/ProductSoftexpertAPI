<?php
require __DIR__ . '/../../vendor/autoload.php';
use app\controllers\ProductController;

/* This PHP code snippet is a basic routing mechanism for handling API requests. Let's break down what
each part of the code is doing: */
$route = strtok($_SERVER['REQUEST_URI'], '?');
$routeParts = explode('/', $route);

switch ($route) {
    case '/api/getProducts':
        $parameters = isset($_GET) ? $_GET : [];
        $controller = new ProductController();
        $controller->getProducts($parameters);
        break;
    // case '/api/getProduct':
    //     $controller = new ProductController();
    //     $controller->getProductById();
    //     break;
    default:
        http_response_code(404);
        echo json_encode(array('message' => 'Rota não encontrada'));
}
