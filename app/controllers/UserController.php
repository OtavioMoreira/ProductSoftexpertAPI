<?php

namespace app\controllers;

use app\config\Database;
use PDO;

class UserController
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $created_at;

    public function getUsers($where = null, $order = null, $limit = null)
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

    public function addUsers($item)
    {
        $this->username = $item['username'];
        $this->email = $item['email'];
        $this->password = md5($item['password']);

        // echo "<pre>"; print_r($this->password); echo "</pre>"; exit;

        try {
            $objDatabase = new Database('users');
            $objDatabase->insert([
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

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
