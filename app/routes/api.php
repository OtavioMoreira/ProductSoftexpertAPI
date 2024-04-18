<?php
require __DIR__ . '/../../vendor/autoload.php';

use app\controllers\ProductController;
use app\controllers\ProductTypeController;
use app\controllers\AuthController;
use app\commands\ValidateMethods;

/* This PHP code snippet is a basic routing mechanism for handling API requests. Let's break down what
each part of the code is doing: */

$route = strtok($_SERVER['REQUEST_URI'], '?');
$routeParts = explode('/', $route);

/* This PHP code snippet is a basic routing mechanism for handling API requests. It uses a switch
statement based on the requested route (``) to determine which action to take for different
API endpoints. Here's a breakdown of what each case in the switch statement is doing: */
switch ($route) {
    case '/api/authenticate':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);

        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(array('message' => 'E-mail e senha são necessários'));
            exit();
        }

        $login = new AuthController();
        $login->login($email, $password);

        break;
    case '/api/logout':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->logoutRoute($_SERVER['HTTP_AUTHORIZATION']);

        $login = new AuthController();
        $login->logout($_SERVER['HTTP_AUTHORIZATION']);
        break;
    case '/api/validateToken':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $login = new AuthController();
        $login->validateToken($_SERVER['HTTP_AUTHORIZATION']);
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
    case '/api/addProducts':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_POST) ? $_POST : [];
        $controller = new ProductController();
        $controller->addProducts($parameters);

        break;
    case '/api/deleteProducts':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_POST) ? $_POST : [];
        $controller = new ProductController();
        $controller->deleteProducts($parameters);

        break;
    case '/api/updateProducts':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_POST) ? $_POST : [];
        $controller = new ProductController();
        $controller->updateProducts($parameters);

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

        $parameters = isset($_POST) ? $_POST : [];
        $controller = new ProductTypeController();
        $controller->addProductsType($parameters);

        break;
    case '/api/deleteProductsType':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_POST) ? $_POST : [];
        $controller = new ProductTypeController();
        $controller->deleteProductsType($parameters);

        break;
    case '/api/updateProductsType':
        $valiteRequests = new ValidateMethods();
        $valiteRequests->validatePost($_SERVER['REQUEST_METHOD']);
        $valiteRequests->authorizeRoute($_SERVER['HTTP_AUTHORIZATION']);

        $parameters = isset($_POST) ? $_POST : [];
        $controller = new ProductTypeController();
        $controller->updateProductsType($parameters);

        break;
    default:
        http_response_code(404);
        echo json_encode(array('message' => 'Route not found'));
}
