<?php

namespace app\config;

use \PDO;
use PDOException;

class Database
{
    const HOST = 'postgresql';

    const NAME = 'softexpert';

    const USER = 'root';

    const PASS = 'ase321';

    const PORT = 5432;

    private $table;

    private $connection;

    /**
     * The function is a constructor in PHP that sets the table property and establishes a database
     * connection.
     * 
     * @param table The `table` parameter in the constructor is used to specify the name of the
     * database table that the class will be interacting with. This parameter is optional and can be
     * passed when creating an instance of the class.
     */
    public function __construct($table = null)
    {
        $this->table = $table;

        $this->setConnection();
    }

    /**
     * The function `setConnection` establishes a connection to a PostgreSQL database using PDO in PHP.
     */
    private function setConnection()
    {
        try {
            $this->connection = new PDO('pgsql:host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::NAME, self::USER, self::PASS);;
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * The function executes a SQL query with optional parameters and returns the statement object or
     * outputs an error message if an exception occurs.
     * 
     * @param query The `query` parameter in the `execute` function represents the SQL query that you
     * want to execute. This query can be a SELECT, INSERT, UPDATE, DELETE, or any other valid SQL
     * statement that you want to run against the database.
     * @param params The `` parameter in the `execute` function is an optional parameter that
     * allows you to pass an array of values to be bound to the placeholders in the SQL query. These
     * values will be used by the prepared statement when it is executed. This parameter provides a way
     * to dynamically pass values to the
     * 
     * @return The `execute` method is returning the statement object after preparing and executing the
     * query with the provided parameters.
     */
    public function execute($query, $params = [])
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);

            return $statement;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * This PHP function performs a SELECT query on a database table with optional WHERE clause, ORDER
     * BY clause, and LIMIT clause.
     * 
     * @param where The `where` parameter in the `select` function is used to specify the conditions for
     * filtering the rows in the database table. It is typically a string that represents the conditions
     * to be met for the rows to be selected. For example, `id = 1` or `name = 'John
     * @param order The `order` parameter in the `select` function is used to specify the ordering of
     * the results returned by the query. It allows you to specify the column(s) and the direction (ASC
     * or DESC) for sorting the results.
     * @param limit The `limit` parameter in the `select` function is used to specify the maximum number
     * of rows to be returned in the query result. It is typically used to limit the number of records
     * retrieved from the database.
     * @param fields The `fields` parameter in the `select` function is used to specify which columns
     * you want to retrieve from the database table. By default, if no specific fields are provided, it
     * will select all columns (`*`).
     * 
     * @return The `select` function is returning the result of executing a SQL query constructed based
     * on the provided parameters. The query selects data from a table specified by `->table`, with
     * optional conditions specified in ``, ordering specified in ``, and limiting the
     * number of results specified in ``. The query is executed using the `execute` method, and
     * the result is returned.
     */
    // public function select($where = null, $order = null, $limit = null, $fields = '*')
    // {
    //     $where = strlen($where) ? 'WHERE ' . $where : '';
    //     $order = strlen($order) ? 'ORDER BY' . $order : '';
    //     $limit = strlen($limit) ? 'LIMIT ' . $limit : '';

    //     $query = 'SELECT * FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

    //     // echo "<pre>"; print_r($query); echo "</pre>"; exit;

    //     return $this->execute($query);
    // }
    public function select($where = null, $order = null, $limit = null, $fields = '*', $join = null)
    {
        $where = strlen($where) ? 'WHERE ' . $where : '';
        $order = strlen($order) ? 'ORDER BY ' . $order : '';
        $limit = strlen($limit) ? 'LIMIT ' . $limit : '';
        $join = !empty($join) ? $join : '';

        // echo "<pre>"; print_r($join); echo "</pre>"; exit;

        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $join . ' ' . $where . ' ' . $order . ' ' . $limit;

        return $this->execute($query);
    }

    /**
     * The `public function insert()` method in the `Database` class is used to insert a new
     * record into a database table. Here is a breakdown of what the method is doing: 
     *
     * @param binds This line of code is using a prepared statement to safely insert values 
     * into the database and prevent SQL injection attacks. The bind_param() function is binding 
     * the parameters of the prepared statement to the values that will be inserted into the database.
     **/

    public function insert($values)
    {
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');

        // echo "<pre>"; print_r($binds); echo "</pre>"; exit;

        $query = 'INSERT INTO ' . $this->table . ' (' . implode(",", $fields) . ') VALUES (' . implode(",", $binds) . ') RETURNING id';
        // echo "<pre>"; print_r($query); echo "</pre>"; exit;

        try {
            $stmt = $this->execute($query, array_values($values));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        } catch (PDOException $e) {
            // Trate a exceção conforme necessário
            die('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * The function deletes records from a database table based on a specified condition.
     * 
     * @param where The `` parameter in the `delete` function is used to specify the condition
     * for deleting records from the database table. It is typically a string that represents the
     * condition based on which the records will be deleted.
     * 
     * @return The `delete` function is returning a boolean value `true` after executing the delete
     * query.
     */
    public function delete($where)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;
        // echo "<pre>"; print_r($query); echo "</pre>"; exit;

        //Executar a query
        $this->execute($query);
        return true;
    }

    /**
     * The function updates a record in a database table based on specified conditions and values.
     * 
     * @param where The `` parameter in the `update` function is used to specify the condition
     * that determines which records in the database table will be updated. It typically consists of a
     * SQL WHERE clause that specifies the conditions that must be met for the update operation to be
     * applied to specific rows in the table.
     * @param values The `values` parameter in the `update` function is an associative array where the
     * keys represent the column names and the values represent the new values that you want to update
     * in those columns.
     * 
     * @return The `update` function is returning a boolean value `true` after executing the SQL query
     * to update the database record.
     */
    public function update($where, $values)
    {
        // echo "<pre>"; print_r($values); echo "</pre>"; exit;

        $fields  = array_keys($values);
        $values = array_values($values);

        $query = 'UPDATE ' . $this->table . ' SET ' . implode("=?,", $fields) . '=? WHERE ' . $where;

        $this->execute($query, $values);
        return true;
    }
}
