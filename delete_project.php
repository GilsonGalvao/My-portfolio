<?php
    session_start();

    if(!isset($_SESSION["user_ID"]) || $_SESSION["user_Tipo"] != "Admin"){
        header("Location: login.html");
        exit();
    }

    include "config.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $projectId = $_POST["project_id"];
        
        $stmt = $conn->prepare("DELETE FROM Projetos WHERE ID = ?");
        $stmt->bind_param("i", $projectId);

        if($stmt->execute()){
            $success_message = "Project successfully deleted!";
        }else{
            $error_message = "Error deleting project: " . $stmt->error;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #25318c 0%, #1a1f24 50%, #0d1117 100%);
            min-height: 100vh;
            background-repeat: no-repeat;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2 class="text-center">Delete Project</h2>
            <!-- Display Success or Error Message -->
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success text-center" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <!-- Add Return to Dashboard Button -->
            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-secondary">Return to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
