<?php
namespace Tests\Unit;

use App\Helpers\Config;
use PHPUnit\Framework\TestCase;
use App\Database\PDODatabseConnection;
use App\Contracts\DatabaseConnectionInterface;

class PDOConnectionImplementPdoTest extends TestCase{
    

    public function testPDOConnectionImplementsPDO(){

        $config=$this->getConfig();

        $pdo=new PDODatabseConnection($config);

        $this->assertInstanceOf(DatabaseConnectionInterface::class,$pdo);


    }



    private function getConfig(){
        $config=Config::get('database.php','pdo_test');

        return $config;
    }


}