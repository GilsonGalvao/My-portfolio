<?php
    $servername = "localhost";
    $username = "root";
    $password = "nova_senha";
    $dbname = "projetofinalphp";

    //criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    //verificar a conexão
    if($conn->connect_error){
        die ("Connection failed: ".$conn->connect_error);
    }

?>