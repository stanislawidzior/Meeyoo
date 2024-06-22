<div class="top">
<div class="profilePicture">
<?php echo '<img src="images/'.$_SESSION["profileId"].'/profile_picture.jpg" alt="image not found">' ?>
</div>
<div class="info">
    <ul>
    <a href="user info">more info</a>
    <?php 
    if(isset($_SESSION['loggedIn']) &&($_SESSION["userId"] !== $_SESSION["profileId"])){
        echo '<a href="main.php?goTo=addFriend&friendId='.$_SESSION["profileId"].'">add friend</a>';
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
<div class="posts"> POSTS</div>

<?php
 