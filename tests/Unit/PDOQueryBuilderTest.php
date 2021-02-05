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

        $this->queryBuilder->beginTransaction();

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

        $this->assertEquals(1,$result);

    }

    public function testItCanDeleteRecord(){
        $this->multipleInsertIntoDb(4);


        $result=$this->queryBuilder->table('bugs')->where('username','AliRazavi')->delete();

        $this->assertEquals(4,$result);


    }
    public function testItCanFetchData (){
        $this->multipleInsertIntoDb(10,[
            'username'=>'Mohammad',
        ]);

        $result=$this->queryBuilder->table('bugs')->where('username','Mohammad')->get();


        $this->assertIsArray($result);

        $this->assertCount(10,$result);
    }
    private function getConfig()
    {
        $config = Config::get('database.php', 'pdo_test');
        return $config;
    }

    public function tearDown() :void{
        $this->queryBuilder->rollback();
        parent::tearDown();
    }

    private function insertIntoDb($options=[]){


        $data=array_merge([
            'subject'=>'مشکل در اتصال به دیتایس ',
            'link'=>'http://link.com',
            'username'=>'AliRazavi',
            'email'=>'razavi.ali.1998@gmail.com',
        ],$options);

       return $this->queryBuilder->table('bugs')->create($data);
    }

    public function testItCanFetchGetDataWithParams(){
        $this->multipleInsertIntoDb(10,[
            'username'=>'Mohammad',
        ]);

        $result=$this->queryBuilder->table('bugs')->where('username','Mohammad')->get([
            'username',
            'email',
            'subject'
        ]);

        $this->assertIsArray($result);
        $this->assertObjectHasAttribute('username',$result[0]);
        $this->assertObjectHasAttribute('email',$result[0]);
        $this->assertObjectHasAttribute('subject',$result[0]);

        $result=json_decode(json_encode($result[0]),true);

        $this->assertEquals(['username','email','subject'],array_keys($result));


    }

    public function testItCanFirstRow(){
        $this->multipleInsertIntoDb(10,[
            'username'=>'Mohammad',
        ]);

        $result=$this->queryBuilder->table('bugs')->where('username','Mohammad')->first();


        $this->assertIsObject($result);    
        $this->assertObjectHasAttribute('username',$result);
        $this->assertObjectHasAttribute('email',$result);
        $this->assertObjectHasAttribute('subject',$result);
        $this->assertObjectHasAttribute('id',$result);
        $this->assertObjectHasAttribute('link',$result);
        
    }

    public function testItCanFindWithId(){
        $id=$this->insertIntoDb([
            'username'=>'Mohammad'
        ]);

        $result=$this->queryBuilder->table('bugs')->find($id);

        $this->assertIsObject($result);
        $this->assertEquals('Mohammad',$result->username);
    }

    private function multipleInsertIntoDb($count,$options=[]){
        for ($i=1; $i <= $count; $i++) { 
            $this->insertIntoDb($options);
        }
    }
}