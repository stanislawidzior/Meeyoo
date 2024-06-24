<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="src/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
    display:flex;
    flex-direction:column;
    align-items: center;
    height:100vh;
    width:100%;
    margin:0;
    background-color:white;
}
nav{
    display:flex;
    flex-direction:row;
    justify-content: space-between;
    border:1px solid black;
    width:100%;
    background-color:#34004d ;
    color:#f7e6ff;
    text-align: center;
}
footer{
    margin-top: auto;
            width: 100%;
            border-top: 1px solid black; 
            padding:2rem;
            background-color: #34004d;
            text-align: center;
            color:#f7e6ff;
}
.logo{
    display:flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}
.logo a{
    font-family: 'Courier New', Courier, monospace;
    letter-spacing: 5px;
    text-decoration: none;
    font-weight: bold;
    background-color: #11001a;
    color:#f7e6ff;
    padding:2rem;
    border-radius: -10px 10px;
    border-top-right-radius: 50%;
    border-bottom-left-radius: 50%;

}
.content{
    padding:10%;
    background-color:#210033;
    color:#f7e6ff;
}
.friendMessage{
    background-color: aqua;

}
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
.postImage{
    width: 200px; 
      height: 30%; 
      overflow: hidden; 
}
.postImage img{
    display: block; 
    width: 100%; 
     height: auto;
}
.posts{
    display:flex;
    flex-direction: column;
    gap:20px;
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
        
        if(isset($_SESSION['newNotification']) && $_SESSION["newNotification"] == true){
            echo '<style> #notificationsButton { color:white;}</style>';
        }
        if(isset($_SESSION["loggedIn"]) || isset($_COOKIE["loggedIn"])){
        echo'<div id= "headerProfilePic"><a href="main.php?goTo=profile&profileId='.($_SESSION["userId"]).'">';
        echo '<img src="images/'.$_SESSION["userId"].'/profile_picture.jpg" alt="image not found"></a>
        <a id="notificationsButton" href="main.php?goTo=notifications">notification</a>
        </div>';
        }else{
            echo "<a href =\"main.php?goTo=login\">Log in</a>";
        }
        ?></div>
    </nav>
