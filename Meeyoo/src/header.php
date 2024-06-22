<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
     .error {
            background-color: lightcoral;
        }
        #headerProfilePic{
            width: 200px; 
              height: 200px; 
              overflow: hidden; 
              border-radius: 50%; 
        }
        #headerProfilePic img {
            display: block; 
            width: 100%; 
             height: auto;
        }
       
    </style>
</head>
<body>
    <nav>
        <div class="logo"><a href="main.php?goTo=home">Meeyoo</a></div>
        <div class="search">
        <form action="main.php" method="POST">
            <input type="text" hidden name="type" id="type" value="search">
            <select id="searchBy" name="searchBy">
                <option value="name">Name</option>
                <option value="email">Email</option>
            </select>
            <input type="text" name="searchQuery" id="searchQuery">
            <input type="submit" id="searchButton">
            <ul id="searchResultsList">
                <?php
                if(isset($_SESSION["results"])){
                foreach($_SESSION["results"] as $result){
                   
                    echo '<li class="searchResult">'. $result["name"]." | " . $result["surname"].'<a href="main.php?goTo=profile&profileId='.$result["id"].'"> Go to profile </a></li>';
           
                }
                unset( $_SESSION['results']);
            }
                ?>
            </ul>
        </form>
    </div>
        <div class="profilePicture"><?php 
        
        
        if(isset($_SESSION["loggedIn"]) || isset($_COOKIE["loggedIn"])){
        echo'<div id= "headerProfilePic"><a href="main.php?goTo=profile&profileId='.($_SESSION["userId"]).'">';
        echo '<img src="images/'.$_SESSION["userId"].'/profile_picture.jpg" alt="image not found"></a></div>';
        }else{
            echo "<a href =\"main.php?goTo=login\">Log in</a>";
        }
        ?></div>
    </nav>
