<?php

namespace app\config;

use Exception; //Classe de tratamento de exceções
use \PDO; //Classe de comunicação com o banco de dados
use PDOException; //Classe de tratamento de exceções do banco de dados
use PDOStatement; //Classe de comunicação com métodos do banco de dados

class Database
{
    const HOST = 'postgresql';

    const NAME = 'softexpert';

    const USER = 'root';

    const PASS = 'ase321';

    const PORT = 5432;

    private $table;

    private $connection;

    public function __construct($table = null)
    {
        $this->table = $table;

        $this->setConnection();
    }

    private function setConnection()
    {
        try {
            $this->connection = new PDO('pgsql:host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::NAME, self::USER, self::PASS);;
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

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

    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        $where = strlen($where) ? 'WHERE ' . $where : '';
        $order = strlen($order) ? 'ORDER ' . $order : '';
        $limit = strlen($limit) ? 'LIMIT ' . $limit : '';

        // echo "<pre>"; print_r($query); echo "</pre>"; exit;

        $query = 'SELECT * FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . ' ' . $limit;

        return $this->execute($query);
    }
}
