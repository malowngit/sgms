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

// Fetch subjects from the database
$subjects = [];
$result = $conn->query("SELECT * FROM subjects");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

// Handle the form submission for adding subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subject_code']) && isset($_POST['subject']) && isset($_POST['units'])) {
    $subject_code = $conn->real_escape_string($_POST['subject_code']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $units = (int)$_POST['units']; // Cast to integer for safety
    $sql = "INSERT INTO subjects (subject_code, name, units) VALUES ('$subject_code', '$subject', $units)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "New subject added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Make sure to exit after sending the response
}

// Handle the deletion of subjects
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteSubjects'])) {
    $idsToDelete = $_POST['deleteSubjects'];
    $idsToDelete = array_map('intval', $idsToDelete); // Sanitize IDs
    $idsString = implode(',', $idsToDelete);
    $sql = "DELETE FROM subjects WHERE id IN ($idsString)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Selected subjects deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Make sure to exit after sending the response
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editSubject'])) {
    // Retrieve the data sent via AJAX
    $subjectId = intval($_POST['subjectId']);
    $subjectCode = $conn->real_escape_string($_POST['subjectCode']);
    $subjectName = $conn->real_escape_string($_POST['subjectName']);
    $units = intval($_POST['units']); // Ensure it's an integer

    // SQL query to update the subject
    $sql = "UPDATE subjects SET subject_code = '$subjectCode', name = '$subjectName', units = $units WHERE id = $subjectId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Subject updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Stop further processing after responding
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
                    <h1 class="mt-4">Subjects</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"></li>
                    </ol>
                    <div id="message" class="alert" style="display: none;"></div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Subjects
                        </div>
                        <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="me-2">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSubject">Add Subject</button>
                            </div>
                            <button type="button" class="btn btn-danger" id="deleteSelectedButton">Delete Selected </button>
                        </div>
                        <form id="subjectDeleteForm" method="POST">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Subject Code</th>
                                    <th>Subject</th>
                                    <th>Units</th>
                                    <th>Actions</th> <!-- Add this header for actions -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subjects as $subject): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="deleteSubjects[]" value="<?php echo $subject['id']; ?>">
                                        </td>
                                        <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['name']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['units']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm editSubject" data-id="<?php echo $subject['id']; ?>" data-code="<?php echo htmlspecialchars($subject['subject_code']); ?>" data-name="<?php echo htmlspecialchars($subject['name']); ?>" data-units="<?php echo htmlspecialchars($subject['units']); ?>">Edit</button>
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
    <div class="modal fade" id="addSubject" tabindex="-1" aria-labelledby="addSubjectLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectLabel">Add Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="subjectForm">
                        <div class="mb-3">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subject_code" placeholder="Enter Subject Code" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" placeholder="Enter Subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="units" class="form-label">Units</label>
                            <input type="number" class="form-control" id="units" placeholder="Enter Units" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitSubject">Add Subject</button>
                </div>
            </div>
        </div>
    </div>

<!-- Edit Subject Modal -->
<div class="modal fade" id="editSubject" tabindex="-1" aria-labelledby="editSubjectLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubjectLabel">Edit Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSubjectForm">
                    <input type="hidden" id="editSubjectId">
                    <div class="mb-3">
                        <label for="editSubjectCode" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="editSubjectCode" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSubjectName" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="editSubjectName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUnits" class="form-label">Units</label>
                        <input type="number" class="form-control" id="editUnits" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitEditSubject">Save Changes</button>
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
                    Are you sure you want to delete the selected subjects?
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
    document.getElementById('submitSubject').addEventListener('click', function() {
        const subject_code = document.getElementById('subject_code').value;
        const subject = document.getElementById('subject').value;
        const units = document.getElementById('units').value;

        if (subject_code && subject && units) {
            $.ajax({
                type: "POST",
                url: "", // Ensure this points to the correct PHP file
                data: { subject_code: subject_code, subject: subject, units: units },
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
                    $('#addSubject').modal('hide');
                    document.getElementById('subjectForm').reset();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error adding subject:", textStatus, errorThrown);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Error adding subject.';
                }
            });
        } else {
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please enter a subject code, subject, and units.';
        }
    });
    
    // Event listener for the delete button
    document.getElementById('deleteSelectedButton').addEventListener('click', function() {
        // Check if any checkboxes are selected
        const checkboxes = document.querySelectorAll('input[name="deleteSubjects[]"]:checked');
        
        if (checkboxes.length === 0) {
            // Show a warning message if no checkboxes are selected
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please select at least one subject to delete.';
            return; // Exit the function
        }

        // Show the confirmation modal if at least one checkbox is selected
        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm deletion
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        // AJAX request to delete the selected subjects
        $.ajax({
            type: "POST",
            url: "", // Ensure this points to the correct PHP file
            data: $('#subjectDeleteForm').serialize(), // Serialize the form data
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
                console.error("Error deleting subjects:", textStatus, errorThrown);
                const messageDiv = document.getElementById('message');
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = 'Error deleting subjects.';
                messageDiv.style.display = 'block';
                $('#deleteConfirmationModal').modal('hide'); // Hide the modal
            }
        });
    });
    // Open edit modal and populate fields
$(document).on('click', '.editSubject', function() {
    const subjectId = $(this).data('id');
    const subjectCode = $(this).data('code');
    const subjectName = $(this).data('name');
    const units = $(this).data('units');

    $('#editSubjectId').val(subjectId);
    $('#editSubjectCode').val(subjectCode);
    $('#editSubjectName').val(subjectName);
    $('#editUnits').val(units);

    $('#editSubject').modal('show');
});

// Handle the edit subject submission
$('#submitEditSubject').on('click', function() {
    const subjectId = $('#editSubjectId').val();
    const subjectCode = $('#editSubjectCode').val();
    const subjectName = $('#editSubjectName').val();
    const units = $('#editUnits').val();

    if (subjectId && subjectCode && subjectName && units) {
        $.ajax({
            type: "POST",
            url: "", // Ensure this is the correct file path
            data: { editSubject: true, subjectId: subjectId, subjectCode: subjectCode, subjectName: subjectName, units: units },
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
                $('#editSubject').modal('hide');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error editing subject:", textStatus, errorThrown);
                console.error("Response text:", jqXHR.responseText); // Log the response text for debugging
                const messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = 'Error editing subject: ' + jqXHR.responseText; // Show the response text
            }
        });
    } else {
        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert alert-warning';
        messageDiv.innerHTML = 'Please fill in all fields.';
    }
});

    </script>
</body>
</html>