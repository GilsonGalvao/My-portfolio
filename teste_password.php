<?php
    include "config.php";

    $nome = "administrador";
    $senha_plain = "nova_senha";

    $query = $conn->prepare("SELECT Senha FROM Utilizadores WHERE Nome = ?");
    $query->bind_param("s",$nome);
    $query->execute();
    $result = $query->get_result();

    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        $senha_hash = $user["Senha"];
        if(password_verify($senha_plain, $senha_hash)){
            echo "A senha está correta!";
        } else{
            echo "A senha está errada!";
        }
    } else {
        echo "Utilizador não encontrado";
    }
?>