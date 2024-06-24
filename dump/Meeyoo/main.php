<?php
    session_start();
    if(isset($_COOKIE["loggedIn"])){
        $_SESSION["loggedIn"] = true;
        $_SESSION["userId"] = $_COOKIE["loggedIn"];
    }
    
    chdir("src/");
    print_r($_SESSION);
    print_r($_POST);
    include("header.php");
    if(isset($_POST["type"])){
        switch($_POST["type"]){
            case "post":
            require("sendPost.php");
            break;
            
            case "message":
                $_SESSION["message"] = $_POST["message"];
                $_SESSION["goTo"] = 'message';
                break;
            case "login":
                require("validateLogin.php");
                exit();
                case "register":
                require("validateRegister.php");
                exit();
            case "search":
                require("search.php");
                exit();
        }
    }
    
    if( isset($_GET["goTo"])){ 
        if(!($_SESSION["goTo"] == "message")){

       
        foreach($_SESSION as $key => $value){
            if( ($key == "loggedIn") || ($key == "userId")  || preg_match("/error/i",$key)|| ($key== "profileId")){
                continue;
            }else{
                echo "deletion of $key" ;
                unset($_SESSION[$key]);
            }
        }
    }
        $_SESSION["goTo"] = $_GET["goTo"];
        $goTo = $_SESSION["goTo"];
        if($_SESSION["goTo"] == "profile"){
            if(isset($_GET["profileId"])){
            $_SESSION["profileId"]= $_GET["profileId"];
            }
        }
        else if($_SESSION["goTo"] == "addFriend"){
            $_SESSION["friendId"] = $_GET["friendId"];
          
        }
        else if($_SESSION["goTo"] == "message"){
            $_SESSION["messageTo"] = $_GET["messageTo"];
        }
        else if($_SESSION["goTo"] == "answer"){
            $_SESSION["decision"] = $_GET["decision"];
            $_SESSION["senderId"] = $_GET["Id"];
            if(isset($_GET["messageId"])){
                $_SESSION["messageId"] = $_GET["messageId"];
            }
        }

    }else if(isset($_SESSION["goTo"])){
        $goTo = $_SESSION["goTo"];
    }else{
        $goTo = "home";
    }
    switch ($goTo){
        case "post":
            require("createPost.php");
            break;
        case "answer":
            require("addFriend.php");
            break;

        case "notifications":
            require("notifications.php");
            break;
        case "message":
            require("message.php");
            break;
        case "listOfFriends":
            require("listOfFriends.php");
            break;
        case "addFriend":
            require("addFriend.php");
            break;
        case "login":
            require("login.php");
            break;
        case "register":
            require("register.php");
            break;
            case "profile":
                require("profile.php");
                break;
                case "home":
                    require("home.php");
                    break;
                    case "logout":
                        session_unset();
                            if(isset($_COOKIE["loggedIn"])){
                                 setcookie("loggedIn","delete", time()-3600,"/");
                                }                   
                                header("Location: main.php");
                                exit();
                    default: "home.php";
    }
    unset($_SESSION["message"]);
    include("footer.php");
?>