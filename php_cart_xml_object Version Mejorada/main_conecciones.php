<?php
// Function to check if a user is already connected
///////////////////////////////////////////////////////
function isUserConnected($username, $connections){
    foreach ($connections->connection as $connection) {
        if ($connection->user == $username) {
            // Check if the connection is still valid (within 5 minutes)
            $currentTime = time();
            $connectionTime = strtotime($connection->date);
            $expirationTime = $connectionTime + (5 * 60);
            if ($currentTime < $expirationTime) {
                return true; // User is already connected
            }
        }
    }
    return false; // User is not connected or the connection has expired
}
// Function to write a connection to the connection.xml file
///////////////////////////////////////////////////////
function writeConnection($username){
    // Load existing connections or create a new XML document
    if (file_exists('xmldb/connection.xml')) {
        $connections = simplexml_load_file('xmldb/connection.xml');
    } else {

        $connections = new SimpleXMLElement('<connections></connections>');
    }
    // Create a new connection entry
    $connection = $connections->addChild('connection');
    $connection->addChild('user', $username);
    $connection->addChild('date', date('Y-m-d H:i:s'));
    // Save the updated connections to connection.xml
    $connections->asXML('xmldb/connection.xml');
}
////////////////////////////////////////////////////////////////
// Check if username and password are provided in the URL
if (isset($_GET['username']) && isset($_GET['password'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];
    // Load user.xml file
    $users = simplexml_load_file('xmldb/users.xml');
    // Check if the user exists and the password matches
    foreach ($users->user as $user) {
        if ($user->username == $username && $user->password == $password) {
            // Check if the user is already connected
            $connections = simplexml_load_file('xmldb/connection.xml');
            if (!isUserConnected($username, $connections)) {
                // Write the new connection to connection.xml
                writeConnection($username);
                echo "Connection successful for user: $username";
            } else {
                echo "User $username is already connected.";
            }
            exit(); // Stop execution after successful connection
        }
    }
    echo "Invalid username or password";
} else {
    echo "Username and password are required in the URL";
}

?>
