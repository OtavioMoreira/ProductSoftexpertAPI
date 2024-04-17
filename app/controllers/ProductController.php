<?php

namespace app\controllers;

use app\config\Database;
use PDO;

class ProductController
{
    public function getProducts($where = null, $order = null, $limit = null)
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
            $objDatabase = new Database('products');
            $result = $objDatabase->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);

            $jsonResult = json_encode($result);
            echo $jsonResult;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }
}
