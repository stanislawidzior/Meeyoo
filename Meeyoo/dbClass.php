<?php
class DbClass{
   private $dbuser = "root", $dbpass = "", $dbname = "data", $dbhost = "localhost";
   private $db;
   public function __construct() {
    try{
    $this->db = new PDO("mysql:host=".$this->dbhost.";dbname=".$this->dbname, $this->dbuser, $this->dbpass);
    }catch (Exception $error){
        die("Connection failed: " . $error->getMessage());
    }
   }
   private function valid($type, $value){
    switch ($type) {
        case "email":
            $pattern = "/^((?!\\.)[\\w\\-_.]*[^.])@\\w+\\.\\w+(\\.\\w+)?[^.\\W]$/";
            if (preg_match($pattern, $value)) {
                return true;
            } else {
                return false;
            }
        case "string":
            return is_string($value);
        case "int":
            return is_numeric($value);
        default:
            return false;
    }
}
public function verifyPasswordFindByEmail($email,$password){
    if(!$this->valid("email",$email)){
        return false;
    }
    if($this->getUserIdByEmail($email) === false){
        return false;
    }
    try{
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statment = $this->db->prepare("Select password From user where email=:email");
        $statment->bindParam("email",$email,PDO::PARAM_STR);
        $statment->execute();
        $hashedPassword = $statment->fetch(PDO::FETCH_ASSOC)["password"];
    }catch(Exception $error){
        echo "Task not completed: " . $error->getMessage();
        return false;
    }
    if($statment === false){
        return false;
    }
    if(password_verify($password,$hashedPassword)){
        return true;
    }else{return false;}

}
public function setPasswordFindByEmail($email,$password){
        if(!$this->valid("email",$email)){
            return false;
        }
        if(($id = $this->getUserIdByEmail($email)) === false){
            return false;
        }
        try{
                $this->db->beginTransaction();
                $hashedPassword = password_hash((string)$password, PASSWORD_BCRYPT);
                $statement = $this->db->prepare("UPDATE `user` SET  `password` = :passw WHERE `id` = :id");  
                $statement->bindParam(':passw', $hashedPassword,PDO::PARAM_STR);
                $statement->bindParam(':id', $id["id"],PDO::PARAM_INT);
                $statement->execute();
                $this->db->commit();
                return true;
            }catch (Exception $error) {
                $this->db->rollBack();
                        echo "Transaction not completed: " . $error->getMessage();
            return false;
}
}
 public function addUser($email, $name, $surname = "none", $gender = "none", $age = 0){
    if(!($this->valid("email",$email)&&$this->valid("string",$name) && $this->valid("string",$gender) && $this->valid("string",$surname) && $this->valid("int",$age))) {
        return false;
    }
    $statement = $this->db->prepare("INSERT INTO `user` (`gender`, `name`, `surname`, `email`, `age`, `private`)
    VALUES (:gender, :nam, :surn, :email, :age, :priv)");
    $private = false;
    $statement->bindParam(':gender', $gender, PDO::PARAM_STR);
    $statement->bindParam(':nam', $name, PDO::PARAM_STR);
    $statement->bindParam(':age', $age, PDO::PARAM_INT);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':surn', $surname, PDO::PARAM_STR);
    $statement->bindParam(':priv', $private, PDO::PARAM_INT);
    try{
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();
      $statement->execute();
      $this->db->commit();
      return true;
    }catch (Exception $error) {
        $this->db->rollBack();
        echo "Transaction not completed: " . $error->getMessage();
        return false;
    }
 }
 public function deleteUserFindByEmail($email) {
    if(($id = $this->getUserIdByEmail($email)) === false){
        return false;
    }
    $id = $id["id"];
    try {
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $this->db->prepare("DELETE FROM `user` WHERE `id` = :id");
        $statement->bindParam(':id', $id, PDO::PARAM_INT); 
        $this->db->beginTransaction();
        $statement->execute();
        $this->db->commit();
        return true;
    } catch (PDOException $error) {
        $this->db->rollBack();
        echo "Transaction not completed: " . $error->getMessage();
        return false;
    }
}
public function editUserFindByEmail($email, $name, $surname, $gender, $age) {
    if (!($this->valid("email", $email) &&  $this->valid("string", $name) && $this->valid("string", $surname) && $this->valid("string", $gender) && $this->valid("int", $age))) {
        echo "here";
        return false;
    }
    if (($user = $this->getUserIdByEmail($email) )=== false) {
        return false;
    }
    $id = $user["id"]; 
    
    try {
        $statement = $this->db->prepare("UPDATE `user` SET `name` = :name, `surname` = :surname, `gender` = :gender, `age` = :age WHERE `id` = :id"); 
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement->bindParam(':name', $name, PDO::PARAM_STR);
        $statement->bindParam(':surname', $surname, PDO::PARAM_STR);
        $statement->bindParam(':gender', $gender, PDO::PARAM_STR);
        $statement->bindParam(':age', $age, PDO::PARAM_INT);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        
        $this->db->beginTransaction();
        $statement->execute();
        $this->db->commit();
        return true; 
    } catch (PDOException $error) {
        $this->db->rollBack();
        echo "Transaction not completed: " . $error->getMessage();
        return false;
    }
}


 
   public function getUserIdByEmail($email){
        if(!$this->valid("email",$email)){
            return false;
        }
        $result = $this->db->query("SELECT id From user where email = \"".$email."\"");
        if(!$result){
            return false;
        }
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    public function sendMessageFindByEmail($from,$to, $message){
        if(!($this->valid("email",$from) && ($this->valid("email",$to)))) {
            return false;
    }
    if($this->getUserIdByEmail(($from))===false || $this->getUserIdByEmail($to) === false)
    {
        return false;
    }
    $message = htmlentities($message);
    if(empty($message)){
        return false;
    }
   $statement = $this->db->prepare("INSERT INTO `messages` (`sender_id`, `receiver_id`, `message_content`)
SELECT u1.`id` AS `sender_id`, u2.`id` AS `receiver_id`, :mess AS `message_content`
FROM `user` AS u1
JOIN `user` AS u2 ON u1.`email` = :from AND u2.`email` = :to");
$statement->bindParam(':from', $from, PDO::PARAM_STR);
$statement->bindParam(':to', $to, PDO::PARAM_STR);
$statement->bindParam(':mess', $message, PDO::PARAM_STR);
try{
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->beginTransaction();
  $statement->execute();
  $this->db->commit();
}catch (Exception $error) {
    $this->db->rollBack();
    echo "Transaction not completed: " . $error->getMessage();
}
    }
    public function deleteMessageFindByEmail($from, $to, $messageId) {
        if (!($this->valid("email", $from) && $this->valid("email", $to))) {
            return false;
        }
        $senderId = $this->getUserIdByEmail($from);
        $receiverId = $this->getUserIdByEmail($to);
        if ($senderId === false || $receiverId === false) {
            return false;
        }
        
        try {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $this->db->prepare("DELETE FROM `messages` WHERE `sender_id` = :senderId AND `receiver_id` = :receiverId AND `message_id` = :messageId");
            $statement->bindParam(':senderId', $senderId['id'], PDO::PARAM_INT);
            $statement->bindParam(':receiverId', $receiverId['id'], PDO::PARAM_INT);
            $statement->bindParam(':messageId', $messageId, PDO::PARAM_INT);
        
             $this->db->beginTransaction();
            $statement->execute();
            $this->db->commit();
            return true;
        } catch (PDOException $error) {
            $this->db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
            return false;
        }
    }
    
   
public function getUserMessagesFindByEmail($email){
    if(!$this->valid("email",$email)){
        return false;
    }
    if (($user = $this->getUserIdByEmail($email)) === false) {
        return false;
    }
    $id = $user["id"];
    $statement = $this->db->prepare("SELECT message_id,receiver_id,sender_id,message_content,sent_at,read_at FROM messages WHERE receiver_id = :id");
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    try{
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();
        $statement->execute();
        $messages = $statement->fetchAll(PDO::FETCH_ASSOC);
      $this->db->commit();
    }catch (Exception $error) {
        $this->db->rollBack();
        echo "Transaction not completed: " . $error->getMessage();
    }
    if(isset($messages) && $messages !== false && $messages !== null){
    return $messages;
    }else{return false;}
}
public function customDbQuery($query){ //do wypierdoloenia bardzo
    try{
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $this->db->prepare($query);
        $this->db->beginTransaction();
        $statement->execute();
        $return = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->db->commit();
        return $return;
    }catch (Exception $error) {
        $this->db->rollBack();
        echo "Transaction not completed: " . $error->getMessage();
        return false;
    }
    
}
public function getUsers($sortBy,$direction,$limit){
    if(!in_array($sortBy,array("id","name","surname","age"))){
        return false;
    }
    if(!($direction == "asc" || $direction == "desc")){
        return false;
    }
    if(!($this->valid("int",$limit))){
        return false;
    }
    try{
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $this->db->prepare("Select * From user  ORDER BY $sortBy $direction LIMIT :limit");
    $query->bindParam("limit", $limit, PDO::PARAM_INT);
    $query->execute();
    $return = $query->fetchAll(PDO::FETCH_ASSOC);
    return $return;
    }catch (Exception $error) {
        echo "Query not completed: " . $error->getMessage();
        return false;
    }
}


}
$base = new DbClass();
//$base->setPasswordFindByEmail("ex@ex.ex","123");
if($base->verifyPasswordFindByEmail("ex@ex.ex","123")){
    echo "dziala";
}else echo "nie dziala";
?>