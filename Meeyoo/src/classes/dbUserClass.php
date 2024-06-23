<?php
include ("dbClassStatic.php");
function replaceNull(&$value, $key) {
    if ($value === null && $key !== 'age') {
        $value = 'none';
    }
}
 class DbUser{   
    private int $id;
    private string $name;
    private string $surname;
    private int $age;
    protected string $email;
    private string $gender;
    private string $description;

    public function __construct($name, $email){
        $this->name= $name;
        $this->email= $email;
        $this->age = 0;
        $this->surname = "none";
        $this->gender = "none";   
    }
    public function addUserToDbAndSetId(){
        if(isset($this->id)){
            throw new Exception('User already in db');
        }
        if(!isset($this->name)){
            return false;
        }
        if(!isset($this->email)){
            return false;
        }
        if(!DbClass::init()){
            throw new Exception('Failed to connect to db');
        }
        if (!DbClass::addUser($this->email,$this->name,$this->surname,$this->gender,$this->age)){
            throw new Exception('Failed to add user');
        }
        if(!($this->id = DbClass::getUserIdByEmail($this->email))){
            throw new Exception('User not found in db');
        }
        return true;
        
    }
    public function validateInput($arr)
    {
        foreach ($arr as $value) {
            if(empty($value)){
                return false;
            }
            if ($value != ($value2 = strip_tags($value))) {
                return false; 
            }
        }
    return true;
        
    }
    public function getFromDbById($id){
        if(DbClass::init() === false){
            throw new Exception('Failed to connect to db');
        }
        if(($userInfo = DbClass::getUserInfoById($id)) === false){
            throw new Exception('Failed to get user associative array');
        }
  
        array_walk($userInfo,'replaceNull');
        $this->id = $userInfo["id"];
        $this->setName($userInfo["name"]);
        $this->setSurname($userInfo["surname"]);
        $this->setAge($userInfo["age"]);
        $this->setEmail($userInfo["email"]);
        $this->setGender($userInfo["gender"]);
        $this->setDescription($userInfo["description"]);
        return true;
    }
    public function getFromDbByEmail($email){
        if(DbClass::init() === false){
            throw new Exception('Failed to connect to db');
        }
        if(($id = DbClass::getUserIdByEmail($email))===false){
            throw new Exception('User not found in db');
        }
        if(($userInfo = DbClass::getUserInfoById($id)) === false){
            throw new Exception('Failed to get user associative array');
        }
  
        array_walk($userInfo,'replaceNull');
        $this->id = $id;
        $this->setName($userInfo["name"]);
        $this->setSurname($userInfo["surname"]);
        $this->setAge($userInfo["age"]);
        $this->setEmail($userInfo["email"]);
        $this->setGender($userInfo["gender"]);
        $this->setDescription($userInfo["description"]);
        return true;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function setDescription($descritpion){
        $this->description = $descritpion;
    }
    public function setSurname($surname){
        $this->surname = $surname;
    }
    public function setAge($age){
    $this->age = $age;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    public function setGender($gender){
        $this->gender = $gender;
    }
    public function getId(){
        return $this->id;
    }
    public function getDescription(){
        if($this->description === null){
            $this->description === "none";
        }
        return $this->description;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getName(){
        return $this->name;
    }
    public function getSurname(){
        return $this->surname;
    }
    public function getGender(){
        return $this->gender;
    }
    public function getAge(){
        return $this->age;
    }
public function verifyPassword(string $password){
    if(!isset($this->id)){
        throw new Exception('User not in sync with db');
    }
    if(!DbClass::init()){
        throw new Exception('Failed to connect to db');
    }
    if(DbClass::verifyPasswordFindByEmail($this->email, $password)){
        return true;
    }else{
        return false;
    }
}
    public function setPassword(string $password){
        if(!isset($this->id)){
            throw new Exception('User not in sync with db');
        }
        if(!DbClass::init()){
            throw new Exception('Failed to connect with db');
        }
        if(DbClass::setPasswordFindByEmail($this->email,$password)){
            return true;
        }else return false;
    }
    public function getUserArray(){
        if(!isset($this->id)){
            throw new Exception('User not in sync with db');
        }
        if(($arr = DbClass::getUserInfoById($this->id))===false){
            throw new Exception('Failed to get user associative array from db');
        }else{
            return $arr;
        }
    }
     public function updateWithDb(){
       if(!isset($this->id)){
        throw new Exception('User not verified with db');
    }
    if(!DbClass::init()){
        return false;
    }
    if(!DbClass::editUserFindByEmail($this->email,$this->name,$this->surname,$this->gender,$this->age)){
        return false;
     }
}
public function deleteFromDb(){
    if(!isset($this->id)){
        throw new Exception('User not in sync with db');
    }
    if(!DbClass::init()){
        throw new Exception('Failed to connect with Db');
    }
    if(!DbClass::deleteUserFindByEmail($this->email)){
        throw new Exception('Failed to delete');
    }
    unset($this->id);
    return true;
}
public function checkIfInDb(){
    if(!DbClass::init()){
        return false;
    }
    if(!DbClass::getUserIdByEmail( $this->email )){
        return false;
}
return true;
}
}



?>