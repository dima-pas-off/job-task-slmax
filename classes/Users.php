<?php



if(!class_exists(User::class)) {
    echo 'Класс User не существует';
    die();
}


class Users 
{

    /**
     * 
     * Класс для работы с группой пользователей
     * Выше объявления класса идет проверка на доступность класса User, если класс не доступен - скрипт обрывается с сообщеним о причине
     * 
     * В __construct идет формирование массива ID существующих пользователей в БД, который соответсвует условию в параметре
     * $column - название столбца
     * $rule - правило (<, >, <>)
     * $value - значение
     * 
     * В методе createUsersInstance формируется массив объектов пользователей исходя из сформированного массива в конструкторе
     * 
     * В методе deleteUsers идет проверка на существование массива с объектами пользователей, если он пустой, то вызыввается метод его наполнения
     * и вызов методов объектов удаления из БД
     * 
     */

    private $usersId = [];
    private $userObjects = [];


    public function __construct($column, $rule, $value) 
    {
        global $DB;
        
        $prepareQuery = $DB->prepare("SELECT * FROM users WHERE $column $rule '$value' ");
        $prepareQuery->execute();
        
        while($id = $prepareQuery->fetch()) {
           $this->usersId[] = $id['ID'];
        }
        
    }



    public function createUsersInstances() {


        foreach( ($this->usersId) as $id ) {
            $this->userObjects[] = new User($id);
        }

        return $this->userObjects;
    }
    
    
    public function deleteUsers() {
        
        if(!$this->userObjects) {
            $this->createUsersInstances();
        }
        
        foreach(($this->userObjects) as $user) {
            $user->deleteUser();
        }

    }

}



?>