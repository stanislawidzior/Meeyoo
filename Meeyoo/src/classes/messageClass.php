<?php
include("MessagesAbstract.php");
class Message extends MessageAbstract{
  public function __construct($userid,$receiverId){
  parent::__construct($userid,$receiverId);  
}
public function getMessagesList(){
  if(($arr = $this->getSpecificMessages()) == false){
    return '<p class="errorInList">Empty chat</p>';
  }
  echo "<ul>";
  foreach($arr as $message){
    if ($message["senderId"] == $this->ownerId) {
      echo '<li class="userMessage">' . htmlspecialchars($message["message_content"]) . '</li>';
    } elseif ($message["receiverId"] == $this->ownerId) {
      echo '<li class="friendMessage">' . htmlspecialchars($message["message_content"]) . '</li>';
    }
  
    echo '<li class="sentAt">'.$message["sentAt"].'</li>';
  }
  echo "</ul>";
}
public function deleteMessages(){
  if(($arr = $this->getSpecificMessages()) == false){
    throw new Exception("message deletion failed");
  }//to implement maybe
}
public function getSpecificMessages(){
if(($arr = $this->getMessages())===false){
  return false;
}
foreach ($arr as $key => $message) {
  if (isset($message['type']) && ($message['type']!== "none") && $message['type'] === 'invite') {
      unset($arr[$key]);
  }
}
return $arr;
}
public function sendMessage($message){
  $message = htmlentities($message);
  if(!$this->inSync){
    throw new Exception("need to be in sync with the dataBase first");
  }
  if(!DbClass::init()){
    throw new Exception("couldn't connect to the dataBase");
  }
  if(!(($user = DbClass::getUserEmailById($this->ownerId)) && ($receiver = DbClass::getUserEmailById($this->receiverId)))){
  throw new Exception("couldn't find email");
  }
  if(!DbClass::sendMessageFindByEmail($user,$receiver,$message)){
    throw new Exception("failed to send message");
  }
  return true;
}

}
?>