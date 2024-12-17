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

// Fetch teacher advisories from the database
$teacherAdvisories = [];
$result = $conn->query("SELECT * FROM teacher_advisories");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teacherAdvisories[] = $row;
    }
}

// Handle the form submission for adding teacher advisory
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['class'], $_POST['student_class'])) {
    $class = $conn->real_escape_string($_POST['class']);
    $studentClass = $conn->real_escape_string($_POST['student_class']);
    $sql = "INSERT INTO teacher_advisories (class, student_class) VALUES ('$class', '$studentClass')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "New teacher advisory added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Make sure to exit after sending the response
}

// Handle the deletion of teacher advisories
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteAdvisories'])) {
    $idsToDelete = $_POST['deleteAdvisories'];
    $idsToDelete = array_map('intval', $idsToDelete); // Sanitize IDs
    $idsString = implode(',', $idsToDelete);
    $sql = "DELETE FROM teacher_advisories WHERE id IN ($idsString)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Selected teacher advisories deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Make sure to exit after sending the response
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
                    <h1 class="mt-4">Teacher Advisory</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"></li>
                    </ol>
                    <div id="message" class="alert" style="display: none;"></div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Teacher Advisory
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="me-2">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAdvisory">Add Teacher Advisory</button>
                                </div>
                                <button type="button" class="btn btn-danger" id="deleteSelectedButton">Delete Selected</button>
                            </div>
                            <form id="advisoryDeleteForm" method="POST ">
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Class</th>
                                            <th>Student Class</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($teacherAdvisories as $advisory): ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="deleteAdvisories[]" value="<?php echo $advisory['id']; ?>">
                                                </td>
                                                <td><?php echo htmlspecialchars($advisory['class']); ?></td>
                                                <td><?php echo htmlspecialchars($advisory['student_class']); ?></td>
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

    <!-- Modal Structure -->
    <div class="modal fade" id="addAdvisory" tabindex="-1" aria-labelledby="addAdvisoryLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAdvisoryLabel">Add Teacher Advisory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="advisoryForm">
                        <div class="mb-3">
                            <label for="class" class="form-label">Class</label>
                            <input type="text" class="form-control" id="class" placeholder="Enter Class" required>
                        </div>
                        <div class="mb-3">
                            <label for="student_class" class="form-label">Student Class</label>
                            <input type="text" class="form-control" id="student_class" placeholder="Enter Student Class" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitAdvisory">Add Teacher Advisory</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the selected teacher advisories?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/script.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

    <script>
    document.getElementById('submitAdvisory').addEventListener('click', function() {
        const classInput = document.getElementById('class').value;
        const studentClassInput = document.getElementById('student_class').value;

        if (classInput && studentClassInput) {
            $.ajax({
                type: "POST",
                url: "", // Ensure this points to the correct PHP file
                data: { class: classInput, student_class: studentClassInput },
                success: function(response) {
                    const result = JSON.parse(response);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    if (result.status === "success") {
                        messageDiv.className = 'alert alert-success';
                        messageDiv.innerHTML = result.message;
                        setTimeout(function() {
                            location.reload(); }, 1000); // 1 second delay
                    } else {
                        messageDiv.className = 'alert alert-danger';
                        messageDiv.innerHTML = result.message;
                    }
                    $('#addAdvisory').modal('hide');
                    document.getElementById('advisoryForm').reset();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error adding teacher advisory:", textStatus, errorThrown);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Error adding teacher advisory.';
                }
            });
        } else {
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please enter both class and student class.';
        }
    });
    
    // Event listener for the delete button
    document.getElementById('deleteSelectedButton').addEventListener('click', function() {
        // Check if any checkboxes are selected
        const checkboxes = document.querySelectorAll('input[name="deleteAdvisories[]"]:checked');
        
        if (checkboxes.length === 0) {
            // Show a warning message if no checkboxes are selected
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please select at least one teacher advisory to delete.';
            return; // Exit the function
        }

        // Show the confirmation modal if at least one checkbox is selected
        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm deletion
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        // AJAX request to delete the selected teacher advisories
        $.ajax({
            type: "POST",
            url: "", // Ensure this points to the correct PHP file
            data: $('#advisoryDeleteForm').serialize(), // Serialize the form data
            success: function(response) {
                const result = JSON.parse(response);
                const messageDiv = document.getElementById('message');

                $('#deleteConfirmationModal').modal('hide'); // Hide the modal

                // Show success or error message
                messageDiv.style.display = 'block';
                messageDiv.className = result.status === "success" ? 'alert alert-success' : 'alert alert-danger';
                messageDiv.innerHTML = result.message;

                // Reload the page after a delay
                setTimeout(function() {
                    location.reload(); // Reload the page to reflect changes
                }, 1000);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error deleting teacher advisories:", textStatus, errorThrown);
                const messageDiv = document.getElementById('message');
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = 'Error deleting teacher advisories.';
                messageDiv.style.display = 'block';
                $('#deleteConfirmationModal').modal('hide'); // Hide the modal
            }
        });
    });
    </script>
</body>
</html>