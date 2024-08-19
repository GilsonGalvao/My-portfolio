<?php
    session_start();

    if(!isset($_SESSION["user_ID"]) || $_SESSION["user_Tipo"] != "Admin"){
        header("Location: login.html");
        exit();
    }

    include "config.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $titulo = $_POST["titulo"];
        $descricao = $_POST["descricao"];
        $tecnologia = $_POST["tecnologia"];
        $tempo_conclusao = $_POST["tempo_conclusao"];
        $data_criacao = date("y-m-d h:i:s");

        //upload da imagem
        $target_dir = "image/";
        $target_file = $target_dir.basename($_FILES["imagem"]["name"]);
        move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file);

        $stmt = $conn->prepare("INSERT INTO Projetos (Titulo, Descricao, Imagem, Tecnologia, Tempo_conclusao, Data_criacao) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $titulo, $descricao, $target_file, $tecnologia, $tempo_conclusao, $data_criacao); 

        if($stmt->execute()){
            echo "project successfully added!";
        }else{
            echo "Error when adding project: ".$stmt->error;
        }

        header("Location: dashboard.php");
    }
?>