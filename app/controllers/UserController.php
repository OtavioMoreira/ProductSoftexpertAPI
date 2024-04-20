<?php

namespace app\controllers;

use app\config\Database;
use PDO;
use app\commands\ValidateMethods;
use Exception;

class UserController
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $created_at;


    public function __construct()
    {
    }

    /**
     * The getUsers function retrieves user data based on specified conditions and returns the results
     * in JSON format.
     * 
     * @param where The `where` parameter in the `getUsers` function is used to specify conditions for
     * filtering the users to be retrieved from the database. In the provided code snippet, the
     * `` parameter is initially set to `null`, but it is later overwritten with an SQL WHERE
     * clause constructed from the parameters
     * @param order The `order` parameter in the `getUsers` function specifies the ordering of the
     * results retrieved from the database. In this case, the default order is set to 'username ASC',
     * which means the results will be sorted in ascending order based on the 'username' column.
     * @param limit The `limit` parameter in the `getUsers` function allows you to specify the maximum
     * number of records to retrieve from the database. If you provide a value for the `limit`
     * parameter, the function will only return that number of records. If you do not provide a value
     * for the `limit
     */
    public function getUsers($where = null, $order = ' username ASC', $limit = null)
    {
        $parameters = $_GET;

        $where = '';
        foreach ($parameters as $key => $value) {
            if (!empty($where)) {
                $where .= ' AND ';
            }

            $where .= "$key = '$value'";
        }

        try {
            $objDatabase = new Database('users');
            $result = $objDatabase->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);

            // echo "<pre>"; print_r($result); echo "</pre>"; exit;

            $jsonResult = json_encode($result);
            echo $jsonResult;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

    /**
     * The function checks if a user with a specific email exists in the database.
     * 
     * @param email The `userExistsByEmail` function you provided seems to be checking if a user with a
     * specific email exists in the database. However, there are a couple of issues in the code snippet
     * you shared.
     * 
     * @return Currently, the `userExistsByEmail` function always returns `false` regardless of whether
     * a user with the specified email exists in the database or not. This is because the function does
     * not check the result of the database query and simply returns `false` after executing the query.
     */
    public function userExistsByEmail($email)
    {
        try {
            $where = "email = '" . $email . "'";

            $objDatabase = new Database('users');
            $objDatabase->select($where)->fetchAll(PDO::FETCH_CLASS, self::class);

            return false;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

    /**
     * The function `addUsers` adds a new user to the database after checking for existing users with
     * the same email.
     * 
     * @param item It looks like you were about to provide the parameters for the `addUsers` function.
     * Could you please provide the values for the `item` parameter so that I can assist you further
     * with this function?
     * 
     * @return The `addUsers` function returns a boolean value. It returns `true` if the user was
     * successfully added to the database, and `false` if there was an error during the process.
     */
    public function addUsers($item)
    {
        $this->username = $item['username'];
        $this->email = $item['email'];
        $this->password = md5($item['password']);

        try {
            try {
                $this->userExistsByEmail($this->email);
            } catch (\Exception $e) {
                echo "Erro: ", $e->getMessage(), "\n";
                return false;
            }

            $objDatabase = new Database('users');
            $objDatabase->insert([
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ", $e->getMessage(), "\n";
            return false;
        }
    }

    /**
     * The function `updateUsers` updates user information in a database based on the provided data.
     * 
     * @param item The `updateUsers` function you provided seems to be updating user information based
     * on the `` array passed to it. The `` array should contain information about the user
     * that needs to be updated, such as their `id`, `username`, `email`, and `password`.
     * 
     * @return The `updateUsers` function returns a boolean value. It returns `true` if the update
     * operation is successful and `false` if there are no fields to update or if an error occurs
     * during the process.
     */
    public function updateUsers($item)
    {
        $this->id = $item['id'];
        $updateData = [];

        if (isset($item['username'])) {
            $this->username = $item['username'];
            $updateData['username'] = $this->username;
        }

        if (isset($item['email'])) {
            $this->email = $item['email'];
            $updateData['email'] = $this->email;
        }

        if (isset($item['password'])) {
            $this->password = md5($item['password']);
            $updateData['password'] = $this->password;
        }

        try {
            try {
                $this->userExistsByEmail($this->email);
            } catch (\Exception $e) {
                echo "Erro: ", $e->getMessage(), "\n";
                return false;
            }

            if (!empty($updateData)) {
                $objDatabase = new Database('users');
                $objDatabase->update('id = ' . $this->id, $updateData);

                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
            return false;
        }
    }

    /**
     * The function `deleteUsers` deletes a user from the database based on the provided ID.
     * 
     * @param id The `deleteUsers` function is designed to delete a user from the database based on the
     * provided `id`. The function first extracts the `id` from the input array, then creates a `where`
     * condition to specify which user to delete based on that `id`. Finally, it calls the `
     * 
     * @return The `deleteUsers` function is returning a boolean value. If the deletion operation is
     * successful, it will return `true`. If an exception occurs during the deletion process, it will
     * catch the exception, display an error message, and return `false`.
     */
    public function deleteUsers($id)
    {
        // echo "<pre>"; print_r($id); echo "</pre>"; exit;
        try {
            $this->id = $id['id'];

            $objDatabase = new Database('users');
            $where = "id = '" . $this->id . "'";
            $objDatabase->delete($where);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
            return false;
        }
    }
}
