<?php
// Function to check if a user is already connected
///////////////////////////////////////////////////////

    class clsConnection{


        private $usuario;
        private $xml_file = 'xmldb/connection.xml';
        private $xml;
        private $conectado = false;

        public function __construct($username){
            $this->Load();
            $this->usuario = $username;
            if(!$this->isUserConnected($this->usuario)){
                $this->writeConnection();
                $this->conectado = true;
                echo "Conexion establecida <br>";
            } else {
                echo "Conexion no establecida <br>";
            }
        }

        public function GetConectado(): bool {
            return $this->conectado;
        }

        private function Load (): void {
            if (file_exists($this->xml_file)) {
                $this->xml = simplexml_load_file($this->xml_file);
            } else {
                $this->xml = new SimpleXMLElement('<connections></connections>');
                $this->xml->asXML($this->xml_file);
            };
            
        }

        private function Save(): void {
            $this->xml->asXML($this->xml_file);
        }


        public function isUserConnected($username): bool{
            foreach ($this->xml->connection as $connection) {
                if ($connection->user == $username) {
                    // Check if the connection is still valid (within 5 minutes)
                    $currentTime = time();
                    $connectionTime = strtotime($connection->date);
                    $expirationTime = $connectionTime + (5 * 60);
                    if ($currentTime < $expirationTime) {
                        $this->conectado = true; // User is already connected
                        return true;
                    }
                }
            }
            return false; 
        }

        private function writeConnection(): void{
            $connection = $this->xml->addChild('connection');
            $connection->addChild('user', $this->usuario);
            $connection->addChild('date', date('Y-m-d H:i:s'));
            // Save the updated connections to connection.xml
            $this->Save();
        }

    }
?>
