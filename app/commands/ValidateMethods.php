<?php

namespace app\commands;

use app\controllers\AuthController;

class ValidateMethods
{
    /**
     * The function `validateGet` checks if the request method is not 'GET' and returns a 405 error
     * response if it is not.
     * 
     * @param requestMethod The `validateGet` function is checking if the `` parameter is
     * not equal to 'GET'. If it is not 'GET', it sets the HTTP response code to 405 (Method Not
     * Allowed), echoes a JSON-encoded message indicating that the method is not allowed, and then
     * exits the
     */
    public function validateGet($requestMethod)
    {
        if ($requestMethod !== 'GET') {
            http_response_code(405);
            echo json_encode(array('message' => 'Method not allowed'));
            exit();
        }
    }

    /**
     * The `validatePost` function in PHP validates the request method and returns an error message if
     * it is not a POST request.
     * 
     * @param requestMethod The `requestMethod` parameter in the `validatePost` function is used to
     * validate if the HTTP request method is a POST method. If the request method is not POST, it
     * returns a 405 HTTP response code with a message indicating that the method is not allowed.
     */
    public function validatePost($requestMethod)
    {
        if ($requestMethod !== 'POST') {
            http_response_code(405);
            echo json_encode(array('message' => 'Method not allowed'));
            exit();
        }
    }

    /**
     * The function `authorizeRoute` checks and validates a Bearer token from the HTTP Authorization
     * header in PHP.
     * 
     * @param authorizationHeader The `authorizeRoute` function you provided is responsible for
     * authorizing a route based on the authorization header passed to it. The `authorizationHeader`
     * parameter is expected to contain the value of the Authorization header sent in the HTTP request.
     */
    public function authorizeRoute($authorizationHeader)
    {
        $token = str_replace('Bearer ', '', $authorizationHeader);

        // echo "<pre>"; print_r($authorizationHeader); echo "</pre>"; exit;


        if (!$token) {
            http_response_code(401);
            echo json_encode(array('message' => 'Authentication token not provided'));
            exit();
        }

        $authController = new AuthController();
        if (!$authController->validateToken($token)) {
            http_response_code(401);
            echo json_encode(array('message' => 'Invalid or expired authentication token', 'status' => 401));
            exit();
        }
    }

    /**
     * The function `logoutRoute` checks for a valid Bearer token in the authorization header and logs
     * out the user using the token.
     * 
     * @param authorizationHeader The `authorizationHeader` parameter in the `logoutRoute` function is
     * used to pass the authorization header value from the client request. This value is typically
     * used to authenticate and authorize the user making the request. In this case, the function is
     * checking if the authorization header contains a valid Bearer token for
     */
    public function logoutRoute($authorizationHeader)
    {
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if (!$authorizationHeader || !preg_match('/^Bearer\s+(.*?)$/', $authorizationHeader, $matches)) {
            http_response_code(401);
            echo json_encode(array('message' => 'Authentication token not provided in proper format'));
            exit();
        }

        $token = $matches[1];

        $authController = new AuthController();
        $authController->logout($token);
    }
}
