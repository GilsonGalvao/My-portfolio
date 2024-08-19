<?php
session_start();

if (!isset($_SESSION["user_ID"]) || $_SESSION["user_Tipo"] != "Admin") {
    header("Location: login.php");
    exit();
}

include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["confirm_delete"])) {
    $appointmentId = $_POST["appointment_id"];
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_delete"])) {
    $appointmentId = $_POST["appointment_id"];

    $stmt = $conn->prepare("DELETE FROM Agendamentos WHERE ID = ?");
    $stmt->bind_param("i", $appointmentId);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Error deleting appointment: " . $stmt->error;
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #25318c 0%, #1a1f24 50%, #0d1117 100%);
            color: #ffffff;
            font-family: "Roboto", sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background-color: #2c3e50;
            border: none;
            border-radius: 10px;
            padding: 20px;
            width: 100%;
            max-width: 600px;
        }
        .card h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        .form-control, .btn {
            border-radius: 5px;
        }
        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this appointment?");
        }
    </script>
</head>
<body>
<div class="container">
    <div class="card">
        <h1 class="text-center">Delete Appointment</h1>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($appointmentId)): ?>
            <form method="POST">
                <input type="hidden" name="appointment_id" value="<?php echo $appointmentId; ?>">
                <input type="hidden" name="confirm_delete" value="1">
                <div class="text-center">
                    <p>Are you sure you want to delete this appointment?</p>
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <p class="text-center">Invalid request!</p>
            <div class="text-center">
                <a href="dashboard.php" class="btn btn-primary">Go back to Dashboard</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
