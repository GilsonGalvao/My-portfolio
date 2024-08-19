<?php
    session_start();

    if(!isset($_SESSION["user_ID"]) ||$_SESSION["user_Tipo"] != "Admin"){
        header("Location: login.html");
        exit();
    }

    include "config.php";

    function getNewsItem($newsId, $conn){
        $stmt = $conn->prepare("SELECT * FROM Noticias WHERE ID = ?");
        $stmt->bind_param("i", $newsId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["news_id"])){
        $newsId = $_POST["news_id"];
        $newsItem = getNewsItem($newsId, $conn);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_news"])){
        $newsId = $_POST["news_id"];
        $titulo = $_POST["titulo"];
        $conteudo = $_POST["conteudo"];

        if($_FILES["imagem"]["name"]){
            $target_dir = "image/news/";
            $target_file = $target_dir . basename($_FILES["imagem"]["name"]);
            move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file);
        }else{
            $target_file = $newsItem["Imagem"];
        }

        $stmt = $conn->prepare("UPDATE Noticias SET Titulo = ?, Conteudo = ?, Imagem = ? WHERE ID = ?");
        $stmt->bind_param("sssi",$titulo,$conteudo,$target_file,$newsId);

        if($stmt->execute()){
            echo "<div class='alert alert-success'>News successfully updated!</div>";
        }else{
            echo "<div class='alert alert-danger'>Error updating news: ".$stmt->error."</div>";
        }

        header("Location: dashboard.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background: linear-gradient(135deg, #25318c 0%, #1a1f24 50%, #0d1117 100%);
            min-height: 100vh;
            background-repeat: no-repeat;
            color: #ffffff;
            font-family: "Roboto", sans-serif;
        }
        .container{
            margin-top: 50px;
        }
        .card{
            background-color:#2c3e50;
            border-radius: 10px;
            border: none;
            padding: 20px;
        }
        .form-control, .btn{
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }   
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            
            <h1 class="text-center">Edit News</h1>
            
            <?php if (isset($newsItem)): ?>
                <form method="POST" action="edit_news.php" enctype="multipart/form-data">
                    <input type="hidden" name="news_id" value="<?php echo $newsItem["ID"]; ?>">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Title:</label>
                        <input type="text" name="titulo" class="form-control" value="<?php echo $newsItem["Titulo"]; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="conteudo" class="form-label">Content:</label>
                        <textarea name="conteudo" class="form-control" required><?php echo $newsItem["Conteudo"]; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Image:</label>
                        <input type="file" name="imagem" class="form-control">
                    </div>
                    <input type="hidden" name="update_news" value="1">
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="Update News">
                    </div>
                </form>
            <?php else: ?>
                <p class="text-center">News not found!</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>