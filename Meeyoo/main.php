<?php
    session_start();
    if(isset($_COOKIE["loggedIn"])){
        $_SESSION["loggedIn"] = true;
        $_SESSION["userId"] = $_COOKIE["userId"];
    }
    chdir("src/");
    include("header.php");
    if(isset($_POST["type"])){
        switch($_POST["type"]){
            case "message":
                $_SESSION["message"] = $_POST["message"];
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
    
    
    if( isset($_GET["goTo"])){ // zastanow sie czy nie powinienes unset robic roznych rzeczy ktore sa plikach ze switcha
        if($_GET["goTo"]!=="message" && $_SESSION["goTo"] =="message"){
            unset($_SESSION["messageTo"]);
            unset( $_SESSION["messageOffset"]);
        }
        $_SESSION["goTo"] = $_GET["goTo"];
        $goTo = $_SESSION["goTo"];
        if($_SESSION["goTo"] == "profile"){
            $_SESSION["profileId"]= $_GET["profileId"];
        }
        else if($_SESSION["goTo"] == "addFriend"){
            $_SESSION["profileId"] = $_GET["profileId"];
        }
        else if($_SESSION["goTo"] == "message"){
            $_SESSION["messageTo"] = $_GET["messageTo"];
        }
    }else if(isset($_SESSION["goTo"])){
        $goTo = $_SESSION["goTo"];
    }else{
        $goTo = "home";
    }

    switch ($goTo){
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
    include("footer.php");
?>