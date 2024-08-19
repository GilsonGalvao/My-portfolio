
<?php
    include "config.php"; //arquivo de configuração para conexão com a base de dados

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //dados do login e que vão para tabela utilizadores
        $nome = $_POST["username"];
        $senha = password_hash($_POST["password"], PASSWORD_BCRYPT); //encriptografar a senha
        $email = $_POST["email"];

        // Dados adicionais do formulário
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phone = $_POST['phone'];
        $pageType = $_POST['pageType'];
        $prazo = $_POST['prazo'];
        $sections = isset($_POST['sections']) ? implode(', ', (array)$_POST['sections']) : ' ';
        $valorFinal = $_POST['valorFinal'];

        //verificar se o nome do usuario já existe
        $query = $conn->prepare("SELECT * FROM Utilizadores WHERE nome = ?");
        $query->bind_param("s", $nome);
        $query->execute();
        $result = $query->get_result();

        if($result->num_rows > 0){
            $message = "Nome do usuário já existe";
        }else{
            //insere o novo utilizador no banco de dados da tabela utilizadores
            $stmt = $conn->prepare("INSERT INTO Utilizadores (nome, senha, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $senha, $email);
            if($stmt->execute()){
                //Obter o ID do novo utilizador
                $user_id = $stmt->insert_id;

                //inserir os dados na tabela Cliente
                $stmt_cliente = $conn->prepare("INSERT INTO Clientes (firstName, lastName, phone, pageType, prazo, sections, email, username, user_id, valorFinal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_cliente->bind_param("ssssisssis", $firstName, $lastName, $phone, $pageType, $prazo, $sections, $email, $nome, $user_id, $valorFinal);
                if ($stmt_cliente->execute()){
                    $message = "successfully registered!";

                } else{
                    $message = "Error when registering in the Clients table!";
                }
            } else{
                $message = "Registration error!";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body{
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg,#25318cff 0%, #1a1f24ff 50%, #0d1117ff 100%);
            color: #ffffff;
            font-family: "Roboto", sans-serif;
        }
        .message-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-box {
            text-align: center;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: 
        }
    </style>
</head>
<body>
    <div class="message-container">
        <div class="message-box">
            <p><?php echo $message; ?></p>
            <a href="index.php" class="btn btn-primary">Back to home page</a>
        </div>
    </div>
</body>
</html>