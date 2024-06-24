<?php
include("classes/postsClass.php");
if (! isset($_FILES["photo"])) {
    $_SESSION["postError"] = "file is not a photo";
    header('Location: main.php?goTo=post');
    exit();
}
$post = new Post($_SESSION["userId"]);
$title = htmlspecialchars($_POST["title"]);
$content = htmlspecialchars($_POST["content"]);
if (!($check = getimagesize($_FILES["photo"]["tmp_name"])) ) {
    $_SESSION["postError"] = "file is not a photo";
    header('Location: main.php?goTo=post');
    exit();
} 
if ($_FILES["photo"]["size"] > (5000000 * 3)) { 
    $_SESSION["postError"] = "Sorry, your file is too large. 15MB limit";
    header('Location: main.php?goTo=post');
    exit();
}   
$imageFileType = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
$allowedFormats = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array($imageFileType, $allowedFormats)) {
    $_SESSION["postError"] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    header('Location: main.php?goTo=post');
    exit();
}
$postId = $post->sendMessage($title.":::". $content);

$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
$targetDir = "img/" . $_SESSION["userId"]. "/posts/".$postId."/";
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0777, true)) {
        die('Failed to create directories...');
    }
}
$targetFile = $targetDir. "photo." . pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
    $_SESSION["postError"] = "none";
    header('Location: main.php?goTo=post');
    exit();
}else{
    $_SESSION["postError"] = "failed to create post";
    header('Location: main.php?goTo=post');
    exit();
}
           

?>