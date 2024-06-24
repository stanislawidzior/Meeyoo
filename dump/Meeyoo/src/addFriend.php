<?php
include("classes/NotificationsClass.php");
if(isset($_SESSION["loggedIn"])){
if(isset($_SESSION["decision"])){
    print_r($_SESSION);
    if($_SESSION["decision"] == "add"){
        try{
            print_r($_SESSION);
            DbClass::init();
        DbClass::addToFriendListById($_SESSION["userId"],$_SESSION["senderId"]);
        DbClass::deleteMessageById($_SESSION["messageId"]);
        $_SESSION["decisionError"] = "none";
        }catch(Exception $e){
            $_SESSION["decisionError"] = "failed";
    }
}else{
    
    try{
        DbClass::init();
        DbClass::deleteMessageById($_SESSION["senderId"]);
    }catch(Exception $e){
        echo "jesetem";
        $_SESSION["decisionError"] = "failed";
    }
}
  unset( $_SESSION["decision"] );
    header("Location: main.php?goTo=notifications");
    exit();
}
else{
try{
$notification = new Notification($_SESSION["userId"],$_SESSION["friendId"]);
$notification->checkWithDb();
$notification->sendMessage("Friend invite");
$_SESSION["inviteError"] = 'none';
}catch(Exception $e){
    $_SESSION["inviteError"] = $e->getMessage();
}
$profileId = $_SESSION['friendId'];
}
header("Location: main.php?goTo=profile&profileId=$profileId");
}else{
    echo '<p class="errorIn">You must be logged in first</p>';
}
