<div class="content" id="home">
<?php 
if(isset($_SESSION["loggedIn"])){
   echo ' <div class="addPost">
    <a href="main.php?goTo=post">
        here add pluss icon
    </a>
 </div>';
}else{
    header("Location: main.php?goTo=login");
}
?>

</div>