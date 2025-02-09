<?php  

function db_connect() {
    $host = "localhost";
    $user = "root";       
    $password = "";      
    $database = "urbancare";

    $conn = new mysqli($host, $user, "", $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

function db_close($conn) {
    $conn->close();
}
?>
