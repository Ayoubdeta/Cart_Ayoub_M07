<?php

    function Load(){
        if (file_exists('xmldb/users.xml')) { 
            $xml = simplexml_load_file('xmldb/users.xml'); 
        } else {
            $xml = new SimpleXMLElement('<users></users>'); 
        }
        return $xml;
    }

    function UserRegister($username,$password){
        $xml=Load();
        $item = $xml->addChild('user'); 
        $item->addChild('username', $username); 
        $item->addChild('password', $password); 
        
        $xml->asXML('xmldb/users.xml');
    }


    function login($username,$password){
        $xml = simplexml_load_file('xmldb/users.xml'); 
        foreach($xml->user as $user){
            if($user->username == $username ){
                if($user->password == $password){
                    echo "Has hecho login";
                } else {
                    echo "Contraseña incorrecta";
                }
            } else {
                echo "No existe";
            }

        }
    }


    /*
    function saveXML(): void {
        $this->xml->asXML($this->xmlfile);
    }
    */






?>