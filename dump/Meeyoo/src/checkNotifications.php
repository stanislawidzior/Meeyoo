<?php
require("classes/NotificationsClass.php");
$notification = new Notification($_SESSION["userId"],"*");
$_SESSION["newNotification"]  = $notification->checkIfNewNotifications($_SESSION["userId"]);
?>