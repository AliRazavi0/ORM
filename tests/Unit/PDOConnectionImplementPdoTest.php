<?php

namespace Tests\Unit;

use App\Helpers\Config;

use PHPUnit\Framework\TestCase;
use App\Database\PDODatabseConnection;
use App\Contracts\DatabaseConnectionInterface;

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


    public function getConfig()
    {
        $config = Config::get('database.php', 'pdo_test');
        return $config;
    }


}