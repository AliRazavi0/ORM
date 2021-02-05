<?php

namespace Tests\Unit;

use App\Helpers\Config;

use PHPUnit\Framework\TestCase;
use App\Database\PDODatabseConnection;
use App\Contracts\DatabaseConnectionInterface;
use App\Exceptions\DataBaseConnectionException;

class PDOConnectionImplementPdoTest extends TestCase
{


    public function testPDOConnectionImplementsPDO()
    {

        $config = $this->getConfig();


        $pdo = new PDODatabseConnection($config);

        $this->assertInstanceOf(DatabaseConnectionInterface::class, $pdo);


    }

    public function testConnectMethodShouldBeConnectToDatabase()
    {
        $config = $this->getConfig();

        $pdoConnection = new PDODatabseConnection($config);

        $pdoConnection->connect();

        $this->assertInstanceOf(\PDO::class, $pdoConnection->getConnection());
    }

    public function testItThrowExptionValid(){
        $this->expectException(DataBaseConnectionException::class);
        $config=$this->getConfig();

        $config['db_password']="123456";

        $pdoConnection=new PDODatabseConnection($config);

        $pdoConnection->connect();


    }


    private function getConfig()
    {
        $config = Config::get('database.php', 'pdo_test');
        return $config;
    }


}