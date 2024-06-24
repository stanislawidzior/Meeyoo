<div class="content" id=notifications>
<?php
include("classes/NotificationsClass.php");
if(isset($_SESSION["decisionError"])){
    if($_SESSION["decisionError"] == "none"){
        echo "<p class='success'>succesfuly added a friend </p>";
    }else{
    echo "<p class='errorIn'>".$_SESSION["decisionError"]."</p>";
    }
    unset($_SESSION["decisionError"]);
}
if(isset($_SESSION["loggedIn"])){
    
$notifications = new Notification($_SESSION["userId"],$_SESSION["userId"]);
$notifications->checkWithDb();
$toPrint = $notifications->getNotifications();
if(empty($toPrint)){
    echo '<p "errorIn"> Empty</p>';
}else{
    
try{DbClass::init();
    echo'<ul class="notificationsList">';
    foreach($toPrint as $note){
       $id =  DbClass::getUserInfoById($note["sender_id"])["id"];
       $messageId = $note["message_id"];
        echo'<li><ul class="row"><li>Notification from:<a href="main.php?goTo=profile&profileId='.$note["sender_id"].'">'. Dbclass::getUserInfoById($note["sender_id"])['name'].'</a></li>';
        if($note["message_content"] == "Friend Request"){
            echo '<li> sends you a friend request</li>';
            echo '<li><a href="main.php?goTo=answer&decision=add&Id='.$id.'&messageId='.$messageId.'">Accept</a></li>;
                <li><a href="main.php?goTo=answer&decision=decline&Id='.$messageId.'">Decline</a></li>' ;
        }
        else{
            echo '<li>'.$note["message_content"].'</li>';
        }
        echo '</ul></li>';
    }
    echo '</ul>';
}catch(Exception $e){
echo "<p class='erorIn'>And Error occured";
}
}

}   else{
    echo '<p id = "notLoggedInError">U must login first to see this content</p>';
}
?>
</div>