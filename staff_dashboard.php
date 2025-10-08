<?php
session_start();
include("db.php");

// Check if the user is logged in and is either 'staff' or 'head'
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['staff', 'head'])) {
    header("Location: login.php");
    exit();
}

$view = isset($_GET['view']) ? $_GET['view'] : 'overview';
$staff_username = $_SESSION['username']; // Get the username from the session

// Fetch user-specific data for the dashboard overview
$user_id = $_SESSION['user_id'];
$trainings_completed = 0;

// You'll need a 'user_trainings' table to store which trainings a user has completed
$query_trainings = "SELECT COUNT(*) AS total FROM user_trainings WHERE user_id = ?";
$stmt = $conn->prepare($query_trainings);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_trainings = $stmt->get_result();
if ($result_trainings) {
    $row = $result_trainings->fetch_assoc();
    $trainings_completed = $row['total'];
}
$stmt->close();

if ($view === 'training-records') {
    $query_records = "SELECT t.title, ut.completion_date FROM user_trainings ut JOIN trainings t ON ut.training_id = t.id WHERE ut.user_id = ?";
    $stmt_records = $conn->prepare($query_records);
    $stmt_records->bind_param("i", $user_id);
    $stmt_records->execute();
    $result_records = $stmt_records->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            background-color: #f0f2f5;
        }
        .main-content {
            flex-grow: 1;
            padding: 2rem;
            margin-left: 250px; /* Aligns with the sidebar width */
        }
        .sidebar {
            width: 250px;
            background-color: #1a237e;
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 2rem;
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            margin: 5px 15px;
            transition: background-color 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #3f51b5;
        }
        .content-box {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 1.5rem;
        }
        .stats-cards {
            display: flex;
            justify-content: space-around;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 1.5rem;
            text-align: center;
            flex-basis: 45%;
        }
        .card h3 {
            margin: 0 0 0.5rem;
            color: #1a237e;
        }
        .card p {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3 class="text-center mb-4">Staff Dashboard</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $view === 'overview' ? 'active' : '' ?>" href="?view=overview">
                    <i class="fas fa-chart-line me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $view === 'training-records' ? 'active' : '' ?>" href="?view=training-records">
                    <i class="fas fa-book-open me-2"></i>Training Records
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="edit_profile.php">
                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <?php if ($view === 'overview'): ?>
            <div class="header">
                <h1>Welcome, <?php echo htmlspecialchars($staff_username); ?>!</h1>
                <p>Here you can view your personal overview and progress.</p>
            </div>
            <div class="stats-cards">
                <div class="card">
                    <h3>Trainings Completed</h3>
                    <p><?php echo $trainings_completed; ?></p>
                </div>
                <div class="card">
                    <h3>Upcoming Trainings</h3>
                    <p>0</p>
                </div>
            </div>
            <div class="content-box mt-4">
                <h2>Recent Activity</h2>
                <p>Your recent activities will be displayed here.</p>
            </div>
      <?php elseif ($view === 'training-records'): ?>
    <div class="content-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>My Training Records</h2>
            <a href="add_training.php" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Add Training
            </a>
        </div>
        <?php if ($result_records && $result_records->num_rows > 0): ?>
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th scope="col">Training Title</th>
                        <th scope="col">Completion Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_records->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['completion_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info mt-4" role="alert">
                You have not completed any trainings yet.
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>