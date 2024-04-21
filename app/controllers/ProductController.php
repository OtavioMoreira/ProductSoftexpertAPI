<?php

namespace app\controllers;

use app\config\Database;
use PDO;

class ProductController
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $qtd;
    public $product_id;
    public $product_type_id;
    public $old_product_type_id;
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
    public function getProducts($where = null, $order = null, $limit = null)
    {
        $parameters = $_GET;

        $where = '';
        foreach ($parameters as $key => $value) {
            if (!empty($where)) {
                $where .= ' AND ';
            }

            $where .= "products.$key = '$value'";
        }

        // echo "<pre>"; print_r($where); echo "</pre>"; exit;

        try {
            $objDatabase = new Database('products');
            $fields = 'products.id, products.name, products.description, products.price, products.qtd, products.created_at, product_product_type.product_id, product_product_type.product_type_id, products_type.percentage';
            $joinClause = 'LEFT JOIN product_product_type ON products.id = product_product_type.product_id';
            $joinClause .= ' LEFT JOIN products_type ON products.id = products_type.id';
            $result = $objDatabase->select($where, $order, $limit, $fields, $joinClause)->fetchAll(PDO::FETCH_CLASS, self::class);



            $jsonResult = json_encode($result);
            echo $jsonResult;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        }
    }

    public function getProductsEdit($where = null, $order = null, $limit = null)
    {
        $parameters = $_GET;

        $where = '';
        foreach ($parameters as $key => $value) {
            if (!empty($where)) {
                $where .= ' AND ';
            }

            $where .= "products.$key = '$value'";
        }

        try {
            $objDatabase = new Database('products');


            $fields = 'products.id, products.name, products.description, products.price, products.qtd, products.created_at, product_product_type.product_type_id, products_type.percentage';
            $joinClause = 'LEFT JOIN product_product_type ON products.id = product_product_type.product_id';
            $joinClause .= ' LEFT JOIN products_type ON product_product_type.product_type_id = products_type.id';


            $result = $objDatabase->select($where, $order, $limit, $fields, $joinClause)->fetchAll(PDO::FETCH_ASSOC);

            // Converta o resultado para JSON e imprima
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
    public function addProducts($item)
    {
        $this->name = $item['name'];
        $this->description = $item['description'];
        $this->price = $item['price'];
        $this->qtd = $item['qtd'];
        $this->product_id = $item['product_id'];
        $this->product_type_id = $item['product_type_id'];

        try {
            $this->existsByName($this->name);
        } catch (\Exception $e) {
            echo "Erro: ", $e->getMessage(), "\n";
            return false;
        }

        try {
            $objDatabase = new Database('products');

            $this->id = $objDatabase->insert([
                'name' => $this->name,
                'description' => $this->description,
                'price' => (float) $this->price,
                'qtd' => (int) $this->qtd,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
        } finally {
            $objDatabase = new Database('product_product_type');
            $objDatabase->insert([
                'product_id' => $this->id,
                'product_type_id' => $this->product_type_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return true;
        }
    }

    /**
     * The function `userExistsByName` checks if a user with a specific name exists in the database and
     * returns false if not.
     * 
     * @param name The `userExistsByName` function seems to be checking if a user with a specific name
     * exists in the database. The function constructs a SQL query to find a user by name and then
     * attempts to fetch all the results using PDO.
     * 
     * @return The function `userExistsByName` is currently returning `false` regardless of whether a
     * user with the given name exists in the database or not. This is because the function always
     * returns `false` after executing the database query and does not check the result of the query to
     * determine if a user with the given name exists.
     */
    public function existsByName($name)
    {
        try {
            $where = "name = '" . $name . "'";

            $objDatabase = new Database('products');
            $objDatabase->select($where)->fetchAll(PDO::FETCH_CLASS, self::class);

            return false;
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
    public function updateProducts($item)
    {
        $this->id = $item['id'];
        $updateData = [];
        $updateDataRel = [];

        if (isset($item['name'])) {
            $this->name = $item['name'];
            $updateData['name'] = $this->name;
        }

        if (isset($item['description'])) {
            $this->description = $item['description'];
            $updateData['description'] = $this->description;
        }

        if (isset($item['price'])) {
            $this->price = (float) $item['price'];
            $updateData['price'] = $this->price;
        }

        if (isset($item['qtd'])) {
            $this->qtd = (int) $item['qtd'];
            $updateData['qtd'] = $this->qtd;
        }

        try {
            try {
                $this->existsByName($this->name);
            } catch (\Exception $e) {
                echo "Erro: ", $e->getMessage(), "\n";
                return false;
            }

            if (!empty($updateData)) {
                $objDatabase = new Database('products');
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

    public function updateProductsRel($item)
    {
        $this->id = $item['id'];
        $updateData = [];

        // echo "<pre>"; print_r($this->id); echo "</pre>"; exit;

        if (isset($item['product_id'])) {
            $this->product_id = (int) $item['product_id'];
            $updateData['product_id'] = $this->product_id;
        }

        if (isset($item['product_type_id'])) {
            $this->product_type_id = (int) $item['product_type_id'];
            $updateData['product_type_id'] = $this->product_type_id;
        }

        if (isset($item['old_product_type_id'])) {
            $this->old_product_type_id = (int) $item['old_product_type_id'];
        }

        try {
            if (!empty($updateData)) {
                $where = "product_id = " . $this->id . " AND product_type_id = " . $this->old_product_type_id;
                // echo "<pre>"; print_r($where); echo "</pre>"; exit;

                $objDatabaseRel = new Database('product_product_type');
                $objDatabaseRel->update($where, $updateData);

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
    public function deleteProducts($id)
    {
        // echo "<pre>"; print_r($id); echo "</pre>"; exit;
        try {
            $this->id = $id['id'];

            $objDatabase = new Database('products');
            $where = "id = '" . $this->id . "'";
            $objDatabase->delete($where);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
            return false;
        }
    }

    public function deleteProductsRel($id)
    {
        try {
            $this->id = $id['id'];
            $this->product_id = $id['product_id'];

            // echo "<pre>"; print_r($this->id); echo "</pre>"; exit;
            // echo "<pre>"; print_r($this->product_type_id); echo "</pre>"; exit;

            $objDatabase = new Database('product_product_type');
            $where = "product_id = '" . $this->id . "' AND product_type_id = '" . $this->product_type_id . "'";
            $objDatabase->delete($where);

            return true;
        } catch (\Exception $e) {
            echo "Erro: ",  $e->getMessage(), "\n";
            return false;
        }
    }
}
