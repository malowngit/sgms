<?php
session_start();
if ($_SESSION['username']) {
    echo '';
} else {
    header("location:adminlogin.php");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sgms_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch unverified students from the database
$unverified_students = [];
$result = $conn->query("SELECT * FROM unverified_students");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $unverified_students[] = $row;
    }
}

// Initialize success message
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message after displaying
}

// Handle the form submission for adding verified students
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addVerifiedStudents'])) {
    $idsToAdd = $_POST['addVerifiedStudents'];
    $idsToAdd = array_map('intval', $idsToAdd); // Sanitize IDs
    $idsString = implode(',', $idsToAdd);

    // Fetch the unverified students to be added
    $unverifiedQuery = "SELECT * FROM unverified_students WHERE id IN ($idsString)";
    $unverifiedResult = $conn->query($unverifiedQuery);

    if ($unverifiedResult->num_rows > 0) {
        while ($student = $unverifiedResult->fetch_assoc()) {
            // Prepare to insert into the students table
            $studentid = $conn->real_escape_string($student['studentid']);
            $fullname = $conn->real_escape_string($student['fullname']);
            $academic_year = $conn->real_escape_string($student['academic_year']);
            $year_level = $conn->real_escape_string($student['year_level']);
            $department = $conn->real_escape_string($student['department']);

            $sql = "INSERT INTO students (studentid, fullname, academic_year, year_level, department) VALUES ('$studentid', '$fullname', '$academic_year', '$year_level', '$department')";
            $conn->query($sql);
        }

        // Now delete the added students from the unverified table
        $sqlDelete = "DELETE FROM unverified_students WHERE id IN ($idsString)";
        $conn->query($sqlDelete);

        // Set success message in session
        $_SESSION['success_message'] = "Selected students added successfully.";
        echo json_encode(["status" => "success", "message" => "Selected students added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No students found to add."]);
    }
    exit; // Ensure the script stops here
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/header.php'; ?>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <?php include 'includes/topnavbar.php'; ?>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <?php include 'includes/sidenavbar.php'; ?>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Verification List of Students</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"></li>
                    </ol>
                    <div id="message" class="alert" style="display: none;"></div>
                    <div id="successMessage" class="alert alert-success" style="display: <?= !empty($success_message) ? 'block' : 'none'; ?>;">
                        <?= htmlspecialchars($success_message); ?>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Unverified Students
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <button type="button" class="btn btn-success" id="addSelectedButton">Add SelectedStudents</button>
                            </div>
                            <form id="studentAddForm" method="POST">
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Student ID</th>
                                            <th>Full Name</th>
                                            <th>Academic Year</th>
                                            <th>Year Level</th>
                                            <th>Department</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($unverified_students as $student): ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="addVerifiedStudents[]" value="<?php echo $student['id']; ?>">
                                                </td>
                                                <td><?php echo htmlspecialchars($student['studentid']); ?></td>
                                                <td><?php echo htmlspecialchars($student['fullname']); ?></td>
                                                <td><?php echo htmlspecialchars($student['academic_year']); ?></td>
                                                <td><?php echo htmlspecialchars($student['year_level']); ?></td>
                                                <td><?php echo htmlspecialchars($student['department']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <?php include 'includes/footer.php'; ?>
            </footer>
        </div>
    </div>

    <?php include 'includes/script.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

    <script>
    // Add selected unverified students
    document.getElementById('addSelectedButton').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="addVerifiedStudents[]"]:checked');
        
        if (checkboxes.length === 0) {
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please select at least one student to add.';
            return;
        }

        $('#studentAddForm').submit();
    });
    </script>
</body>
</html>