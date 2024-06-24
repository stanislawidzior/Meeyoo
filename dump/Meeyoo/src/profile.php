<div class="content" id="profile">
<div class="top">
    <?php 
    if(isset($_SESSION["inviteError"])){
        if($_SESSION["inviteError"] == "none"){
            echo "<p id ='inviteSuccess'> Invite Sent</p>";
        }else{
            echo "<p id ='inviteError'>". $_SESSION["inviteError"] ."</p>";
        }
        unset($_SESSION["inviteError"]);
    }
    ?>
<div class="profilePicture">
<?php echo '<img src="images/'.$_SESSION["profileId"].'/profile_picture.jpg" alt="image not found">' ?>
</div>
<div class="info">
    <ul>
    
    <?php 
    if(isset($_SESSION['loggedIn']) && ($_SESSION["userId"] != $_SESSION["profileId"])){
        echo '<ul><li><a href="main.php?goTo=addFriend&friendId='.$_SESSION["profileId"].'">add friend</a></li>';
        echo '<a href="user info">more info</a></li></ul>';
    }else if(isset($_SESSION['loggedIn']) &&( ($_SESSION['userId'] == $_SESSION['profileId']))){
        echo '<ul><li><a href="main.php?goTo=listOfFriends">list of friends</a></li>'; //Dodane dodane tez do main case
        echo '<li><a href="user info">edit profile</a></li></ul>';
    }
    ?>
    
    </ul>
</div>
<div class="description"> 
    <?php 
    
    include("classes/UserClass.php");
   
    $user = new User("jakis","uzytkownik@uzytkonik");
    try{
        $user->getFromDbById($_SESSION["profileId"]);
        $description = $user->getDescription();
    }catch(Exception $e){
        echo "Error:". $e->getMessage();
    }
    echo $description;
    ?>
</div>
</div>
<div class="posts"> 
<?php
include("classes/postsClass.php");
$post = new Post($_SESSION["profileId"]);
$post->getUsersPosts();

?>    

</div>
</div>
<?php

 