<?php 
namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use PDO;

class PDOQueryBuilder{
    protected $table;
    protected $connection;
    protected $conditions;
    protected $values;

    public function __construct(DatabaseConnectionInterface $database)
    {   
        $this->connection=$database->getConnection();
    }

    
    public function table(string $table){
        $this->table=$table;

        return $this;
    }

    public function create(array $data){
        $placeholder=[];
        foreach ($data as $column => $value) {
            $placeholder[]='?';
        }


        $fields=implode(',',array_keys($data));
        $placeholder=implode(',',$placeholder);

        $sql="INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholder})";

        $query=$this->connection->prepare($sql);
        $query->execute(array_values($data));

        return (int)$this->connection->lastInsertId();
    }

    public function where(string $column,string $value ){

        $this->conditions[]="{$column}=?";

        $this->values[]=$value;


        return $this;
    }

    public function update(array $data){
        $fields=[];
        foreach ($data as $column => $value) {
            $fields[]="{$column}='{$value}'";
        }
        $fields=implode(',',$fields);
        $conditions=implode(' and ',$this->conditions);
        $sql="UPDATE {$this->table} SET {$fields} WHERE {$conditions}";

        $query=$this->connection->prepare($sql);
        $query->execute($this->values);

        return $query->rowCount();

    }

    public function get(array $columns=['*']){

        $conditions=implode(' and ',$this->conditions);
        $columns=implode(',',$columns);
        $sql="SELECT {$columns} FROM {$this->table} WHERE {$conditions}";

        $query=$this->connection->prepare($sql);
        $query->execute($this->values);

        return $query->fetchAll();
    }


    public function delete(){
        $conditions=implode(' and ',$this->conditions);

        $sql="DELETE FROM {$this->table} WHERE {$conditions} ";
        

        $query=$this->connection->prepare($sql);
        $query->execute($this->values);

        return $query->rowCount();
    }

    public function first(array $columns=['*']){
       $data= $this->get($columns);

        return empty($data) ? null : $data[0];
    }


    public function truncateAllTable(){

        $query=$this->connection->prepare('SHOW TABLES');
 

        $query->execute();

        foreach($query->fetchAll(PDO::FETCH_COLUMN) as $table){
            $this->connection->prepare("TRUNCATE TABLE `{$table}`")->execute();
        }
    }

    public function find(int $id){
        return $this->where('id',$id)->first();
    }

    public function findBy(string $column,$value){
        return $this->where($column,$value)->first();
    }

    public function beginTransaction(){
        $this->connection->beginTransaction();
    }

    public function rollback(){

        $this->connection->rollback();
        
    }
}