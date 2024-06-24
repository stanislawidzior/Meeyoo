<div class="content" id="messageBox">
    <?php
    if(!isset($_SESSION["loggedIn"])){
        echo "<p class='errorInList'> U have to be logged in first</p>";
        
    }else{
        if(isset($_SESSION["messageTo"])){
    require("classes/messageClass.php");
    $message = new Message($_SESSION["userId"], $_SESSION["messageTo"]);//sending messages, receiving messages,get more
    if($message->checkWithDb()){
        if(isset($_SESSION["message"])){
            $message->sendMessage($_SESSION["message"]);
            unset($_SESSION["message"]);
        }
        if(isset($_SESSION["messageOffset"])){
        $message->setOffset($_SESSION["messageOffset"]);
        unset($_SESSION["message"]);
        exit();
        }
        $message->getMessagesList();
        if(empty($message)){
        
        }
        $_SESSION["messageOffset"] = $message->getOffset();
       
        echo '<form action="main.php" method="POST">
        <input type="text" hidden name="type" value="message">
    <input type="text" name="message" id ="message" required placeholder="message">
    <input type="submit" id="sendMessageButton">
</form>';

    }else{
        echo '<p class = "errorInList"> An error occured </p>';
    }
    
        }
}
    ?>

</div>