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
        $_SESSION["goTo"] = $_GET["goTo"];
        $goTo = $_SESSION["goTo"];
        if($_SESSION["goTo"] == "profile"){
            $_SESSION["profileId"]= $_GET["profileId"];
        }
        if($_SESSION["goTo"] == "addFriend"){
            $_SESSION["profileId"]= $_GET["profileId"];
        }
    }else if(isset($_SESSION["goTo"])){
        $goTo = $_SESSION["goTo"];
    }else{
        $goTo = "home";
    }

    switch ($goTo){
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