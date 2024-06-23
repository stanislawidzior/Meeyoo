<div class="content" id="listOfFriends">
<?php
if (!isset($_SESSION["loggedIn"])) {
    echo "<p class='errorInList'>You need to login to see this content</p>";//NEW
} else {
    require "classes/dbClassStatic.php";
    $listOfFriends = DbClass::getUserFriendListById($_SESSION["userId"]);
    if(empty($listOfFriends)) {
    echo "<p class='errorInList'>You currently don't have any friends add friends to see this content</p>";
    }else{
    echo '<table>';
    foreach ($listOfFriends as $friend) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($friend['name']) . '</td>';
        echo '<td><a href="main.php?goTo=profile&profileId=' . htmlspecialchars($friend["id"]) . '">';
        echo '<img src="images/' . htmlspecialchars($friend["id"]) . '/profile_picture.jpg" alt="Profile picture of ' . htmlspecialchars($friend['name']) . '"></a></td>';
        echo '<td><a id="messageButton" href="main.php?goTo=message&messageTo=' . htmlspecialchars($friend['id']) . '">message</a></td>';
        echo '</tr>';
    }
    echo '</table>';
}
}
?>
</div>
