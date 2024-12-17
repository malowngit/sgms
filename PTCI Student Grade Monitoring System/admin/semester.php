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

// Fetch semesters from the database
$semesters = [];
$result = $conn->query("SELECT * FROM semesters");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $semesters[] = $row;
    }
}

// Handle the form submission for adding semester
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['semester'])) {
    $semester = $conn->real_escape_string($_POST['semester']);
    $sql = "INSERT INTO semesters (course) VALUES ('$semester')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "New semester added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Make sure to exit after sending the response
}

// Handle the deletion of semesters
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteSemesters'])) {
    $idsToDelete = $_POST['deleteSemesters'];
    $idsToDelete = array_map('intval', $idsToDelete); // Sanitize IDs
    $idsString = implode(',', $idsToDelete);
    $sql = "DELETE FROM semesters WHERE id IN ($idsString)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Selected semesters deleted successfully."]);
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
                    <h1 class="mt-4">Semesters</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"></li>
                    </ol>
                    <div id="message" class="alert" style="display: none;"></div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Semesters
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="me-2">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSemester">Add Semester</button>
                                </div>
                                <button type="button" class="btn btn-danger" id="deleteSelectedButton">Delete Selected</button>
                            </div>
                            <form id="semesterDeleteForm" method="POST">
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Semester</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($semesters as $semester): ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="deleteSemesters[]" value="<?php echo $semester['id']; ?>">
                                                </td>
                                                <td><?php echo htmlspecialchars($semester['course']); ?></td>
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
    <div class="modal fade" id="addSemester" tabindex="-1" aria-labelledby="addSemesterLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSemesterLabel">Add Semester</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="semesterForm">
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="text" class="form-control" id="semester" placeholder="Enter Semester" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitSemester">Add Semester</button>
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
                    Are you sure you want to delete the selected semesters?
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
    document.getElementById('submitSemester').addEventListener('click', function() {
        const semester = document.getElementById('semester').value;

        if (semester) {
            $.ajax({
                type: "POST",
                url: "", // Ensure this points to the correct PHP file
                data: { semester: semester },
                success: function(response) {
                    const result = JSON.parse(response);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    if (result.status === "success") {
                        messageDiv.className = 'alert alert-success';
                        messageDiv.innerHTML = result.message;
                        setTimeout(function() {
                            location.reload(); // Reload the page
                        }, 1000); // 1 seconds delay
                    } else {
                        messageDiv.className = 'alert alert-danger';
                        messageDiv.innerHTML = result.message;
                    }
                    $('#addSemester').modal('hide');
                    document.getElementById('semesterForm').reset();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error adding semester:", textStatus, errorThrown);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Error adding semester.';
                }
            });
        } else {
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please enter a semester.';
        }
    });
    
    // Event listener for the delete button
    document.getElementById('deleteSelectedButton').addEventListener('click', function() {
        // Check if any checkboxes are selected
        const checkboxes = document.querySelectorAll('input[name="deleteSemesters[]"]:checked');
        
        if (checkboxes.length === 0) {
            // Show a warning message if no checkboxes are selected
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please select at least one semester to delete.';
            return; // Exit the function
        }

        // Show the confirmation modal if at least one checkbox is selected
        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm deletion
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        // AJAX request to delete the selected semesters
        $.ajax({
            type: "POST",
            url: "", // Ensure this points to the correct PHP file
            data: $('#semesterDeleteForm').serialize(), // Serialize the form data
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
                console.error("Error deleting semesters:", textStatus, errorThrown);
                const messageDiv = document.getElementById('message');
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = 'Error deleting semesters.';
                messageDiv.style.display = 'block';
                $('#deleteConfirmationModal').modal('hide'); // Hide the modal
            }
        }); 
    });
</script>
</body>
</html>
