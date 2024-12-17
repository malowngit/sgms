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

// Fetch academic years from the database
$academicYears = [];
$result = $conn->query("SELECT * FROM academic_years");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $academicYears[] = $row;
    }
}

// Handle the form submission for adding academic year
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schoolYear']) && isset($_POST['status'])) {
    $schoolYear = $conn->real_escape_string($_POST['schoolYear']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "INSERT INTO academic_years (school_year, status) 
            VALUES ('$schoolYear', '$status')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "New record added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Make sure to exit after sending the response
}

// Handle the deletion of academic years
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteAcademicYears'])) {
    $idsToDelete = $_POST['deleteAcademicYears'];
    $idsToDelete = array_map('intval', $idsToDelete); // Sanitize IDs
    $idsString = implode(',', $idsToDelete);
    $sql = "DELETE FROM academic_years WHERE id IN ($idsString)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Selected records deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Make sure to exit after sending the response
}

// Handle the edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'editAcademicYear') {
    $id = (int) $_POST['id'];
    $schoolYear = $conn->real_escape_string($_POST['schoolYear']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE academic_years 
            SET school_year = '$schoolYear', status = '$status'
            WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Record updated successfully."]);
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
                    <h1 class="mt-4">School Year</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"></li>
                    </ol>
                    <div id="message" class="alert" style="display: none;"></div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Academic Year Data
                        </div>
                        <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="me-2">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAcademicYear">Add New Record</button>
                            </div>
                            <button type="submit" class="btn btn-danger" form="academicYearDeleteForm">Delete Selected</button>
                        </div>
                            <form id="academicYearDeleteForm" method="POST">
                                <table id="datatablesSimple" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>School Year</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($academicYears as $year): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="deleteAcademicYears[]" value="<?php echo $year['id']; ?>">
                                            </td>
                                            <td><?php echo htmlspecialchars($year['school_year']); ?></td>
                                            <td><?php echo htmlspecialchars($year['status']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $year['id']; ?>" data-schoolyear="<?php echo $year['school_year']; ?>" data-status="<?php echo $year['status']; ?>" data-bs-toggle="modal" data-bs-target="#editAcademicYearModal">Edit</button>
                                            </td>
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
    <div class="modal fade" id="addAcademicYear" tabindex="-1" aria-labelledby="addAcademicYearLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAcademicYearLabel">Add New Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="academicYearForm">
                        <div class="mb-3">
                            <label for="schoolYear" class="form-label">School Year</label>
                            <input type="text" class="form-control" id="schoolYear" placeholder="Enter School Year" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitAcademicYear">Add Record</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal Structure -->
    <div class="modal fade" id="editAcademicYearModal" tabindex="-1" aria-labelledby="editAcademicYearLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAcademicYearLabel">Edit Academic Year</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAcademicYearForm">
                        <input type="hidden" id="editId">
                        <div class ="mb-3">
                            <label for="editSchoolYear" class="form-label">School Year</label>
                            <input type="text" class="form-control" id="editSchoolYear" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-control" id="editStatus" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitEditAcademicYear">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the selected academic years?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/script.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

    <script>
    document.getElementById('submitAcademicYear').addEventListener('click', function() {
        const schoolYear = document.getElementById('schoolYear').value;
        const status = document.getElementById('status').value;

        if (schoolYear && status) {
            $.ajax({
                type: "POST",
                url: "", // The same page to handle the request
                data: { 
                    schoolYear: schoolYear, 
                    status: status 
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    if (result.status === "success") {
                        messageDiv.className = 'alert alert-success';
                        messageDiv.innerHTML = result.message;
                        setTimeout(function() {
                            location.reload(); // Reload the page
                        }, 1000); // 1 second delay
                    } else {
                        messageDiv.className = 'alert alert-danger';
                        messageDiv.innerHTML = result.message;
                    }
                    $('#addAcademicYear').modal('hide');
                    document.getElementById('academicYearForm').reset();
                },
                error: function() {
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Error adding record.';
                }
            });
        } else {
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please fill all fields.';
        }
    });
    
    document.getElementById('academicYearDeleteForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const checkboxes = document.querySelectorAll('input[name="deleteAcademicYears[]"]:checked');
        if (checkboxes.length === 0) {
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please select at least one record to delete.';
            return;
        }

        // Show the custom delete confirmation modal
        $('#deleteConfirmationModal').modal('show');

        // When the user clicks the "Delete" button in the modal
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            // Proceed with AJAX request to delete the selected records
            $.ajax({
                type: "POST",
                url: "", // The same page to handle the request
                data: $('#academicYearDeleteForm').serialize(), // Serialize form data
                success: function(response) {
                    const result = JSON.parse(response);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';

                    if (result.status === "success") {
                        messageDiv.className = 'alert alert-success';
                        messageDiv.innerHTML = result.message;
                        setTimeout(function() {
                            location.reload(); // Reload the page after successful deletion
                        }, 1000);
                    } else {
                        messageDiv.className = 'alert alert-danger';
                        messageDiv.innerHTML = result.message;
                    }

                    // Close the modal after the request
                    $('#deleteConfirmationModal').modal('hide');
                },
                error: function() {
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Error deleting records.';

                    // Close the modal in case of error
                    $('#deleteConfirmationModal').modal('hide');
                }
            });
        });
    });

    $(document).on('click', '.edit-btn', function() {
        // Get the data attributes from the clicked button
        const id = $(this).data('id');
        const schoolYear = $(this).data('schoolyear');
        const status = $(this).data('status');

        // Populate the modal fields with the selected record data
        $('#editId').val(id);
        $('#editSchoolYear').val(schoolYear);
        $('#editStatus').val(status);
    });

    $('#submitEditAcademicYear').on('click', function() {
        const id = $('#editId').val();
        const schoolYear = $('#editSchoolYear').val();
        const status = $('#editStatus').val();

        if (schoolYear && status) {
            $.ajax({
                type: "POST",
                url: "", // The same page to handle the request
                data: {
                    id: id,
                    schoolYear: schoolYear,
                    status: status,
                    action: 'editAcademicYear' // This action is important for the server to know it's an edit
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    if (result.status === "success") {
                        messageDiv.className = 'alert alert-success';
                        messageDiv.innerHTML = result.message;
                        setTimeout(function() {
                            location.reload(); // Reload the page after successful edit
                        }, 1000); // 1 second delay
                    } else {
                        messageDiv.className = 'alert alert-danger';
                        messageDiv.innerHTML = result.message;
                    }
                    $('#editAcademicYearModal').modal('hide');
                },
                error: function() {
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Error editing record.';
                }
            });
        } else {
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please fill all fields.';
        }
    });
    </script>
</body>
</html>