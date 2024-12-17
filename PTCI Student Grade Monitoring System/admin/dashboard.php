<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:adminlogin.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sgms_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch counts from the database
$studentsCount = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$teachersCount = $conn->query("SELECT COUNT(*) as count FROM teachers")->fetch_assoc()['count'];
$classesCount = $conn->query("SELECT COUNT(*) as count FROM classes")->fetch_assoc()['count'];
$departmentsCount = $conn->query("SELECT COUNT(*) as count FROM departments")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Remove the underline from links */
        a {
            text-decoration: none;
        }
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05); /* Slight zoom effect on hover */
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <?php include 'includes/topnavbar.php'; ?>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <?php include 'includes/sidenavbar.php' ?>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Admin's Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol> 
                    <div class="row">
                        <!-- Students Card (Clickable) -->
                        <div class="col-xl-3 col-md-6">
                            <a href="studentlist.php">
                                <div class="card text-white mb-4" style="background-color: #007bff; height: 200px;">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-user-graduate fa-9x"></i>
                                        </div>
                                        <div class="text-right">
                                            <h2 class="mb-0"><?php echo $studentsCount; ?></h2>
                                            <p>Students</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Teachers Card (Clickable) -->
                        <div class="col-xl-3 col-md-6">
                            <a href="teacherlist.php">
                                <div class="card text-white mb-4" style="background-color: #28a745; height: 200px;">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-chalkboard-teacher fa-9x"></i>
                                        </div>
                                        <div class="text-right">
                                            <h2 class="mb-0"><?php echo $teachersCount; ?></h2>
                                            <p>Teachers</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Subjects Card (Clickable) -->
                        <div class="col-xl-3 col-md-6">
                            <a href="classes.php">
                                <div class="card text-white mb-4" style="background-color: #ffc107; height: 200px;">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-book fa-9x"></i>
                                        </div>
                                        <div class="text-right">
                                            <h2 class="mb-0"><?php echo $classesCount; ?></h2>
                                            <p>Classes</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Departments Card (Clickable) -->
                        <div class="col-xl-3 col-md-6">
                            <a href="department.php">
                                <div class="card text-white mb-4" style="background-color: #17a2b8; height: 200px;">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-building fa-9x"></i>
                                        </div>
                                        <div class="text-right">
                                            <h2 class="mb-0"><?php echo $departmentsCount; ?></h2>
                                            <p>Departments</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <?php include 'includes/footer.php' ?>
            </footer>
        </div>
    </div>
    <?php include 'includes/script.php' ?>
</body>
</html> 
