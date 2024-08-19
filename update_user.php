<?php
    session_start();
    if( !isset($_SESSION["user_ID"]) || $_SESSION["user_Tipo"] != "Admin"){
        header("Location: login.html");
        exit();
    }

    include "config.php";

    // Função para buscar os dados do usuário
    function getUserData ($userId, $conn){
        $stmt = $conn->prepare("SELECT * FROM Utilizadores WHERE ID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    //variável para armazenar os dados do usuário
    $user = null;
    
    //Verificar se o user_ID foi passado na query string
    if (isset($_GET["user_ID"])){
        $userId = $_GET["user_ID"];
        $user = getUserData($userId, $conn);
    }

    //Verificar se o formulário foi enviado para atualizar os dados
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])){
            $userId = $_POST["user_id"];
            $nome = $_POST["nome"];
            $email = $_POST["email"];
            $senha = !empty($_POST["senha"]) ? password_hash($_POST["senha"], PASSWORD_BCRYPT): null;

            if ($senha){
                $stmt = $conn->prepare("UPDATE Utilizadores SET Nome = ?, Email = ?, Senha = ? WHERE ID = ?");
                $stmt->bind_param("sssi", $nome, $email, $senha, $userId);
            } else{
                $stmt = $conn->prepare("UPDATE Utilizadores SET Nome = ?, Email = ? WHERE ID = ?");
                $stmt->bind_param("ssi", $nome, $email, $userId);
            }

            if($stmt->execute()){
                echo "<div class='alert alert-success'>Data successfully updated!</div>";
            }else {
                echo "<div class='alert alert-danger'>Error updating data: ".$stmt->error."</div>";
            }

            // Após atualização dos dados, busque novamente os dados do usuário
            $user = getUserData($userId, $conn);
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
        body{
            background: linear-gradient(135deg, #25318c 0%, #1a1f24 50%, #0d1117 100%);
            color: #ffffff;
            font-family: "Roboto", sans-serif;
            min-height: 100vh;
            background-repeat: no-repeat;
        }
        .container{
            margin-top: 50px;
            max-width: 600px;
        }
        .card{
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
            <h1 class="text-center">Update User Data</h1>
            <!-- Add Return to Dashboard Button -->
            <div class="text-center mb-4">
                <a href="dashboard.php" class="btn btn-secondary">Return to Dashboard</a>
            </div>
            <?php if (!$user) : ?>
                <form method="GET" action="update_user.php">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select User:</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Select...</option>
                            <?php 
                            $result = $conn->query("SELECT ID, Nome FROM Utilizadores");
                            while ($row = $result->fetch_assoc()): ?>
                                <option value="<?php echo $row['ID']; ?>"><?php echo $row['Nome']; ?></option>
                            <?php endwhile; ?>    
                        </select>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="Select User">
                    </div>
                </form>
            <?php else: ?>
                <form action="update_user.php?user_ID=<?php echo $user["ID"]; ?>" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user["ID"]; ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Name:</label>
                        <input type="text" name="nome" class="form-control" value="<?php echo $user["Nome"]; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $user["Email"]; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">New Password (leave blank if you don't want to change it):</label>
                        <input type="password" name="senha" class="form-control">
                    </div>
                    <input type="hidden" name="update_user" value="1">
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="Update User">
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>