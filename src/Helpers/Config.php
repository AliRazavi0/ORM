<?php 
namespace App\Helpers;

use App\Exceptions\ConfigfileNotFoundExceptions;

class Config{
    public static function getFileContent(string $fileName){
        $filePath=realpath(__DIR__."/../Configs/{$fileName}");


        if (!$filePath){
            throw new ConfigfileNotFoundExceptions();
        }

        $fileContent=require $filePath;


        return $fileContent;
    }

    public static function get(string $fileName,string $key){
        $fileContent=self::getFileContent($fileName);

        if (is_null($key)) return $fileContent;

        return $fileContent[$key] ?? null;
    }
}