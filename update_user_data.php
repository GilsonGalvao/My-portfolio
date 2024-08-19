<?php
    session_start();
    if(!isset($_SESSION["user_ID"])){
        header("Location: login.html");
        exit();
    }

    include "config.php";

    $userId = $_SESSION["user_ID"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE Utilizadores SET Nome = ?, Email = ?, Senha = ? WHERE ID = ?");
    $stmt->bind_param("sssi", $nome, $email, $senha, $userId);
    
    $updateSuccess = false;
    $errorMsg = "";

    if ($stmt->execute()){
        $updateSuccess = true;
    } else{
        $errorMsg = "Error updating data: ".$stmt->error;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #25318c 0%, #1a1f24 50%, #0d1117 100%);
            color: #ffffff;
            font-family: "Roboto", sans-serif;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .card {
            background-color: #2c3e50;
            border-radius: 10px;
            padding: 20px;
            border: none;
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
            <h1 class="text-center">Update User Data</h1>
            <!-- Add Return to Dashboard Button -->
            <div class="text-center mb-4">
                <a href="dashboard.php" class="btn btn-secondary">Return to Dashboard</a>
            </div>
            <?php if ($updateSuccess): ?>
                <div class="alert alert-success text-center">
                    Successfully updated data!
                </div>
            <?php else: ?>
                <div class="alert alert-danger text-center">
                    <?php echo $errorMsg; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>