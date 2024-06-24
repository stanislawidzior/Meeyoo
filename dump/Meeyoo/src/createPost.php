<div class="content" id="createPost" >
    <?php 
    if(isset($_SESSION["postError"])){
        if($_SESSION["postError"]!= 'none'){
            echo '<p "errorIn">'.$_SESSION["postError"]."</p>";
        }else{
            echo '<p "success">post created</p>';
        }
        unset($_SESSION['postError']);
    }
    ?>
    <h1>Create you post</h1>
<form action="main.php" method="post" enctype="multipart/form-data">
    <input type="text" name="type" id="type" value="post" hidden>
    <input type="text" name="title" id="title" placeholder="content" required>
    <input type="text" name="content" id="content" placeholder="content" required>
    <label for="photo">Choose a photo to upload:</label>
        <input type="file" name="photo" id="photo" accept="image/*">
    <input type="submit" name="submit" value="Post">
</form>
</div>