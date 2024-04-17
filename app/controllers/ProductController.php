<?php

namespace app\controllers;

use app\config\Database;
use PDO;

class ProductController
{
    /**
     * The function `getProducts` retrieves products from a database based on specified conditions and
     * returns the results in JSON format.
     * 
     * @param where The `where` parameter in the `getProducts` function is used to filter the products
     * based on specific conditions. In the provided code, the `where` parameter is initially set to
     * `null` as a default value. However, it is then overwritten with an empty string ` = '';`
     * @param order The `order` parameter in the `getProducts` function is used to specify the order in
     * which the products should be retrieved from the database. It determines the sorting of the
     * results based on a specific column or columns.
     * @param limit The `limit` parameter in the `getProducts` function is used to specify the maximum
     * number of products that should be returned in the result set. It limits the number of rows
     * returned by the database query. If the `limit` parameter is not provided, all matching products
     * will be returned.
     */
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
