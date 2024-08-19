<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include "config.php";

    header('Content-Type: application/json');

    $sql = "SELECT ID, Titulo FROM Noticias ORDER BY Data_publicacao DESC LIMIT 5";
    $result = $conn->query($sql);

    $news = [];

    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            $news[] = $row; 
        }
    }

    echo json_encode($news);

    $conn->close();
?>