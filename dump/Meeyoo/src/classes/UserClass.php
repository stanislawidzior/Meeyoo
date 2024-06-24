<?php
include("dbUserClass.php");
class User extends dbUser{
    private $friends;
  public function __construct($name = "none",$email = "none"){
    parent::__construct($name,$email);
    $friends = [];
  }
    public function sendFriendInvite($to){
      
        if(!$this->sendMessage($to,"Friend request")){
            return false;
        }else return true;


    }
  
 
    public function validateInput($arr){
        
        if(!parent::validateInput($arr)){
           
            return false;
        }

        $result = true;
     
       // print_r($arr);
        foreach($arr as $type=>$value){
            switch ($type) {
                case "email":
                    $pattern = "/^((?!\\.)[\\w\\-_.]*[^.])@\\w+\\.\\w+(\\.\\w+)?[^.\\W]$/";
                    $result  = preg_match($pattern, $value);
                    break;
                    case "name":
                        $result  = is_string($value);
                        break;
                        case "surname":
                            $result  = is_string($value);
                            break;
                            case "gender":
                                $result  = is_string($value);
                                break;
                case "age":
                    $result = is_numeric($value);
                    break;
                case "password":
                    $result = preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,64}$/', $value);
                    break;
                default:
                    break;
            }
            if($result == false){
                //echo $type;
                return false;
            }
    }
    return true;  
}

}
//$arr = array("name"=>"jan","surname"=>"brzechwa","email"=>"email@email.com","age"=>12,"gender"=>"rather not say","password"=>"Lipnezwierzeta123!");
//$user = new User("name","email@email.com");
//$user->setAge($arr["age"]);
//$user->setGender($arr["gender"]);
//$user->setSurname($arr["surname"]);
//echo $user->validateInput($arr)?"dobre":"niedobre";

//echo $user->validateInput($arr);

?>