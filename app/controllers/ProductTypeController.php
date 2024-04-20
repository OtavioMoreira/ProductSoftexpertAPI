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
     * The function `getProductsType` retrieves product types based on specified conditions and returns
     * the results in JSON format.
     * 
     * @param where The `where` parameter in the `getProductsType` function is used to filter the
     * results based on specific conditions. In the provided code snippet, the `` variable is
     * initially set to `null` as a default value. However, it is later overwritten with an empty
     * string `''`
     * @param order The `order` parameter in the `getProductsType` function is used to specify the
     * order in which the results should be returned. It can be used to sort the results based on a
     * specific column in ascending or descending order.
     * @param limit The `limit` parameter in the `getProductsType` function is used to specify the
     * maximum number of records to be returned from the database query. It limits the result set to a
     * certain number of rows.
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
     * The function existsByName checks if a product with a specific name exists in the database.
     * 
     * @param name The `existsByName` function you provided seems to be checking if a product with a
     * specific name exists in the database. The function constructs a SQL query to select records
     * where the name matches the provided ``, but it currently always returns `false` regardless
     * of whether a matching record is found or not
     * 
     * @return The `existsByName` function is always returning `false`.
     */
    public function existsByName($name)
    {
        
        try {
            $where = "name = '" . $name . "'";
            
            $objDatabase = new Database('products_type');
            $objDatabase->select($where)->fetchAll(PDO::FETCH_CLASS, self::class);

            // echo "<pre>"; print_r($result); echo "</pre>"; exit;

            return false;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

    /**
     * The function `addProductsType` adds a new product type to the database with the provided name,
     * description, and percentage.
     * 
     * @param item 'name' => 'Electronics',
     * 
     * @return The `addProductsType` function is returning a boolean value `true` if the insertion of
     * the product type into the database is successful. If an exception occurs during the database
     * insertion process, an error message will be echoed out but the function itself does not return
     * anything in that case.
     */
    public function addProductsType($item)
    {
        $this->name = $item['name'];
        $this->description = $item['description'];
        $this->percentage = $item['percentage'];
        
        // echo "<pre>"; print_r($this->name); echo "</pre>"; exit;

        try {
            try {
                $this->existsByName($this->name);
            } catch (\Exception $e) {
                echo "Erro: ", $e->getMessage(), "\n";
                return false;
            }

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
     * The function `updateProductsType` updates product type information based on the provided data.
     * 
     * @param item The `updateProductsType` function you provided seems to be updating product type
     * information based on the `` array passed as a parameter. The function checks for specific
     * keys in the `` array (such as 'name', 'description', and 'percentage'), updates the
     * corresponding properties of the object
     * 
     * @return The `updateProductsType` function returns a boolean value. It returns `true` if the
     * update operation was successful and `false` if there were no fields to update or if an exception
     * occurred during the update process.
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
     * This PHP function deletes a product type from the database based on the provided ID.
     * 
     * @param id The `deleteProductsType` function is designed to delete a record from the
     * `products_type` table based on the provided `id`. The function takes an array as a parameter,
     * and it expects the array to have an 'id' key which will be used to identify the record to be
     * deleted.
     * 
     * @return a boolean value. If the deletion operation is successful, it will return true. If an
     * exception occurs during the deletion process, it will catch the exception, display an error
     * message, and return false.
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
