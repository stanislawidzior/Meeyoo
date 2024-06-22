<?php
$userSending= new User();
$userReceiving = new User();
try{
$userSending->getFromDbById($_SESSION["userId"]);
$userReceiving->getFromDbById($_SESSION["profileId"]);

$receiverEmail = $userReceiving->getEmail();
$user->sendFriendInvite($receiverEmail);
}catch(Exception $e){
    $_SESSION["error"] = $e->getMessage();
}