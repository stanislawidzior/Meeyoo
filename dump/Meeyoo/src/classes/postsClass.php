<?php
include("MessagesAbstract.php");
class Post extends MessageAbstract{
    public function __construct($owner){
        parent::__construct($owner, 0);
    }
    public function getPosts(){
    


    }
    public function checkWithDbMany(){
       try{ DbClass::init();
        $friendsArray = DbClass::getUserFriendListById($this->ownerId);
       foreach($friendsArray as$key => $friend){
        $this->setReceiver($friend["id"] );
        if(!$this->checkWithDb()){
            echo "here";
             return false;
         }
    }
}catch(Exception $e){
    return false;
}
    return true;
}
    public  function getSpecificMessages(){
        try{
        DbClass::init();
        $friendsArray = DbClass::getUserFriendListById($this->ownerId);
        $onlyindexes =[];
         foreach($friendsArray as $friend){
            if(isset($friend["id"])){
                array_push($onlyindexes,$friend["id"]);
            }
         }
        print_r($friendsArray);
       $arr = DbClass::getAllPostsFromFriends($onlyindexes,20,$this->offset);
       }catch(Exception $e){
        echo "here";
        return false;
       }   
       return $arr;

    }
    public  function getUsersPosts(){
        try{
        DbClass::init();
       $arr = DbClass::getAllPostsById($this->ownerId,20,$this->offset);
       }catch(Exception $e){
        echo "here";
        return false;
       } 
       foreach($arr as $post){
        $arr = explode(":::", $post["message_content"]);
        echo '<div class="postBody">
<div class="postHead">'.$arr[0].'</div>';
$directoryPath ="img\\".$_SESSION["profileId"]."\\posts\\".$post["message_id"];
$directoryPath = str_replace('\\', '/', $directoryPath);
echo $directoryPath;
if (is_dir($directoryPath)){
 echo '<div class="postImage"><img src="src/'.$directoryPath."/photo.png".'" alt="image not found"></div>';
}
echo '<div class="postContent">'.$arr[1].'</div>
</div>';
       }

    }


    
    public  function deleteMessages(){

    }
    public function getId(){

    }
    public  function sendMessage($message){
     
        try{
            DbClass::init();
        return DbClass::sendMessageFindByEmail(DbClass::getUserEmailById($this->ownerId),DbClass::getUserEmailById($this->ownerId),$message,"post");
        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }
}
//$post = new Post(33);
//echo $post->sendMessage("seimaneczko");

//print_r( $post->getUsersPosts())

?>