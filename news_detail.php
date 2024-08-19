<?php
    include "config.php";

    if(isset($_GET["id"])){
        $id = $_GET["id"];
        $stmt = $conn->prepare("SELECT * FROM Noticias WHERE ID = ?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $news = $result->fetch_assoc();
    } else{
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $news["Titulo"]; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container my-4">
        <h1><?php echo $news['Titulo']; ?></h1>
        <p><em>published in: <?php echo date("d m y", strtotime($news["Data_publicacao"])); ?></em></p>
        <?php if($news["Imagem"]): ?>
            <img src="<?php echo $news['Imagem']; ?>" class="img-fluid mb-4" alt="<?php echo $news["Titulo"]; ?>">
        <?php endif; ?>
        <p><?php echo nl2br($news["Conteudo"]); ?></p>
        <a href="index.php" class="btn btn-primary mt-4">Back</a>
    </div>  
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TI7AeoM79WvnB68HAt1wNH8e4fRv4rkndJz9tU8/d0WdxPSQnLAs" crossorigin="anonymous"></script>
</body>
</html>