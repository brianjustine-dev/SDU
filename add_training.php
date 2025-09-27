<?php
session_start();
include("db.php");

// Check if the user is logged in and is staff or head
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['staff', 'head'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $completion_date = $_POST['completion_date'];

    // Use a transaction for safety
    $conn->begin_transaction();
    try {
        // Step 1: Insert the training into the 'trainings' table
        $stmt1 = $conn->prepare("INSERT INTO trainings (title, description, training_date) VALUES (?, ?, ?)");
        $stmt1->bind_param("sss", $title, $description, $completion_date);
        $stmt1->execute();
        $training_id = $stmt1->insert_id;
        $stmt1->close();

        // Step 2: Link the user to the training in the 'user_trainings' table
        $stmt2 = $conn->prepare("INSERT INTO user_trainings (user_id, training_id, completion_date) VALUES (?, ?, ?)");
        $stmt2->bind_param("iis", $user_id, $training_id, $completion_date);
        $stmt2->execute();
        $stmt2->close();

        $conn->commit();
        $message = "<div class='alert alert-success'>Training added successfully!</div>";
    } catch (Exception $e) {
        $conn->rollback();
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Training</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #f0f2f5; }
        .container { max-width: 600px; margin-top: 50px; }
        .card { padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2 class="text-center mb-4">Add a New Training</h2>
            <?php echo $message; ?>
            <form action="add_training.php" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Training Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="completion_date" class="form-label">Completion Date</label>
                    <input type="date" class="form-control" id="completion_date" name="completion_date" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Training</button>
            </form>
            <div class="text-center mt-3">
                <a href="staff_dashboard.php?view=training-records">Go back to Training Records</a>
            </div>
        </div>
    </div>
</body>
</html>