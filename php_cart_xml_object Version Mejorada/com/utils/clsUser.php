<?php

class clsUser{
    private $xml;
    private $xmlfile = "xmldb/users.xml";
    private $username;
    private $password;

    function __construct() {
        $this->Load();

    }

    function Load():void {
        if (file_exists('xmldb/users.xml')) { 
            $this->xml = simplexml_load_file($this->xmlfile); 
        } else {
            $this->xml = new SimpleXMLElement('<users></users>'); 
        }
    }

    function register($username,$password):void {
        $item = $this->xml->addChild('user'); 
        $item->addChild('username', $username); 
        $item->addChild('password', $password); 
        
        $this->username = $username;
        $this->saveXML();
    }


    function login($username,$password):void {

        foreach($this->xml->user as $user){
            if($user->username == $username ){
                if($user->password == $password){
                    $this->username = $username;
                    echo "Has hecho login";
                    break;
                } else {
                    echo "Contraseña incorrecta";
                }
            } else {
                echo "No existe";
            }

        }
    }

    private function saveXML(): void {
        $this->xml->asXML($this->xmlfile);
    }
    

    public function getUser(){
        return $this->username;
    }

}
?>