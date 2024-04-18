<?php

namespace app\controllers;

use app\config\Database;
use PDO;

class AuthController
{
    /* These lines are defining properties of the `AuthController` class in PHP. Each property is
    declared as public, meaning they can be accessed and modified from outside the class. Here's a
    brief explanation of each property: */
    public $id;
    public $user_id;
    public $token;
    public $expiration;

    public function login($email, $password)
    {
        $authController = new AuthController();
        $user_id = $authController->validateCredentials($email, $password);

        // echo "<pre>"; print_r($user_id); echo "</pre>"; exit;

        if ($user_id) {
            $authController->generateToken($user_id);
        } else {
            http_response_code(401);
            echo json_encode(array('message' => 'Credenciais inválidas'));
            return false;
        }
    }

    /**
     * This PHP function validates user credentials by checking the email and password in the database
     * and returns the user ID if the credentials are valid.
     * 
     * @param email The `validateCredentials` function you provided seems to be checking if a user with
     * a specific email and password exists in the database. However, it's important to note that
     * storing passwords in plain text is not secure. It's recommended to hash passwords before storing
     * them in the database and compare hashed values during
     * @param password The `validateCredentials` function you provided seems to be checking if a user
     * with a specific email and password exists in the database. However, the password parameter is
     * missing in your description. Could you please provide the password parameter so that I can
     * assist you further with your code?
     * 
     * @return The `validateCredentials` function is returning the user ID if the credentials provided
     * (email and password) match a record in the database. If a matching record is found, it sets the
     * `user_id` property of the current object to the user ID and returns the user ID. If no matching
     * record is found, it returns `false`.
     */
    public function validateCredentials($email, $password)
    {
        $where = "email = '" . $email . "' AND password = '" . md5($password) . "'";
        $objDatabase = new Database('users');
        $result = $objDatabase->select($where)->fetchAll(PDO::FETCH_CLASS, self::class);

        // echo "<pre>"; print_r($result[0]->id); echo "</pre>"; exit;

        if (count($result) > 0) {
            $this->user_id = $result[0]->id;
            return $this->user_id;
        }

        return false;
    }

    /**
     * The function `generateToken` generates a random token and stores it in the database with an
     * expiration time for a specified user.
     * 
     * @param user_id The `generateToken` function you provided generates a token for a given user ID
     * and stores it in the database along with an expiration date. It uses `random_bytes` to generate
     * a random token and sets an expiration time of 4 hours from the current time.
     * 
     * @return The `generateToken` function is returning the generated token if the insertion into the
     * database is successful. If an exception occurs during the database insertion, it will catch the
     * exception and echo the error message. In both cases, it will return the token value.
     */
    public function generateToken($user_id)
    {
        $this->removeExistingToken($user_id);

        $this->token = bin2hex(random_bytes(32));
        $this->expiration = date('Y-m-d H:i:s', strtotime('+4 hour'));

        // echo "<pre>"; print_r($this->token . "<br>" . $this->expiration . "<br>" . $user_id); echo "</pre>"; exit;
        try {
            $objDatabase = new Database('tokens');
            $objDatabase->insert([
                'user_id' => $user_id,
                'token' => $this->token,
                'expiration' => $this->expiration
            ]);

            http_response_code(200);
            echo json_encode(array('message' => 'Bearer ' . $this->token));
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

    /**
     * The function `removeExistingToken` deletes existing tokens associated with a specific user ID in
     * a database table.
     * 
     * @param user_id The `removeExistingToken` function takes a `user_id` as a parameter. This
     * function is responsible for removing any existing token records associated with the provided
     * `user_id` from the 'tokens' table in the database. If successful, it returns `true`; otherwise,
     * it catches any exceptions that
     * 
     * @return a boolean value. It returns true if the token for the specified user ID was successfully
     * removed, and it returns false if an exception occurred during the deletion process.
     */
    private function removeExistingToken($user_id)
    {
        try {
            $objDatabase = new Database('tokens');
            $where = "user_id = " . $user_id;
            $objDatabase->delete($where);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
            return false;
        }
    }

    /**
     * The function `validateToken` queries a database table for a specific token and returns true if
     * the token exists, otherwise false.
     * 
     * @param token It looks like there is a small issue in your code. The `` variable should be
     * properly enclosed in quotes when building the SQL query in the `` clause. This is
     * important to prevent SQL injection vulnerabilities and ensure that the query works correctly.
     * 
     * @return either true or false based on the result of the database query. If the query returns any
     * result, it will return true, otherwise it will return false.
     */
    public function validateToken($token)
    {
        try {
            $objDatabase = new Database('tokens');
            $where = "token = '" . $token . "'";
            $result = $objDatabase->select($where)->fetchAll(PDO::FETCH_CLASS, self::class);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

    /**
     * The function `logout` deletes a token from the 'tokens' table in a database based on the
     * provided token value.
     * 
     * @param token The `logout` function you provided seems to be a part of a system that handles user
     * authentication tokens. When a user logs out, their token is deleted from the database to
     * invalidate it.
     * 
     * @return The `logout` function is returning a boolean value. If the deletion of the token from
     * the database is successful, it will return `true`. If an exception occurs during the deletion
     * process, it will catch the exception, display an error message, and return `false`.
     */
    public function logout($token)
    {
        try {
            $objDatabase = new Database('tokens');
            $where = "token = '" . $token . "'";
            $objDatabase->delete($where);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
            return false;
        }
    }

    /**
     * The function `deleteExpiredTokens` deletes expired tokens from a database table.
     * 
     * @return a boolean value `true` if the deletion of expired tokens is successful.
     */
    public function deleteExpiredTokens() {
        try {
            $objDatabase = new Database('tokens');
            $where = "expiration <= NOW()";
            $objDatabase->delete($where);

            return true;
        } catch (\Exception $e) {
            echo "Erro ao excluir tokens expirados: " . $e->getMessage();
        }
    }
}
