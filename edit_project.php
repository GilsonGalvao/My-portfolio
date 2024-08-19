<?php
    session_start();

    if(!isset($_SESSION["user_ID"]) || $_SESSION["user_Tipo"] != "Admin"){
        header("Location: login.html");
        exit();
    }

    include "config.php";

    function getProject ($projectId, $conn){
        $stmt = $conn->prepare("SELECT * FROM Projetos WHERE ID = ?");
        $stmt->bind_param("i",$projectId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $projectId = $_POST["project_id"];
        $project = getProject($projectId, $conn);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_project"])){
        $projectId = $_POST["project_id"];
        $titulo = $_POST["titulo"];
        $descricao = $_POST["descricao"];
        $tecnologia = $_POST["tecnologia"];
        $tempo_conclusao = $_POST["tempo_conclusao"];
        $data_criacao = date("y-m-d h:i:s");

        if ($_FILES["imagem"]["name"]){
            $target_dir = "image/";
            $target_file = $target_dir.basename($_FILES["imagem"]["name"]);
            move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file);

        }else {
            $target_file = $project["Imagem"];
        }

        $stmt = $conn->prepare("UPDATE Projetos SET Titulo = ?, Descricao = ?, imagem = ?, Tecnologia = ?, Tempo_conclusao = ?, Data_criacao = ? WHERE ID = ?");
        $stmt->bind_param("ssssssi", $titulo, $descricao, $target_file, $tecnologia, $tempo_conclusao, $data_criacao,$projectId);
    
        if ($stmt->execute()){
            echo "project successfully updated!";        
        } else {
            echo "Error updating the project: ".$stmt->error;
        }
        header("Location: dashboard.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #25318c 0%, #1a1f24 50%, #0d1117 100%);
            color: #ffffff;
            font-family: "Roboto", sans-serif;
            min-height: 100vh;
            background-repeat: no-repeat;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            background-color: #2c3e50;
            border-radius: 10px;
            padding: 20px;
            border: none;
        }
        .form-control, .btn {
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
            <h1>Edit Project</h1>
    
            <?php if (isset($project)): ?>
                <form method="POST" action="edit_project.php" enctype="multipart/form-data">
                    <input type="hidden" name="project_id" value="<?php echo $project['ID']; ?>">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Title:</label>
                        <input type="text" name="titulo" class="form-control" value="<?php echo $project['Titulo']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Description:</label>
                        <textarea name="descricao" class="form-control" required><?php echo $project['Descricao']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Image:</label>
                        <input type="file" name="imagem" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="tecnologia" class="form-label">Technology:</label>
                        <input type="text" name="tecnologia" class="form-control" value="<?php echo $project['Tecnologia']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tempo_conclusao" class="form-label">Time of Completion:</label>
                        <input type="text" name="tempo_conclusao" class="form-control" value="<?php echo $project['Tempo_conclusao']; ?>" required>
                    </div>
                    <input type="hidden" name="update_project" value="1">
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="Update Project">
                    </div>
                </form>
            <?php else: ?>
                <p class="text-center">Project not found!</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>