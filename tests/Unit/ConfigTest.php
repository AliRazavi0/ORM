<?php 
namespace Tests\Unit;

use App\Exceptions\ConfigfileNotFoundExceptions;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase{
    public function testIsThrowExpetionsNotFoundFile(){
        $this->expectException(ConfigfileNotFoundExceptions::class);
        $config=Config::getFileContent('test');
    }

    public function testGetMethodReturnValidData(){
        $config=Config::get('database.php','pdo');
     

        $expectData=[
            'driver'=>'mysql',
            'db_host'=>'127.0.0.1',
            'db_user'=>'root',
            'db_name'=>'orm',
            'db_password'=>'123456789'
        ];

        $this->assertEquals($config,$expectData);
    }
    public function testGetFileContentRetrunArray(){
        
        $config=Config::getFileContent('database.php');
      
        $this->assertIsArray($config);

    }
}