<?php
    $nova_senha = "nova_senha_forte";
    $hash = password_hash("nova_senha", PASSWORD_BCRYPT);

    echo "Hash da nova senha: ".$hash;
?>