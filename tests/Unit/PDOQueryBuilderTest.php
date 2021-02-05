<?php

use App\Helpers\Config;
use PHPUnit\Framework\TestCase;
use App\Database\PDOQueryBuilder;
use App\Database\PDODatabseConnection;

class PDOQueryBuilderTest extends TestCase{
    public function testInCanCreateData(){
        $pdoConnection=new PDODatabseConnection($this->getConfig());

        $queryBuilder=new PDOQueryBuilder($pdoConnection->connect());

        $data=[
            'subject'=>'مشکل در اتصال به دیتایس ',
            'link'=>'http://link.com',
            'username'=>'AliRazavi',
            'email'=>'razavi.ali.1998@gmail.com',
        ];

        $result=$queryBuilder->table('bugs')->create($data);

      
        $this->assertIsInt($result);
        $this->assertGreaterThan(0,$result);

    }

    private function getConfig()
    {
        $config = Config::get('database.php', 'pdo_test');
        return $config;
    }
}