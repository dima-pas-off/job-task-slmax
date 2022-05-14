<?php




class User 
{
    /**
     *   Класс пользователя.
     *   
     *   Используется глобальная переменная БД, которая опеределена в файле config/connectToDatabase.php в
     *   В конструкторе принимает данные пользователя, идет поиск по введенному ID в БД (метод searchUserById),
     *   Если пользователь найден то данные для полей объекта берем из БД, иначе с помощью методов установки значений проводим валидацию
     *   введенных данных и сохраняем пользователя в БД (save метод)
     *  
     *      
     *  В методе formattingUser в параметрах определяем будет ли форматированы данных Gender и(или) Birthday
     *  также в этом методе данные из объекта записываются в объект stdClass и возвращен в самом методе
     *  
     * 
     */


    private  $database;

    private $id;
    private $firstName;
    private $lastName;
    private $birthday;
    private $gender;
    private $city;


    /**
     * В конструкторе принимает данные пользователя, идет поиск по введенному ID в БД (метод searchUserById),
     *   Если пользователь найден то данные для полей объекта берем из БД, иначе с помощью методов установки значений проводим валидацию
     *   введенных данных и сохраняем пользователя в БД (save метод)
     * 
     */

    public function __construct(int $id, $firstName = null, $lastName = null,$birthday = null,int $gender = null, $city = null) {

        global $DB;
        $this->database = $DB;
        $user = ($this->searchUserById($id))[0];
  
        if($user) {

            $this->id = $user['ID'];
            $this->firstName = $user['FIRST_NAME'];
            $this->lastName = $user['LAST_NAME'];
            $this->birthday = $user['BIRTHDAY'];
            $this->gender = $user['GENDER'];
            $this->city = $user['CITY'];

        } else {

            try {
               $this->setId($id)
                    ->setFirstName($firstName)
                    ->setLastName($lastName)
                    ->setBirthday($birthday)
                    ->setGender($gender)
                    ->setCity($city)
                    ->save();
            }
            catch(Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    private function setId(int $id) {

        if(!is_int($id) && $id > 0) {
            throw new Exception('Только число больше 0');
        }

        $this->id = $id;

        return $this;
    }

    private function setFirstName($firstName) {


        if(!(preg_match('/^[a-z]{1,50}$/i', $firstName))) {
            throw new Exception('Только буквы, длина не более 50 символов');
        }

        $this->firstName = $firstName;

        return $this;

    }


    private function setLastName($lastName) {
        if(!(preg_match('/^[a-z]{1,50}$/i', $lastName))) {
            throw new Exception('Только буквы, длина не более 50 символов');
        }

        $this->lastName = $lastName;

        return $this;
    }

    private function setBirthday($date) {

        $objectDateTime = new DateTime($date);
        $this->birthday =$objectDateTime->format('Y-m-d');
        return $this;
    }


    private function setGender(int $gender) {

        if($gender !== 0 && $gender !== 1) {
            throw new Exception('Должно иметь значени 0 или 1');
        }
        
        $this->gender = $gender;

        return $this;
    }

    private function setCity($city) {
        $this->city = $city;

        return $this;
    }


    private function searchUserById($id) {
        $prepareQuery = $this->database->prepare("SELECT * FROM users WHERE ID = ?");
        $prepareQuery->execute(array($id));
        return $prepareQuery->fetchAll();
    }

    public static function convertingBirthdayToAge(DateTime $birthday) {

        if(is_null($birthday)) {
            return null;
        }

        $nowDate = new DateTime();

        $diffDates = $nowDate->diff($birthday)->y;
        return $diffDates;
    }


    public static function convertingGenderToString($gender) {
        if(is_null($gender)) return null;

        return $gender === 0 ? 'М' : 'Ж';
    }


    public function save() {
        $prepareQuery = $this->database->prepare("INSERT INTO users (ID, FIRST_NAME, LAST_NAME, BIRTHDAY, GENDER, CITY) VALUES (:id,:firstName, :lastName, :birthday, :gender, :city)");
        $prepareQuery->execute(array(
                                     'id'        => $this->id,
                                     'firstName' => $this->firstName,
                                     'lastName'  => $this->lastName,
                                     'birthday'  => $this->birthday,
                                     'gender'    => $this->gender,
                                     'city'      => $this->city));

    }

    public function formattingUser(bool $isFormattingGender, bool $isFormattingBirthday): stdClass {

        $gender = $this->gender; 
        $birthday = $this->birthday;

        $stdObject = new stdClass();

        if($isFormattingGender) {
            $gender = User::convertingGenderToString($gender);
        } 

        if($isFormattingBirthday) {
            $birthday = User::convertingBirthdayToAge(new DateTime($birthday));
        }


        $stdObject->id = $this->id;
        $stdObject->firstName = $this->firstName;
        $stdObject->lastName = $this->lastName;
        $stdObject->birthday = $birthday;
        $stdObject->gender = $gender;
        $stdObject->city = $this->city;

        return $stdObject;
    }


    public function deleteUser() {
        $prepareQuery = $this->database->prepare("DELETE FROM users WHERE ID = ?");
        $prepareQuery->execute(array($this->id));
    }


 
}

?>