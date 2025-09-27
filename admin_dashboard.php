<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$view = isset($_GET['view']) ? $_GET['view'] : 'overview';
$admin_username = $_SESSION['username']; // Get the username from the session

// Fetch summary data for the dashboard overview
$total_staff = 0;
$trainings_completed = 0;
$active_programs = 0;

$query_total_staff = "SELECT COUNT(*) AS total FROM users WHERE role IN ('staff', 'head')";
$result_total_staff = $conn->query($query_total_staff);
if ($result_total_staff) {
    $row = $result_total_staff->fetch_assoc();
    $total_staff = $row['total'];
}

// Note: You'll need to create a 'trainings' table and link it to users
// for this count to work. For now, it's a placeholder.
// The code below is a simplified fix to prevent the "table doesn't exist" error.
// For a full solution, you need to create the 'trainings' table.
if ($conn->query("SHOW TABLES LIKE 'trainings'")->num_rows == 1) {
    $query_trainings = "SELECT COUNT(*) AS total FROM trainings";
    $result_trainings = $conn->query($query_trainings);
    if ($result_trainings) {     
        $row = $result_trainings->fetch_assoc();
     $trainings_completed = $row['total'];
    }
}


// Similarly, you'll need a 'programs' or equivalent table
// This is also a placeholder for now.
$active_programs = 3; // Example static value
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
    font-family: 'Montserrat', sans-serif;
    display: flex;
    background-color: #f0f2f5;
    transition: margin-left 0.3s ease-in-out;
}

.main-content {
    flex-grow: 1;
    padding: 2rem;
    transition: margin-left 0.3s ease-in-out;
}

@media (min-width: 992px) {
    .sidebar-lg {
        width: 250px;
        background-color: #1a237e;
        color: white;
        height: 100vh;
        position: fixed;
        padding-top: 2rem;
        transition: width 0.3s ease-in-out;
    }
    .main-content {
        margin-left: 250px;
    }
}

/* Sidebar when toggled (collapsed) */
body.toggled .sidebar-lg {
    width: 80px; /* Reduced width for collapsed state */
    padding-top: 1rem;
    color: white; 
}

body.toggled .sidebar-lg .profile-pic {
    display: none; /* Hide the entire profile-pic div */
}

body.toggled .sidebar-lg .nav-link {
    text-align: center; /* Center icons in collapsed view */
    padding: 12px 0;
    color: white; 
}

body.toggled .sidebar-lg .nav-link i,
body.toggled .sidebar-lg .nav-link span {
    margin: 0; /* Remove any margin for icons */
    color: white;
}

body.toggled .sidebar-lg .nav-link span {
    display: none; /* Hide text in collapsed view */
}

body.toggled .main-content {
    margin-left: 80px; /* New margin to align with collapsed sidebar */
}

/* Also, ensure the toggle button for desktop sidebar is positioned correctly */
.sidebar-lg .sidebar-toggle-btn {
    background-color: transparent;
    border: none;
    color: white; /* Or any color you prefer for the icon */
    font-size: 1.5rem;
    padding: 10px;
    width: 100%;
    text-align: right; /* Align to right for better appearance */
}

/* Style for the sidebar header */
.sidebar-lg .sidebar-header {
    padding: 0 15px; /* Adjust padding as needed */
    height: 60px; /* Or match your desired height */
    display: flex;
    color: white; 
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem; /* Add margin below the header */
}

.sidebar-lg .sidebar-header h5 {
    font-weight: bold;
    color: white;
    margin: 0;
    transition: opacity 0.3s ease-in-out;
}

/* Hide header text when toggled */
body.toggled .sidebar-lg .sidebar-header h5 {
    display: none; /* Hide the text */
}

/* Adjust toggle button when sidebar is collapsed */
body.toggled .sidebar-lg .sidebar-toggle-btn {
    text-align: center; /* Center the icon */
    padding: 10px 0; /* Adjust padding */
}

/* Adjust main-content margin when sidebar is toggled (collapsed) */
body.toggled .main-content {
    margin-left: 80px;
}

/* Ensure the profile pic and text are hidden when toggled */
body.toggled .profile-pic {
    display: none !important; /* Use !important if other rules override */
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
    flex-basis: 30%;
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

.content-box {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    padding: 1.5rem;
}

.content-box h2 {
    margin-top: 0;
    color: #1a237e;
    border-bottom: 2px solid #1a237e;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

th, td {
    text-align: left;
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}
</style>
</head>
<body id="body">

    <div class="offcanvas offcanvas-start bg-dark text-white d-lg-none" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">SDU Admin</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                    <a class="nav-link <?= $view === 'overview' ? 'active' : '' ?>" href="?view=overview">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $view === 'staff-directory' ? 'active' : '' ?>" href="?view=staff-directory">Staff Directory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $view === 'training-records' ? 'active' : '' ?>" href="?view=training-records">Training Records</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $view === 'reports' ? 'active' : '' ?>" href="?view=reports">Reports</a>
                </li>
                <li class="nav-item mt-auto">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>


   <div class="sidebar-lg d-none d-lg-block">
        <div class="sidebar-header d-flex justify-content-between align-items-center mb-4">
            <h5 class="ms-3 me-auto text-white">SDU Admin</h5> 
            <button id="sidebar-toggle" class="btn btn-dark sidebar-toggle-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </button>
        </div>
        
        <div class="profile-pic text-center mb-4">
            <div style="width: 80px; height: 80px; background-color: #ffffffff; border-radius: 50%; margin: 0 auto;"></div>
            <p class="mt-2 text-white">Admin</p>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $view === 'overview' ? 'active' : '' ?>" href="?view=overview">
                    <i class="fas fa-chart-line"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="staff_directory_view.php">
                    <i class="fas fa-users me-2"></i> <span>Staff Directory</span>
                </a>
            </li>
            <li class="nav-item">
        <a class="nav-link" href="staff_report.php">
            <i class="fas fa-file-alt me-2"></i> <span>Staff Report</span>
        </a>
    </li>
            <li class="nav-item">
                <a class="nav-link <?= $view === 'reports' ? 'active' : '' ?>" href="?view=reports">
                    <i class="fas fa-chart-bar"></i> <span>Reports</span>
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="main-content">
        <button class="btn btn-primary d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
        </button>

        <?php if ($view === 'overview'): ?>
            <div class="header">
                <h1>Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h1>
            </div>
            <div class="stats-cards">
                <div class="card">
                    <h3>Total Staff</h3>
                    <p><?php echo $total_staff; ?></p>
                </div>
                <div class="card">
                    <h3>Trainings Completed</h3>
                    <p><?php echo $trainings_completed; ?></p>
                </div>
                <div class="card">
                    <h3>Active Programs</h3>
                    <p><?php echo $active_programs; ?></p>
                </div>
            </div>
            <div class="content-box">
                <h2>Recent Activity</h2>
                <p>Content for recent activity will go here.</p>
            </div>
        <?php elseif ($view === 'staff-directory'): ?>
            <div class="content-box">
                <h2>Staff Directory</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Program</th>
                            <th>Job Function</th>
                            <th>Office</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_staff = "SELECT * FROM users WHERE role IN ('staff', 'head')";
                        $result_staff = $conn->query($query_staff);
                        if ($result_staff->num_rows > 0) {
                            while ($staff = $result_staff->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($staff['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($staff['position']) . "</td>";
                                echo "<td>" . htmlspecialchars($staff['program']) . "</td>";
                                echo "<td>" . htmlspecialchars($staff['job_function']) . "</td>";
                                echo "<td>" . htmlspecialchars($staff['office']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No staff members found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($view === 'training-records'): ?>
            <div class="content-box">
                <h2>Training Records</h2>
                <p>This section will show a list of trainings and the staff who attended them. You'll need to create a `trainings` table and a many-to-many relationship table (`user_trainings`) to track this data.</p>
            </div>
        <?php elseif ($view === 'reports'): ?>
            <div class="content-box">
                <h2>Bi-Yearly Summary Reports</h2>
                <p>This section will generate and display summary reports on training participation and competencies. This will require more complex SQL queries to aggregate data over time.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarToggleBtn = document.getElementById('sidebar-toggle');
            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function() {
                    const body = document.getElementById('body');
                    body.classList.toggle('toggled');
                });
            }
        });
    </script>
</body>
</html>
