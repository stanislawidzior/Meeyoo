<?php
    session_start();
    $goTo = isset($_GET["goTo"]) ? $_GET["goTo"] : "home.php";
    switch ($goTo){
        case "login.php":
            require("Location: login");
            exit();
        case "registe.php":
            require("register.php");
            case "profile":
                require("profile.php?$profileHash");
                exit();
                case "home.php":
                    require("home.php");
                    exit();
                    default: "home.php";
    }
?>