<?php
include("classes/UserClass.php");
$arr = $_POST;

$user = new User($arr["name"],$arr["email"]);
if(!$user->validateInput($arr)){
    echo "nie dziala";
    $_SESSION["registerError"] = "Invalid Input tutaj";
    header("Location: main.php?goTo=register");
   
 exit();
}

if(!(isset($arr["password"]) && !empty($arr["password"]) && isset($arr["passwordRepeat"]) && !empty($arr["passwordRepeat"]))){
    $_SESSION["registerError"] = "Repeat your password";
    header("Location: main.php?goTo=register");
    exit();
}
if($user->checkIfInDb()){
    $_SESSION["registerError"] = "e-mail already in use";
    header("Location: main.php");
    exit();
}else{
    $user->setAge($arr["age"]);
    $user->setGender($arr["gender"]);
    $user->setSurname($arr["surname"]);
    try{
    $user->addUserToDbAndSetId();
    $directory = "img/".$user->getId()."/profile_picture";
    mkdir($directory, 0755, true);
    }catch(Exception $e){
    $_SESSION["registerError"] = "Failed to add user to data base";
    header("Location: main.php?goTo=register");
    exit();
    }
    try{
    $user->setPassword($arr["password"]);
    }catch(Exception $e){
    $_SESSION["registerError"] = $e->getMessage();
    header("Location: main.php?goTo=register");
    exit();
    }
    $_SESSION["registerError"] = "none";
    header("Location: main.php?goTo=register");
    exit();
}


