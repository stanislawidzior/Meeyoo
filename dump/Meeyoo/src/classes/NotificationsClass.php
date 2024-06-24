<?php
include("MessagesAbstract.php");
class Notification extends MessageAbstract{
    public  function getSpecificMessages(){
        if(($arr = $this->getMessages())===false){
            return false;
          }
          foreach ($arr as $key => $message) {
            if (isset($message['type']) && ($message['type']!== "notification")) {
                unset($arr[$key]);
            }else if(isset($message['receiver_id']) && $message['receiver_id']!= $this->receiverId){
              unset($arr[$key]);
            }
    }
    return $arr;
}
public static function checkIfNewNotifications($id){
   try{
    DbClass::init();
    if(!DbClass::getUserEmailById($id)){
        return false;
    }
    $arr = DbClass::getAllNotificationsById($id,20,100);
    //print_r($arr);
    foreach($arr as $notification){
        if(empty($notification['read_at'])){
            return true;
        }
    }
    return false;
   }catch (Exception $e){
    return false;
   }


}
        public function getNotifications(){
        try{
            DbClass::init();
            if(!DbClass::getUserEmailById($this->ownerId)){
                return false;
            }
       $arr = DbClass::getAllNotificationsById($this->receiverId,20,$this->offset);
        return $arr;
        }catch(Exception $e){
        return false;
        }

    }
    public  function deleteMessages(){// later
        if(($arr = $this->getMessages())===false){
            return false;
          }
          
    }
    public function alreadySent($message){
        if(($arr = $this->getMessages())===false){
            return false;
          }
        foreach ($arr as $key => $message) {
            if (isset($message['type']) && ($message['type'] == "notification")) {
            } if(isset($message['receiver_id']) && $message['receiver_id'] == $this->receiverId){
                if(isset($message['message_content']) && ($message['message_content'] == "Friend Request")){
                    return true;
                }
            }
    }
    return false;
}
    public  function sendMessage($message){
        if($this->alreadySent($message)){
            throw new Exception("Invite already sent");
        }
        $message = "Friend Request";
  if(!$this->inSync){
    throw new Exception("need to be in sync with the dataBase first");
  }
  if(!DbClass::init()){
    throw new Exception("couldn't connect to the dataBase");
  }
  if(!(($user = DbClass::getUserEmailById($this->ownerId)) && ($receiver = DbClass::getUserEmailById($this->receiverId)))){
  throw new Exception("couldn't find email");
  }
  if(!DbClass::sendMessageFindByEmail($user,$receiver,$message,"notification")){
    throw new Exception("failed to send notification");
  }
  return true;
    }
}


//echo Notification::checkIfNewNotifications(33)?"true":"false";
?>