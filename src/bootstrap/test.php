<?php

use App\Database\PDODatabseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;

require_once __DIR__. "/../../vendor/autoload.php";


$config=Config::get('database.php','pdo_test');

$pdoConnect=(new PDODatabseConnection($config));

$queryBuilder=new PDOQueryBuilder($pdoConnect->connect());

$queryBuilder->truncateAllTable();

