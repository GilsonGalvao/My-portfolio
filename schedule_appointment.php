<?php
    session_start();
    if (!isset($_SESSION["user_ID"])) {
        header("Location: login.html");
        exit();
    }

    include "config.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_SESSION["user_ID"];
        $dataReuniao = $_POST["data_reuniao"];
        $observacoes = $_POST["observacoes"];

        $stmt = $conn->prepare("INSERT INTO Agendamentos (User_ID, Data_reuniao, Observacoes) VALUE (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $dataReuniao, $observacoes);

        if ($stmt->execute()) {
            $success_message = "Meeting successfully scheduled!";
        } else {
            $error_message = "Error scheduling meeting: " . $stmt->error;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule a Meeting</title>
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
            <h2 class="text-center">Schedule a Meeting</h2>
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
            <!-- Schedule Meeting Form -->
            <form method="POST" action="schedule_appointment.php">
                <div class="mb-3">
                    <label for="data_reuniao" class="form-label">Meeting Date and Time</label>
                    <input type="datetime-local" name="data_reuniao" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observations</label>
                    <textarea name="observacoes" class="form-control" rows="3" required></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Schedule Meeting</button>
                </div>
            </form>
            <!-- Add Return to Dashboard Button -->
            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-secondary">Return to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>