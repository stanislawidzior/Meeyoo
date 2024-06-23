<div class="content" id="login">

    <?php 
    
    if(isset($_SESSION["loggedIn"])){
        echo '
    <div><h1>Already logged in</h1>
   <p>  <a href="main.php?goTo=logout">Log out</a></p>
</div>
';
       
    }else {
    echo '<h1>Log in</h1>';
    if(isset($_SESSION["loginError"])){
        if($_SESSION["loginError"] == "none"){

        }else{
        echo '<p id="loginError">'. $_SESSION["loginError"].'</p>';
        }
        unset($_SESSION["loginError"]);

    }
    echo '<form action="main.php" method="POST">
        <input type="text" hidden id="type" name="type" value="login">
        <label for="email">Your E-mail</label>
        <input type="text" placeholder="example@email.com" id="email" name="email">
        <label for="password">Your password</label>
        <input type="password" placeholder="password" id="password" name="password">
        <input type="submit" value="Log in" id="LoginButton">
        <label for="remember">Remember me
         <input type="checkbox" name="remember" id="remember"></label>
        </form>
    <p>Not a member yet? <a href="main.php?goTo=register">Sign up</a></p>';
    }
    ?>
    
</div>
   
