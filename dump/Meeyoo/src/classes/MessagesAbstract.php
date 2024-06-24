<?php

require_once("dbClassStatic.php");

abstract class MessageAbstract{
    protected int $ownerId;
    protected int $receiverId;
    protected bool $inSync;
    protected $offset;
    public function __construct($ownerId, $receiverId){
    if(!(is_numeric($ownerId) && is_numeric($receiverId))){
        return;
    }
    $this->ownerId = $ownerId;
    $this->receiverId = $receiverId;
    $this->inSync = false;
    $this->offset = 0;
    }
    public function getOwner(): int{
        return $this->ownerId;
    }
    public function getReceiver(): int{
        return $this->receiverId;
    }
    public function getOffset(): int{
        return $this->offset;
    }
    public function setOffset(int $offset){
         $this->offset = $offset;
    }
    public function setOwner( $ownerId): void{
        if(!is_numeric($ownerId)){
            throw new Exception("id error");
            }
        $this->ownerId = $ownerId;
        $this->inSync = false;
    }
    public function setReceiver( $receiverId): void{
        if(!is_numeric($receiverId)){
        throw new Exception("id error");
        }
        $this->receiverId = $receiverId;
        $this->inSync = false;
    }
    public function checkWithDb(){
        if(!(isset($this->receiverId)&& isset( $this->ownerId ))){
        return false;
        }
        try{
            DbClass::init();
            if(!DbClass::getUserEmailById($this->ownerId) || !DbClass::getUserEmailById($this->receiverId)){
                return false;
            }else{
                $this->inSync = true;
                return true;
            }
        }catch(Exception $e){
            return false;
        }
    }
    public function getMessages(){
        if(!$this->inSync){
            
        throw new Exception("not in sync with db");
        }
        try{
            DbClass::init();
            if(!$ownerId = DbClass::getUserEmailById($this->ownerId)){
                return false;
            }
            $output = DbClass::getUserMessagesFindByEmail($ownerId, 20,$this->offset);
            if(empty($output)){
                echo "tutaj";
            return false;
            }else{
                $offset = $this->offset;
                return $output;
            }
        
            }catch(Exception $e){
                throw $e;
            }
        }
     public abstract function getSpecificMessages();
     public abstract function deleteMessages();
     public abstract function sendMessage($message);
    
    }
    
?>