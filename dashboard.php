<?php
    session_start();

    $limite_inatividade = 900; // 15 minutos

    if (!isset($_SESSION["user_ID"])){
        header("Location: login.php");
        exit();
    }

    if (isset($_SESSION["ultimo_acesso"])) {
        // Calcule o tempo de inatividade
        $inatividade = time() - $_SESSION["ultimo_acesso"];
    
        // Se o tempo de inatividade exceder o limite, destrua a sessão e redirecione para a página de login
        if ($inatividade > $limite_inatividade) {
            session_unset();     // Limpa as variáveis de sessão
            session_destroy();   // Destroi a sessão
            header("Location: login.php?message=Session expired due to inactivity");
            exit();
        }
    }

    // Atualizar o tempo de último acesso
    $_SESSION["ultimo_acesso"] = time();

    include "config.php"; // Inclui a configuração do banco de dados

    // Função para buscar os dados do usuário
    function getUserData($userId, $conn) {
       $stmt = $conn->prepare("SELECT * FROM Utilizadores WHERE ID = ?");
       $stmt->bind_param("i", $userId);
       $stmt->execute();
       return $stmt->get_result()->fetch_assoc(); 
    }

    // Função para buscar os agendamentos do usuário
    function getUserAppointments ($userId, $conn){
        $stmt = $conn->prepare("SELECT * FROM Agendamentos WHERE User_ID= ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }
    // Função para buscar todos os agendamentos (apenas para o administrador)
    function getAllAppointments($conn)
    {
        $stmt = $conn->prepare("SELECT Agendamentos.ID, Utilizadores.Nome, Agendamentos.Data_reuniao, Agendamentos.Observacoes 
                                FROM Agendamentos 
                                JOIN Utilizadores ON Agendamentos.User_ID = Utilizadores.ID
                                ORDER BY Agendamentos.Data_reuniao DESC");
        $stmt->execute();
        return $stmt->get_result();
    }
    //Função para buscar os projetos
    function getProjects($conn){
        $stmt = $conn->prepare("SELECT * FROM Projetos ORDER BY Data_criacao DESC");
        $stmt->execute();
        return $stmt->get_result();
    }

        //Função para buscar as notícias
        function getNews($conn){
            $stmt = $conn->prepare("SELECT * FROM Noticias ORDER BY Data_publicacao DESC");
            $stmt->execute();
            return $stmt->get_result();
        }

    $userData = getUserData($_SESSION["user_ID"], $conn);
    $userAppointments = getUserAppointments( $_SESSION["user_ID"], $conn);
    $projects = getProjects($conn);
    $news = getNews($conn);

    //verifica se é o administrador
    $is_admin = $_SESSION["user_Tipo"] == "Admin";

    // Buscar todas as reuniões agendadas se for o administrador
    $allAppointments = $is_admin ? getAllAppointments($conn) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body{
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg,#25318cff 0%, #1a1f24ff 50%, #0d1117ff 100%);
            color: #ffffff;
            font-family: "Roboto", sans-serif;
        }
        .container{
            margin-top: 30px;
        }
        table {
            color: #ffffff;
        }
        h1, h2, h3 {
            margin-top: 20px;
        }
        .card {
            background-color: #2c3e50;
            border: none;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .card img {
            border-radius: 10px 10px 0 0;
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
        .table thead th {
            border-bottom: 2px solid #3498db;
        }
        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="btn btn-danger logout-button">Logout</a>
        <h1 class="text-center">Welcome, <?php echo $is_admin ? "Administrator" : "User"; ?></h1>
        
        <?php if ($is_admin): ?>
            <h2 class="text-center">Administrator Area</h2>
            <div class="card p-3">
                <h3>Manage Projects</h3>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Technology</th>
                            <th>Time of completion</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($project = $projects->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $project["ID"]; ?></td>
                                <td><?php echo $project["Titulo"]; ?></td>
                                <td><?php echo $project["Descricao"]; ?></td>
                                <td><img src="<?php echo $project["Imagem"]; ?>" alt="<?php echo $project["Titulo"]; ?>" style="width: 50px;"></td>
                                <td><?php echo $project["Tecnologia"]; ?></td>
                                <td><?php echo $project["Tempo_conclusao"]; ?></td>
                                <td>
                                    <form style="display:inline-block;" method="POST" action="edit_project.php">
                                        <input type="hidden" name="project_id" value="<?php echo $project["ID"]; ?>">
                                        <input type="submit" value="Edit" class="btn btn-warning btn-sm">
                                    </form>
                                    <form style="display:inline-block;" method="POST" action="delete_project.php">
                                        <input type="hidden" name="project_id" value="<?php echo $project["ID"]; ?>">
                                        <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>    
            
            <div class="card p-3">
                <h3>Add New Project</h3>
                <form method="POST" action="add_project.php" enctype="multipart/form-data">
                    <div class="mb-3">       
                        <label class="form-label" for="titulo">Title: </label>
                        <input class="form-control" type="text" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="descricao">Description: </label>
                        <textarea name="descricao" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="imagem">Image: </label>
                        <input class="form-control" type="file" name="imagem" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tecnologia">Technology: </label>
                        <input class="form-control" type="text" name="tecnologia" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tempo_conclusao">Time of Completion: </label>
                        <input class="form-control" type="text" name="tempo_conclusao" required>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Add Project">
                </form>
            </div>

            <div class="card p-3">
                <h3>Manage News</h3>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Image</th>
                            <th>Date of publication</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($newsItem = $news->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $newsItem["ID"]; ?></td>
                                <td><?php echo $newsItem["Titulo"]; ?></td>
                                <td><?php echo substr($newsItem["Conteudo"], 0, 50)."..."; ?></td>
                                <td><img style="width: 50px;" src="<?php echo $newsItem['Imagem']; ?>" alt="<?php echo $newsItem['Titulo']; ?>" ></td>
                                <td><?php echo date('d/m/y H:i:s', strtotime($newsItem['Data_publicacao'])); ?></td>
                                <td>
                                    <form action="edit_news.php" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="news_id" value="<?php echo $newsItem['ID']; ?>">
                                        <input type="submit" value="Edit" class="btn btn-warning btn-sm">    
                                    </form>
                                    <form action="delete_news.php" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="news_id" value="<?php echo $newsItem['ID']; ?>">
                                        <input type="submit" value="Delete" class="btn btn-danger btn-sm" >
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="card p-3">
                <h3>Add New News</h3>
                <form action="add_news.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">    
                        <label for="titulo" class="form-label">Title: </label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="conteudo" class="form-label">Content: </label>
                        <textarea name="conteudo" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Image: </label>
                        <input type="file" name="imagem" class="form-control">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Add News">
                </form>
            </div>

            <div class="card p-3"> 
                <h3>Update Users Data</h3>
                <form action="update_user.php" method="GET" >
                    <div class="mb-3">
                        <label for="select_user" class="form-label">Select User: </label>
                        <select name="user_ID" id="select_user" class="form-select">
                            <?php
                                $result = $conn->query("SELECT ID, Nome FROM Utilizadores");
                                while ($row = $result->fetch_assoc()){
                                    echo "<option value='{$row['ID']}'>{$row['Nome']}</option>";    
                                }
                            ?>
                        </select>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Select Client">
                </form>
            </div>
            
            <div class="card p-3">
                <h3>All Scheduled Meetings</h3>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Date of Meeting</th>
                            <th>Observations</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($allAppointments && $allAppointments->num_rows > 0): ?>
                            <?php while($appointment = $allAppointments->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $appointment["ID"]; ?></td>
                                    <td><?php echo $appointment["Nome"]; ?></td>
                                    <td><?php echo $appointment["Data_reuniao"]; ?></td>
                                    <td><?php echo $appointment["Observacoes"]; ?></td>
                                    <td>
                                        <form method="POST" action="edit_appointment.php">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment["ID"]; ?>">
                                            <input type="submit" value="Edit" class="btn btn-warning btn-sm">
                                        </form>
                                        <form method="POST" action="delete_appointment.php">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment["ID"]; ?>">
                                            <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No meetings scheduled</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
         
        <?php else: ?>
            <h2 class="text-center">User Area</h2>

            <div class="card p-3">
                <h3>Update Personal Data</h3>
                <form action="update_user_data.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label" for="nome">Name: </label>
                        <input type="text" name="nome" class="form-control" value="<?php echo $userData['Nome']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email: </label>
                        <input class="form-control" type="email" name="email" value="<?php echo $userData['Email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="senha">New password: </label>
                        <input class="form-control" type="password" name="password" value="<?php echo $userData['Senha']; ?>" required>
                    </div>
                    <input class="form-control" class="btn btn-primary" type="submit" value="Update Data">
                </form>
            </div>

            <div class="card p-3">
                <h3>Schedule Meeting</h3>
                <form action="schedule_appointment.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label" for="data_reuniao">Date of meeting: </label>
                        <input class="form-control" type="datetime-local" name="data_reuniao" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="observacao">Observations:</label>
                        <textarea name="observacoes" class="form-control" required></textarea>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Schedule Meeting">
                </form>
            </div>

            <div class="card p-3">
                <h3>Consult Appointments</h3>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Observations</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $userAppointments->fetch_assoc()): ?>
                            <?php  
                                $dataReuniao = new DateTime($row["Data_reuniao"]);
                                $agora = new DateTime();
                                $interval = $agora->diff($dataReuniao);
                                $hours = $interval-> h + ($interval->days * 24);

                                if($hours > 72){
                                    $canEdit = true;
                                }else{
                                    $canEdit = false;
                                }
                            ?>
                            <tr>
                                <td><?php echo $row["Data_reuniao"]; ?></td>
                                <td><?php echo $row["Observacoes"]; ?></td>
                                <td>
                                    <?php if ($canEdit): ?>
                                        <form method="POST" action="update_appointment_user.php">
                                            <input type="hidden" name="appointment_id" value="<?php echo $row["ID"]; ?>">
                                            <input type="datetime-local" class="form-control" name="data_reuniao" value="<?php echo $row["Data_reuniao"]; ?>" required>
                                            <input type="text" name="observacoes" class="form-control" value="<?php echo $row["Observacoes"]; ?>" required>
                                            <input type="submit" value="Update" class="btn btn-primary">
                                        </form>
                                    <?php else: ?>
                                        <span class="text-danger">cannot be changed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TI7AeoM79WvnB68HAt1wNH8e4fRv4rkndJz9tU8/d0WdxPSQnLAs" crossorigin="anonymous"></script>
</body>
</html>