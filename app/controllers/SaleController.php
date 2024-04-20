<?php

namespace app\controllers;

use app\config\Database;
use PDO;

class SaleController
{
    public $id;
    public $user_id;
    public $product_id;
    public $qtd;
    public $totalValue;
    public $created_at;

    /**
     * The function `getSales` retrieves sales data based on specified conditions and returns the
     * results in JSON format.
     * 
     * @param where The `where` parameter in the `getSales` function is used to filter the sales data
     * based on specific conditions. In the provided code snippet, the `` parameter is initially
     * set to `null`, but it is later overwritten with a dynamic condition based on the values in the
     * `
     * @param order The `` parameter in the `getSales` function is used to specify the order in
     * which the results should be retrieved from the database. It typically includes the column name
     * and the direction of sorting (ASC for ascending or DESC for descending).
     * @param limit The `limit` parameter in the `getSales` function is used to specify the maximum
     * number of records to be retrieved from the database. It limits the number of results returned by
     * the query. If the `limit` parameter is not provided, all matching records will be returned.
     */
    public function getSales($where = null, $order = null, $limit = null)
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
            $objDatabase = new Database('sales');
            $joinClause = 'INNER JOIN users ON sales.user_id = users.id INNER JOIN products ON sales.product_id = products.id';
            
            $fields = 'sales.id AS sale_id, users.username AS user_name, products.name AS product_name, products.price AS product_price';
            $result = $objDatabase->select($where, $order, $limit, $fields, $joinClause)->fetchAll(PDO::FETCH_ASSOC);
    
            // Aqui você pode processar os resultados, se necessário
    
            $jsonResult = json_encode($result);
            echo $jsonResult;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

    /**
     * The function `addSales` inserts sales data into a database table based on the provided item
     * information.
     * 
     * @param item The `addSales` function you provided is used to add a new sales record to a database
     * table. It takes an `` parameter which is expected to be an associative array containing the
     * following keys:
     * 
     * @return The `addSales` function is returning a boolean value `true` if the sales data was
     * successfully inserted into the database. If an exception occurs during the database insertion
     * process, an error message will be echoed out.
     */
    public function addSales($item)
    {
        // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        $this->user_id = $item['user_id'];
        $this->product_id = $item['product_id'];
        $this->qtd = $item['qtd'];
        $this->totalValue = $item['totalValue'];

        try {
            $objDatabase = new Database('sales');
            $objDatabase->insert([
                'user_id' => $this->user_id,
                'product_id' => $this->product_id,
                'qtd' => (int) $this->qtd,
                'totalValue' => (float) $this->totalValue,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

    /**
     * The function `updateSales` updates sales data based on the provided item information.
     * 
     * @param item The `updateSales` function you provided is responsible for updating sales data based
     * on the `` array passed to it. The `` array contains information about the sale that
     * needs to be updated. Here is a breakdown of the parameters in the `` array:
     * 
     * @return The `updateSales` function returns a boolean value. It returns `true` if the update
     * operation was successful and `false` if there were no fields to update or if an exception
     * occurred during the update process.
     */
    public function updateSales($item)
    {
        $this->id = $item['id'];
        $updateData = [];

        if (isset($item['user_id'])) {
            $this->user_id = $item['user_id'];
            $updateData['user_id'] = $this->user_id;
        }

        if (isset($item['product_id'])) {
            $this->product_id = $item['product_id'];
            $updateData['product_id'] = $this->product_id;
        }

        if (isset($item['qtd'])) {
            $this->qtd = (int) $item['qtd'];
            $updateData['qtd'] = $this->qtd;
        }

        if (isset($item['totalValue'])) {
            $this->totalValue = (float) $item['totalValue'];
            $updateData['totalValue'] = $this->totalValue;
        }

        try {
            if (!empty($updateData)) {
                $objDatabase = new Database('sales');
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
     * This PHP function deletes a sales record from the database based on the provided ID.
     * 
     * @param id The `deleteSales` function is designed to delete a sales record from the database
     * based on the provided `id`. The `id` parameter is expected to be an associative array with a key
     * named `id`.
     * 
     * @return The `deleteSales` function is returning a boolean value. If the deletion operation is
     * successful, it will return `true`. If an exception occurs during the deletion process, it will
     * catch the exception, display an error message, and return `false`.
     */
    public function deleteSales($id)
    {
        // echo "<pre>"; print_r($id); echo "</pre>"; exit;
        try {
            $this->id = $id['id'];

            $objDatabase = new Database('sales');
            $where = "id = '" . $this->id . "'";
            $objDatabase->delete($where);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
            return false;
        }
    }
}
