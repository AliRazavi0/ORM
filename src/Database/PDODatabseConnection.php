<?php 
namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exceptions\DataBaseConnectionException;
use PDO;
use PDOException;

class PDODatabseConnection implements DatabaseConnectionInterface{
    protected $connection;
    protected $config;

    public function __construct(array $config)
    {

        $this->config=$config;
    }

    public function connect(){

        $dsn=$this->generateDsn($this->config);
        try {
            $this->connection = new PDO(...$dsn);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

        } catch(PDOException $e) {
            throw new DataBaseConnectionException($e->getMessage());
        }

        return $this;
    }

    public function getConnection(){
        return $this->connection;
    }

    private function generateDsn(array $config)
    {
        $dsn="{$config['driver']}:host={$config['db_host']};dbname={$config['db_name']}";

        return[
            $dsn,
            $config['db_user'],
            $config['db_password']
        ];
    }
}