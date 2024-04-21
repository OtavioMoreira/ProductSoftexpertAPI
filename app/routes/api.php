<?php

// Adiciona cabeçalhos CORS para permitir solicitações da origem do seu aplicativo React/Next.js
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Se a solicitação for do tipo OPTIONS, retorna apenas os cabeçalhos CORS e não executa mais nada
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}


require __DIR__ . '/../../vendor/autoload.php';

use app\controllers\ProductController;
use app\controllers\ProductTypeController;
use app\controllers\SaleController;
use app\controllers\AuthController;
use app\controllers\UserController;
use app\commands\ValidateMethods;

/* This PHP code snippet is a basic routing mechanism for handling API requests. Let's break down what
each part of the code is doing: */

$login = new AuthController();
$login->deleteExpiredTokens();
$route = strtok($_SERVER['REQUEST_URI'], '?');
$routeParts = explode('/', $route);

/* This PHP code snippet is a basic routing mechanism for handling API requests. It uses a switch
statement based on the requested route (``) to determine which action to take for different
API endpoints. Here's a breakdown of what each case in the switch statement is doing: */
switch ($route) {
    case '/api/authenticate':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $email = $requestData['email'] ?? null;
        $password = $requestData['password'] ?? null;

        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(array('message' => 'E-mail e senha são necessários'));
            exit();
        }

        $login->login($email, $password);

        break;
    case '/api/deleteExpiredTokens':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);

        $login->deleteExpiredTokens();

        break;
    case '/api/logout':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->logoutRoute($_SERVER['HTTP_AUTHORIZATION']);

        $login->logout($_SERVER['HTTP_AUTHORIZATION']);
        break;
    case '/api/validateToken':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $login->validateToken($_SERVER['HTTP_AUTHORIZATION']);
        break;
        // User Routes
    case '/api/getUsers':
        // This route can used for get userById by params
        // All users or userById
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validateGet($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_GET) ? $_GET : [];
        $controller = new UserController();
        $controller->getUsers($parameters);

        break;
    case '/api/addUsers':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $controller = new UserController();
        $controller->addUsers($requestData);

        break;
    case '/api/deleteUsers':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $controller = new UserController();
        $controller->deleteUsers($requestData);

        break;
    case '/api/updateUsers':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $controller = new UserController();
        $controller->updateUsers($requestData);

        break;
        // Products Routes
    case '/api/getProducts':
        // This route can used for get userById by params
        // All users or userById
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validateGet($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_GET) ? $_GET : [];
        $controller = new ProductController();
        $controller->getProducts($parameters);

        break;
    case '/api/getProductsEdit':
        // This route can used for get userById by params
        // All users or userById
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validateGet($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_GET) ? $_GET : [];
        $controller = new ProductController();
        $controller->getProductsEdit($parameters);

        break;
    case '/api/addProducts':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $controller = new ProductController();
        $controller->addProducts($requestData);

        break;
    case '/api/deleteProducts':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $controller = new ProductController();
        $controller->deleteProducts($requestData);

        break;
    case '/api/deleteProductsRel':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $controller = new ProductController();
        $controller->deleteProductsRel($requestData);

        break;
    case '/api/updateProducts':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        // echo "<pre>"; print_r($requestData); echo "</pre>"; exit;

        $controller = new ProductController();
        $controller->updateProducts($requestData);

        break;
    case '/api/updateProductsRel':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        // echo "<pre>"; print_r($requestData); echo "</pre>"; exit;

        $controller = new ProductController();
        $controller->updateProductsRel($requestData);

        break;
        // Products Type Routes
    case '/api/getProductsType':
        // This route can used for get userById by params
        // All users or userById
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validateGet($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_GET) ? $_GET : [];
        $controller = new ProductTypeController();
        $controller->getProductsType($parameters);

        break;
    case '/api/addProductsType':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        // echo "<pre>"; print_r($jsonData); echo "</pre>"; exit;

        $controller = new ProductTypeController();
        $controller->addProductsType($requestData);

        break;
    case '/api/deleteProductsType':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $controller = new ProductTypeController();
        $controller->deleteProductsType($requestData);

        break;
    case '/api/updateProductsType':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $controller = new ProductTypeController();
        $controller->updateProductsType($requestData);

        break;
        // Sales Routes
    case '/api/getSales':
        // This route can used for get userById by params
        // All users or userById
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validateGet($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_GET) ? $_GET : [];
        $controller = new SaleController();
        $controller->getSales($parameters);

        break;
    case '/api/getSalesEdit':
        // This route can used for get userById by params
        // All users or userById
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validateGet($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_GET) ? $_GET : [];
        $controller = new SaleController();
        $controller->getSalesEdit($parameters);

        break;
    case '/api/addSales':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        // echo "<pre>"; print_r($requestData); echo "</pre>"; exit;

        $controller = new SaleController();
        $controller->addSales($requestData);

        break;
    case '/api/deleteSales':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $controller = new SaleController();
        $controller->deleteSales($requestData);

        break;
    case '/api/updateSales':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);

        $controller = new SaleController();
        $controller->updateSales($requestData);

        break;
    default:
        http_response_code(404);
        echo json_encode(array('message' => 'Route not found'));
}
