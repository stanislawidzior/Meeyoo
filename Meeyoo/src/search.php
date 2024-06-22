<?php
include("classes/SearchClass.php");
$query = strip_tags("a");
$searchBy = strip_tags("name");
$results = [];
if ($searchBy === "email") {
    $results = Search::searchByEmail($query);
} elseif ($searchBy === "name") {
    $results = Search::searchByName($query);
}
$_SESSION["results"] = $results;

header("Location: main.php");
exit();
