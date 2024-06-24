<?php
interface Sendable{
    public function send();
    public function prepareMessage($message);
    public function setReceiverId($to);
    public function setSenderId($from);


}
?>