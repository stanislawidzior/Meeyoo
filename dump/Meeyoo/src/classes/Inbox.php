<?php
include("dbClassStatic.php");
 class Inbox {
    private $userId;
    public function getUserFriendsArray($id){
       DbClass::init();
        $friends = DbClass::getUserFriendsById($id);
        return $friends;
    }
    public static function getUserMessagesFrom($id){
        DbClass::init();
        $email = DbClass::getUserEmailById($id);
        $messages = DbClass::getUserMessagesFindByEmail($id); // wiadomosci ktore przyszly do uzytkownika
        $messegessend = DbClass::getUserSentMessagesFindByEmail($id);
        print_r(array_combine($messegessend, $messages));
    }

}
DbClass::init();
$email = "stas@sta.com";
$name = "stas";
$email2 = "franek@franek.com";
$name2 = "franek";

echo DbClass::addUser($email,$name);
echo DbClass::addUser($email2,$name2);
//$id = DbClass::getUserIdByEmail($email);
//$id2 = DbClass::getUserIdByEmail($email2);

?>