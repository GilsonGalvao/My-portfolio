<?php
    session_start();
    include "config.php";

    $errorMessage = ""; // Variável para armazenar a mensagem de erro

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $nome = $_POST["username"];
        $senha = $_POST["password"];

        $query = $conn->prepare("SELECT * FROM Utilizadores WHERE nome = ?");
        $query-> bind_param("s", $nome);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 1){
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user["Senha"])){
                $_SESSION["user_ID"] = $user["ID"];
                $_SESSION["user_Tipo"] = $user["Tipo"];
                $_SESSION["username"] = $user["Nome"];
                header("Location: dashboard.php");
                exit();
            } else{
                $errorMessage = "Senha incorreta!";

            }
        }else {
            $errorMessage = "Utilizador não encontrado!";

        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Login">
    <meta name="author" content="Gilson Galvão">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html{
            margin: 0;
            height: 100%;
        }
        .bg{
            background-image: url("image/PlanoDeFundo/plano-de-fundo.jpg") ;
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .login-container{
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box{
            background-color: rgba(0, 0, 0, 0.7); /* Fundo semi-transparente */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #ffffff;
        }
        .login-box .form-control{
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            color: #ffffff;
        }
        .login-box .form-control:focus{
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }
        .login-box .btn-primary:hover {
            border-color: rgb(45, 65, 95);
        }
    </style>
</head>
<body>
    <div class="bg">
        <div class="login-container">
            <div class="login-box">
            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-warning text-center">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                </div>
            <?php endif; ?>
                <h2 class="mb-3">LOGIN</h2>
                
                <!-- Exibe a mensagem de erro, se houver -->
                <?php if ($errorMessage): ?>
                    <div class="alert alert-danger">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                
                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Enter</button>
                </form>
                <div class="mt-3">
                    <a href="forgot_password.html">Forgot your password?</a>
                </div>
                <div class="mt-2">
                    <a href="index.php#budget">Register!</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>