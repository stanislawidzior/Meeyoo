<?php 
chdir("classes/");
include("UserClass.php");
$user = new User("",$_POST["email"]);
if(!$user->validateInput($_POST)){
    $_SESSION["loginError"] = "Invalid input";
    header("Location: main.php?goTo=login");
    chdir("..");
    exit();
}
if(!$user->checkIfInDb()){
    $_SESSION["loginError"] = "User not found";
    chdir("..");
    header("Location: main.php?goTo=login");
    exit();
}else{
    try{
    $user->getFromDbByEmail($_POST["email"]);
    if($user->verifyPassword($_POST["password"])){
        $_SESSION["loggedIn"] =true;
        if($_POST["remember"] === true){
            setcookie("loggedIn",true,time()+  + (7 * 24 * 60 * 60));
        }
        $_SESSION["userId"] = $user->getId();
    $_SESSION["loginError"] = "none";
    chdir("..");
        header("Location: main.php?goTo=home");
        exit();
    }else{
        $_SESSION["loginError"] = "Incorrect password";
        chdir("..");
        header("Location: main.php?goTo=login");
        exit();
    }
}catch(Exception $e){
    $_SESSION["loginError"] = "Error occured: ".$e->getMessage();
}
}


?>