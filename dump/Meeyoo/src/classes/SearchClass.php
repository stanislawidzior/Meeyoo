<?php
include("dbUserClass.php");
class Search {
    public static function searchByName($name) {
        try{
            DbClass::init();
        }catch(Exception $e){
            return false;
        }
        $output = [];
        if(($output = DbClass::findUsersByName($name))!==false){
            return $output;
        }
}
public static function searchByEmail($email) {
    try{
        DbClass::init();
    }catch(Exception $e){
        return false;
    }
    $output = [];
    if(($output = DbClass::findUsersByEmail($email))!==false){
        return $output;
    }
}
}
?>