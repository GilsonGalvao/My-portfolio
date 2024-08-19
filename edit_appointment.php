<?php
    session_start();

    if (!isset($_SESSION["user_ID"]) || $_SESSION["user_Tipo"] != "Admin") {
        header("Location: login.php");
        exit();
    }

    include "config.php";

    function getAppointment($appointmentId, $conn) {
        $stmt = $conn->prepare("SELECT * FROM Agendamentos WHERE ID = ?");
        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["appointment_id"])) {
        $appointmentId = $_POST["appointment_id"];
        $appointment = getAppointment($appointmentId, $conn);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_appointment"])) {
        $appointmentId = $_POST["appointment_id"];
        $data_reuniao = $_POST["data_reuniao"];
        $observacoes = $_POST["observacoes"];

        $stmt = $conn->prepare("UPDATE Agendamentos SET Data_reuniao = ?, Observacoes = ? WHERE ID = ?");
        $stmt->bind_param("ssi", $data_reuniao, $observacoes, $appointmentId);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
        } else {
            echo "Error updating appointment: " . $stmt->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
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
            <h1 class="text-center">Edit Appointment</h1>
            
            <?php if (isset($appointment)): ?>
                <form method="POST" action="edit_appointment.php">
                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['ID']; ?>">
                    <div class="mb-3">
                        <label for="data_reuniao" class="form-label">Date of Meeting:</label>
                        <input type="datetime-local" class="form-control" name="data_reuniao" value="<?php echo $appointment['Data_reuniao']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observations:</label>
                        <textarea class="form-control" name="observacoes" required><?php echo $appointment['Observacoes']; ?></textarea>
                    </div>
                    <input type="hidden" name="update_appointment" value="1">
                    <div class="text-center">
                    <button type="submit" class="btn btn-primary">Update Appointment</button>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-center">Appointment not found!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
