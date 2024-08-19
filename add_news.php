<?php 
    session_start();

    if(!isset($_SESSION["user_ID"]) || $_SESSION["user_Tipo"] != "Admin"){
        header("Location: login.html");
        exit();
    }

    include "config.php";
    
    $errorMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $titulo = $_POST["titulo"];
        $conteudo = $_POST["conteudo"];
        $data_publicacao = date("Y-m-d H:i:s");

        //Upload da imagem, se houver
        $imagem = "";
        if(!empty($_FILES["imagem"]["name"])){
            $target_dir = "image/news/";
            $target_file = $target_dir.basename($_FILES["imagem"]["name"]);
            if(move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
                $imagem = $target_file;
            }else{
                $errorMessage = "Error uploading image.";
            }
        }

        if(empty($errorMessage)) {
            $stmt = $conn->prepare("INSERT INTO Noticias (Titulo, Conteudo, Imagem, Data_publicacao) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $titulo, $conteudo, $imagem, $data_publicacao);
        
            if($stmt->execute()){
                header("Location: dashboard.php");
            } else{
                $errorMessage = "Error adding news: ".$stmt->error;
            }
        }
        


    }
?>