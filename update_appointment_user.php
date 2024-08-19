<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Your Appointment</title>
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
            <h2 class="text-center">Update Your Appointment</h2>
            <!-- Add Return to Dashboard Button -->
            <div class="text-center mb-4">
                <a href="dashboard.php" class="btn btn-secondary">Return to Dashboard</a>
            </div>
            <!-- Form for Updating User Appointments -->
            <form method="POST" action="update_appointment_user.php">
                <input type="hidden" name="appointment_id" value="<?php echo $appointmentId; ?>">
                <div class="mb-3">
                    <label for="data_reuniao" class="form-label">New Meeting Date and Time</label>
                    <input type="datetime-local" name="data_reuniao" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observations</label>
                    <textarea name="observacoes" class="form-control" rows="3" required></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Update Appointment</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
