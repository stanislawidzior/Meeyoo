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
        } catch (Exception $error) {
            die("Connection failed: " . $error->getMessage());
        }
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
            $statement->bindParam(':id', $userId["id"], PDO::PARAM_INT);
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

    public static function deleteUserFindByEmail($email) {
        $userId = self::getUserIdByEmail($email);
        if ($userId === false) {
            return false;
        }

        try {
            $statement = self::$db->prepare("DELETE FROM `user` WHERE `id` = :id");
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
            return $result ? $result : false;
        } catch (PDOException $error) {
            echo "Task not completed: " . $error->getMessage();
            return false;
        }
    }

    public static function sendMessageFindByEmail($from, $to, $message) {
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
            $statement = self::$db->prepare("INSERT INTO `messages` (`sender_id`, `receiver_id`, `message_content`)
                                             SELECT u1.`id` AS `sender_id`, u2.`id` AS `receiver_id`, :mess AS `message_content`
                                             FROM `user` AS u1
                                             JOIN `user` AS u2 ON u1.`email` = :from AND u2.`email` = :to");
            $statement->bindParam(':from', $from, PDO::PARAM_STR);
            $statement->bindParam(':to', $to, PDO::PARAM_STR);
            $statement->bindParam(':mess', $message, PDO::PARAM_STR);
            self::$db->beginTransaction();
            $statement->execute();
            self::$db->commit();
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
}

// Initialize the database connection
DbClass::init();
$email = "john@doe.com";
$name = "John";
$surname = "Doe";
$gender = "Male";
$age = 30;
if (DbClass::addUser($email, $name, $surname, $gender, $age)) {
    echo "User added successfully.";
} else {
    echo "Failed to add user.";
}
?>