<?php
    include "config.php";
    // Incluir os arquivos do PHPMailer
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $messg = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST["email"];

        // Verificar se o e-mail existe no banco de dados
        $query = $conn->prepare("SELECT id FROM Utilizadores WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0){
            // Verificar se o e-mail existe no banco de dados
            $user = $result->fetch_assoc();
            $user_id = $user['id'];
            $token = bin2hex(random_bytes(50));

            // Armazenar o token no banco de dados com uma validade de 1 hora
            $expires = date("U") + 3600;
            $conn->query("DELETE FROM password_resets WHERE user_id = '$user_id'");
            $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $token, $expires);
            $stmt->execute();

            // Enviar o e-mail de redefinição de senha
            $reset_link = "http://localhost/reset_password.php?token=$token";
            $mail = new PHPMailer(true);

            try{
                //configuração do servidor
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Servidor SMTP do Gmail
                $mail->SMTPAuth = true;
                $mail->Username = 'gilsondag@gmail.com'; 
                $mail->Password = 'G24685225g*';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                
                // Configurações do e-mail
                $mail->setFrom('gilsondag@gmail.com', 'No-reply');
                $mail->addAddress($email); // Adicionar destinatário

                // Conteúdo do e-mail
                $mail->isHTML(true);
                $mail->Subject = 'Redefinir sua senha';
                $mail->Body    = "Clique no link a seguir para redefinir sua senha: <a href='$reset_link'>$reset_link</a>";
                
                $mail->send();
                $messg = "Reset link sent to your e-mail.";
            }   catch (Exception $e){
                    $messg =  "error sending e-mail. Mailer Error: {$mail->ErrorInfo}";
                }
        } else {
            $messg =  "No users found with this e-mail.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            <p><?php echo $messg; ?></p>
            <a href="login.html" class="btn btn-primary">Back to the Login Page</a>
        </div>
    </div>
</body>
</html>