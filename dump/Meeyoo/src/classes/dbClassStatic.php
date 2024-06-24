<?php
class DbClass {
    private static $dbuser = "root";
    private static $dbpass = "";
    private static $dbname = "data";
    private static $dbhost = "localhost";
    private static $db;

    public static function init() {
        try {
            self::$db = new PDO("mysql:host=".self::$dbhost.";dbname=".self::$dbname, self::$dbuser, self::$dbpass);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (Exception $error) {
            echo "Connection failed: " . $error->getMessage();
            return false;
            
        }
    }
    public static function getUserMessagesFindByEmail($email,$limit,$offset){ //editededited
        if(!self::valid("email",$email)){
            return false;
        }
        if (($user = self::getUserIdByEmail($email)) === false) {
            return false;
        }
        $id = $user;
        $statement = self::$db->prepare("SELECT message_id, receiver_id, sender_id, message_content, sent_at, read_at,type 
        FROM messages WHERE receiver_id = :id  OR sender_id = :id ORDER BY sent_at DESC LIMIT :limit OFFSET :offset");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        try{
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->beginTransaction();
            $statement->execute();
            $messages = $statement->fetchAll(PDO::FETCH_ASSOC);
          self::$db->commit();
        }catch (Exception $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
        }
        if(isset($messages) && $messages !== false && $messages !== null){
        return $messages;
        }else{return false;}
    }
    public static function getUserSentMessagesFindByEmail($email){
        if(!self::valid("email",$email)){
            return false;
        }
        if (($user = self::getUserIdByEmail($email)) === false) {
            return false;
        }
        $id = $user["id"];
        $statement = self::$db->prepare("SELECT message_id,receiver_id,sender_id,message_content,sent_at,read_at FROM messages WHERE sender_id = :id");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        try{
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->beginTransaction();
            $statement->execute();
            $messages = $statement->fetchAll(PDO::FETCH_ASSOC);
          self::$db->commit();
        }catch (Exception $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
        }
        if(isset($messages) && $messages !== false && $messages !== null){
        return $messages;
        }else{return false;}
    }
    
    private static function valid($type, $value) {
        switch ($type) {
            case "email":
                $pattern = "/^((?!\\.)[\\w\\-_.]*[^.])@\\w+\\.\\w+(\\.\\w+)?[^.\\W]$/";
                return preg_match($pattern, $value);
            case "string":
                return is_string($value);
            case "int":
                return is_numeric($value);
            default:
                return false;
        }
    }

    public static function verifyPasswordFindByEmail($email, $password) {
        if (!self::valid("email", $email)) {
            return false;
        }
        $userId = self::getUserIdByEmail($email);
        if ($userId === false) {
            return false;
        }

        try {
            $statement = self::$db->prepare("SELECT password FROM user WHERE email = :email");
            $statement->bindParam(":email", $email, PDO::PARAM_STR);
            $statement->execute();
            $hashedPassword = $statement->fetch(PDO::FETCH_ASSOC)["password"];
        } catch (Exception $error) {
            echo "Task not completed: " . $error->getMessage();
            return false;
        }

        if (!$statement) {
            return false;
        }

        return password_verify($password, $hashedPassword);
    }

    public static function setPasswordFindByEmail($email, $password) {
        if (!self::valid("email", $email)) {
            return false;
        }
        $userId = self::getUserIdByEmail($email);
        if ($userId === false) {
            return false;
        }

        try {
            self::$db->beginTransaction();
            $hashedPassword = password_hash((string)$password, PASSWORD_BCRYPT);
            $statement = self::$db->prepare("UPDATE `user` SET `password` = :passw WHERE `id` = :id");
            $statement->bindParam(':passw', $hashedPassword, PDO::PARAM_STR);
            $statement->bindParam(':id', $userId, PDO::PARAM_INT);
            $statement->execute();
            self::$db->commit();
            return true;
        } catch (Exception $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
            return false;
        }
    }

    public static function addUser($email, $name, $surname = "none", $gender = "none", $age = 0) {
        if (!(self::valid("email", $email) && self::valid("string", $name) && self::valid("string", $gender) && self::valid("string", $surname) && self::valid("int", $age))) {
            return false;
        }
        $private = false;
        try {
            $statement = self::$db->prepare("INSERT INTO `user` (`gender`, `name`, `surname`, `email`, `age`, `private`) 
                                             VALUES (:gender, :nam, :surn, :email, :age, :priv)");
            $statement->bindParam(':gender', $gender, PDO::PARAM_STR);
            $statement->bindParam(':nam', $name, PDO::PARAM_STR);
            $statement->bindParam(':age', $age, PDO::PARAM_INT);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':surn', $surname, PDO::PARAM_STR);
            $statement->bindParam(':priv', $private, PDO::PARAM_INT);
            self::$db->beginTransaction();
            $statement->execute();
            self::$db->commit();
            return true;
        } catch (Exception $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
            return false;
        }
    }
    public static function getUserFriendListById($id) {
        if (!self::valid("int", $id)) {
            return false;
        }   if(!(self::getUserEmailById($id))) 
        {
            return false;
        }

        try {
            $statement = self::$db->prepare("SELECT u.id, u.name 
                   FROM friends_list f
                   JOIN user u ON f.friend_id = u.id
                   WHERE f.user_id = :id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : false;
        } catch (PDOException $error) {
            echo "Task not completed: " . $error->getMessage();
            return false;
        }
    }
    public static function addToFriendListById($id,$id2) {
        if (!(self::valid("int", $id)&&self::valid("int", $id))) {
            echo "tutaj";
            return false;
        }
        if(!(self::getUserEmailById($id) && self::getUserEmailById($id2))) 
        {
            return false;
        }
        try {
            $statement = self::$db->prepare("INSERT INTO `friends_list` (`user_id`, `friend_id`) 
                                             VALUES (:id, :id2),(:id2, :id)");
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':id2', $id2, PDO::PARAM_INT);
            self::$db->beginTransaction();
            $statement->execute();
            self::$db->commit();
            return true;
        } catch (Exception $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
            return false;
        }
    }

    public static function deleteUserFindByEmail($email) {
        $userId = self::getUserIdByEmail($email);
        if ($userId === false) {
            return false;
        }

        try {
            $statement = self::$db->prepare("DELETE FROM `user` WHERE `id` = :id");
            $statement->bindParam(':id', $userId, PDO::PARAM_INT);
            self::$db->beginTransaction();
            $statement->execute();
            self::$db->commit();
            return true;
        } catch (PDOException $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
            return false;
        }
    }

    public static function editUserFindByEmail($email, $name, $surname, $gender, $age) {

        if (!(self::valid("email", $email) && self::valid("string", $name) && self::valid("string", $surname) && self::valid("string", $gender) && self::valid("int", $age))) {
            return false;
        }
        $userId = self::getUserIdByEmail($email);
        if ($userId === false) {
            return false;
        }

        try {
            $statement = self::$db->prepare("UPDATE `user` SET `name` = :name, `surname` = :surname, `gender` = :gender, `age` = :age WHERE `id` = :id");
            $statement->bindParam(':name', $name, PDO::PARAM_STR);
            $statement->bindParam(':surname', $surname, PDO::PARAM_STR);
            $statement->bindParam(':gender', $gender, PDO::PARAM_STR);
            $statement->bindParam(':age', $age, PDO::PARAM_INT);
            $statement->bindParam(':id', $userId["id"], PDO::PARAM_INT);
            self::$db->beginTransaction();
            $statement->execute();
            self::$db->commit();
            return true;
        } catch (PDOException $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
            return false;
        }
    }

    public static function getUserIdByEmail($email) {
        if (!self::valid("email", $email)) {
            return false;
        }

        try {
            $statement = self::$db->prepare("SELECT id FROM user WHERE email = :email");
            $statement->bindParam(":email", $email, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result ? $result["id"] : false;
        } catch (PDOException $error) {
            echo "Task not completed: " . $error->getMessage();
            return false;
        }
    }
    public static function getUserEmailById($id) {
        if (!self::valid("int", $id)) {
            return false;
        }

        try {
            $statement = self::$db->prepare("SELECT email FROM user WHERE id = :id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result ? $result["email"] : false;
        } catch (PDOException $error) {
            echo "Task not completed: " . $error->getMessage();
            return false;
        }
    }
    public static function getUserInfoById($id) {
        if (!self::valid("int", $id)) {
            return false;
        }

        try {
            $statement = self::$db->prepare("SELECT * FROM user WHERE id = :id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : false;
        } catch (PDOException $error) {
            echo "Task not completed: " . $error->getMessage();
            return false;
        }
    }

    public static function sendMessageFindByEmail($from, $to, $message,$type = "none") { //edited edited edit update updated type added
        if (!(self::valid("email", $from) && self::valid("email", $to))) {
            return false;
        }
        $senderId = self::getUserIdByEmail($from);
        $receiverId = self::getUserIdByEmail($to);
        if ($senderId === false || $receiverId === false) {
            return false;
        }

        $message = htmlentities($message);
        if (empty($message)) {
            return false;
        }

        try {
            $statement = self::$db->prepare("INSERT INTO `messages` (`sender_id`, `receiver_id`, `message_content`, `type`)
            SELECT u1.`id` AS `sender_id`, u2.`id` AS `receiver_id`, :mess AS `message_content`, :type AS `type`
            FROM `user` AS u1
            JOIN `user` AS u2 ON u1.`email` = :from AND u2.`email` = :to;");
            $statement->bindParam(':from', $from, PDO::PARAM_STR);
            $statement->bindParam(':to', $to, PDO::PARAM_STR);
            $statement->bindParam(':mess', $message, PDO::PARAM_STR);
            $statement->bindParam(':mess', $message, PDO::PARAM_STR);
            $statement->bindParam(':type', $type, PDO::PARAM_STR);
            self::$db->beginTransaction();
            $statement->execute();
            $messageId = self::$db->lastInsertId(); 
            self::$db->commit();
            return $messageId;
        } catch (PDOException $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
        }
    }

    public static function deleteMessageFindByEmail($from, $to, $messageId) {
        if (!(self::valid("email", $from) && self::valid("email", $to))) {
            return false;
        }
        $senderId = self::getUserIdByEmail($from);
        $receiverId = self::getUserIdByEmail($to);
        if ($senderId === false || $receiverId === false) {
            return false;
        }

        try {
            $statement = self::$db->prepare("DELETE FROM `messages` WHERE `sender_id` = :senderId AND `receiver_id` = :receiverId AND `message_id` = :messageId");
            $statement->bindParam(':senderId', $senderId['id'], PDO::PARAM_INT);
            $statement->bindParam(':receiverId', $receiverId['id'], PDO::PARAM_INT);
            $statement->bindParam(':messageId', $messageId, PDO::PARAM_INT);
            self::$db->beginTransaction();
            $statement->execute();
            self::$db->commit();
            return true;
        } catch (PDOException $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
            return false;
        }
    }
    public static function deleteMessageById($messageId) {
        if (!is_numeric($messageId)) {
            echo "jestem";
            return false;
        }
    
        try {
            $statement = self::$db->prepare("DELETE FROM `messages` WHERE `message_id` = :messageId");
            $statement->bindParam(':messageId', $messageId, PDO::PARAM_INT);
            self::$db->beginTransaction();
            $statement->execute();
            self::$db->commit();
            return true;
        } catch (PDOException $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
            return false;
        }
    }
    
    public static function findUsersByName($name) {
        if (!self::valid("string", $name)) {
            return false;
        }
        $name = "%".$name."%";
        try {
            $statement = self::$db->prepare("SELECT id, name,surname FROM user WHERE name like :name");
            $statement->bindParam(":name", $name, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : false;
        } catch (PDOException $error) {
            echo "Task not completed: " . $error->getMessage();
            return false;
        }
    }
    public static function getUsers($sortBy,$direction,$limit){
        if(!in_array($sortBy,array("id","name","surname","age"))){
            return false;
        }
        if(!($direction == "asc" || $direction == "desc")){
            return false;
        }
        if(!(self::valid("int",$limit))){
            return false;
        }
        try{
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = self::$db->prepare("Select * From user  ORDER BY $sortBy $direction LIMIT :limit");
        $query->bindParam("limit", $limit, PDO::PARAM_INT);
        $query->execute();
        $return = $query->fetchAll(PDO::FETCH_ASSOC);
        return $return;
        }catch (Exception $error) {
            echo "Query not completed: " . $error->getMessage();
            return false;
        }
    }
    public static function findUsersByEmail($email) {
        if (!self::valid("string", $email)) {
            return false;
        }
        $email = $email."%";
        try {
            $statement = self::$db->prepare("SELECT id, name,surname FROM user WHERE email like :email");
            $statement->bindParam(":email", $email, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : false;
        } catch (PDOException $error) {
            echo "Task not completed: " . $error->getMessage();
            return false;
        }
    }
    public static function getUserFriendsById($id){
        if (!self::valid("int", $id)) {
            return false;
        }

        try {
            $statement = self::$db->prepare("SELECT * FROM friends_list WHERE user_id = :id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : false;
        } catch (PDOException $error) {
            echo "Task not completed: " . $error->getMessage();
            return false;
        }
    }
    public static function removeNotificationByNotificationId($id){

    }
    public static function getAllNotificationsById($id,$limit = 20, $offset = 0){
        if(!self::valid("int",$id)){
            return false;
        }
        if (($user = self::getUserEmailById($id)) === false) {
            return false;
        }
        $statement = self::$db->prepare("SELECT sender_id,message_id, message_content, read_at FROM messages WHERE receiver_id=:id AND type = 'notification' ORDER BY sent_at DESC LIMIT $limit  OFFSET $offset");

        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        try{
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->beginTransaction();
            $statement->execute();
            $messages = $statement->fetchAll(PDO::FETCH_ASSOC);
          self::$db->commit();
        }catch (Exception $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
        }
        if(isset($messages) && $messages !== false && $messages !== null){
        return $messages;
        }else{return false;}
    }  
    public static function getAllPostsById($id,$limit = 20, $offset = 0){
        if(!self::valid("int",$id)){
            return false;
        }
        if (($user = self::getUserEmailById($id)) === false) {
            return false;
        }
        $statement = self::$db->prepare("SELECT message_id, message_content, read_at ,type FROM messages WHERE sender_id=:id AND type = 'post' ORDER BY sent_at DESC LIMIT $limit  OFFSET $offset");

        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        try{
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->beginTransaction();
            $statement->execute();
            $messages = $statement->fetchAll(PDO::FETCH_ASSOC);
          self::$db->commit();
        }catch (Exception $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
        }
        if(isset($messages) && $messages !== false && $messages !== null){
        return $messages;
        }else{return false;}
    }      
    public static function getAllPostsFromFriends($ids,$limit = 20, $offset = 0) {
        if (!is_array($ids) || empty($ids)) {
            return false;
        }
        foreach ($ids as $id) {
            if (!self::valid("int", $id)) {
                return false;
            }
        }
        $forquery = implode(',', array_fill(0, count($ids), '?'));
        $select = "SELECT message_id, message_content, read_at FROM messages WHERE sender_id IN ($forquery) AND type = 'post' ORDER BY sent_at DESC LIMIT $limit  OFFSET $offset";
        $statement = self::$db->prepare($select);
        try {
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->beginTransaction();
            $statement->execute($ids);
            $messages = $statement->fetchAll(PDO::FETCH_ASSOC);
            self::$db->commit();
        } catch (Exception $error) {
            self::$db->rollBack();
            echo "Transaction not completed: " . $error->getMessage();
            return false;
        }
        if (!empty($messages)) {
            return $messages;
        } else {
            return false;
        }
    }
    
}


//$email = "d";
//$name = "John";
//$surname = "Doe";
//$gender = "Male";
//$age = 30;
//DbClass::init();
//print_r(DbClass::getUserFriendListById(30));
//print_r(DbClass::getAllPostsFromFriends(array(31,33,30)));
//print_r(DbClass::getAllNotificationsById(30));
//$id = DbClass::getUserIdByEmail($email);
//print_r(DbClass::getAllPostsById(33));
//(DbClass::sendMessageFindByEmail($id,$id,"siema"));
//print_r(DbClass::getUserInfoById($id));

?>