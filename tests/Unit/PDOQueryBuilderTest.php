<?php

use App\Helpers\Config;
use PHPUnit\Framework\TestCase;
use App\Database\PDOQueryBuilder;
use App\Database\PDODatabseConnection;

class PDOQueryBuilderTest extends TestCase{
    private $queryBuilder;

    public function setUp() :void{
        $pdoConnection=new PDODatabseConnection($this->getConfig());

        $this->queryBuilder=new PDOQueryBuilder($pdoConnection->connect());

        parent::setUp();

    }

    public function testItCanCreateData(){
        $result=$this->insertIntoDb();
        $this->assertIsInt($result);
        $this->assertGreaterThan(0,$result);

    }



    public function testItCanUpdateData(){

        $this->insertIntoDb();
        $result=$this->queryBuilder->table('bugs')->where('email','razavi.ali.1998@gmail.com')->update([
            'email'=>'alirazavi253@gmail.com'
        ]);

        $this->assertEquals(2,$result);

    }
    private function getConfig()
    {
        $config = Config::get('database.php', 'pdo_test');
        return $config;
    }

    private function insertIntoDb(){


        $data=[
            'subject'=>'مشکل در اتصال به دیتایس ',
            'link'=>'http://link.com',
            'username'=>'AliRazavi',
            'email'=>'razavi.ali.1998@gmail.com',
        ];

       return $this->queryBuilder->table('bugs')->create($data);
    }
}