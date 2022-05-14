<?php


final class Database 
{

    /**
     * Класс Базы данных реалиющий паттерн Singleton
     * Метод getInstance() создает сущность класса БД
     * Метод connectToDb получает на вход конфигурационные данные базы данных и создает объект PDO
     */

    private static $instance;
    private $database;

    protected function __construct()
    {

    }


    public static function getInstance(): Database {

        if(is_null(static::$instance)) {
            
            static::$instance = new static();
        }

        return static::$instance;
        
    }


    public function connectToDb($configDatabase) {

        if(is_null($this->database)) {
            $this->database = new \PDO($configDatabase['dsn'], $configDatabase['user'], $configDatabase['password'], $configDatabase['option']);
        }
        return $this->database;

    }
}

?>