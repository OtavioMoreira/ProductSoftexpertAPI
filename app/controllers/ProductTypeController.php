<?php

namespace app\controllers;

use app\config\Database;
use PDO;

class ProductTypeController
{
    public $id;
    public $name;
    public $description;
    public $percentage;
    public $created_at;

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
    public function getProductsType($where = null, $order = null, $limit = null)
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
            $objDatabase = new Database('products_type');
            $result = $objDatabase->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);

            // echo "<pre>"; print_r($result); echo "</pre>"; exit;

            $jsonResult = json_encode($result);
            echo $jsonResult;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

    /**
     * The function `addProducts` adds a new product to a database table with the provided item
     * details.
     * 
     * @param item The `addProducts` function you provided seems to be adding a new product to a
     * database table named 'products'. It takes an array `` as a parameter, which should contain
     * the following keys:
     * 
     * @return The `addProducts` function is returning a boolean value `true` if the insertion of the
     * product into the database is successful. If an exception occurs during the database insertion
     * process, it will catch the exception and echo out the error message.
     */
    public function addProductsType($item)
    {
        // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        $this->name = $item['name'];
        $this->description = $item['description'];
        $this->percentage = $item['percentage'];

        try {
            $objDatabase = new Database('products_type');
            $objDatabase->insert([
                'name' => $this->name,
                'description' => $this->description,
                'percentage' => (int) $this->percentage,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

    /**
     * The function `updateProducts` updates product information based on the provided data array.
     * 
     * @param item The `updateProducts` function you provided is responsible for updating product
     * information based on the data passed in the `` parameter. The `` parameter is expected
     * to be an associative array containing information about the product to be updated. Here is an
     * explanation of the keys that can be present in the
     * 
     * @return The `updateProducts` function returns a boolean value. It returns `true` if the update
     * operation was successful and at least one field was updated. It returns `false` if no fields
     * were sent for updating or if an exception occurred during the update process.
     */
    public function updateProductsType($item)
    {
        $this->id = $item['id'];
        $updateData = [];

        if (isset($item['name'])) {
            $this->name = $item['name'];
            $updateData['name'] = $this->name;
        }

        if (isset($item['description'])) {
            $this->description = $item['description'];
            $updateData['description'] = $this->description;
        }

        if (isset($item['percentage'])) {
            $this->percentage = (int) $item['percentage'];
            $updateData['percentage'] = $this->percentage;
        }
        
        try {
            if (!empty($updateData)) {
                $objDatabase = new Database('products_type');
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
     * The function `deleteProducts` deletes a product from the database based on the provided ID.
     * 
     * @param id The `deleteProducts` function you provided seems to be a method for deleting a product
     * from a database table based on the given ID. The function takes an ID as a parameter and
     * attempts to delete the corresponding product record from the 'products' table in the database.
     * 
     * @return The `deleteProducts` function is returning a boolean value. It returns `true` if the
     * deletion operation is successful, and `false` if an exception occurs during the process.
     */
    public function deleteProductsType($id)
    {
        // echo "<pre>"; print_r($id); echo "</pre>"; exit;
        try {
            $this->id = $id['id'];

            $objDatabase = new Database('products_type');
            $where = "id = '" . $this->id . "'";
            $objDatabase->delete($where);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
            return false;
        }
    }
}
