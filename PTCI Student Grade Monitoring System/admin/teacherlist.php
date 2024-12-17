<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:adminlogin.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sgms_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch teachers from the database
$teachers = [];
$result = $conn->query("SELECT * FROM teachers");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

// Handle the form submission for adding teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['teacherid'])) {
    $teacherid = $conn->real_escape_string($_POST['teacherid']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Prepare the SQL query to insert the new teacher
    $sql = "INSERT INTO teachers (teacherid, name, email, password) 
            VALUES ('$teacherid', '$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Return success response if insertion is successful
        echo json_encode(["status" => "success", "message" => "New teacher added successfully."]);
    } else {
        // Return error message if insertion fails
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Ensure that the script exits after the response is sent
}

// Handle the form submission for editing teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editteacherId'])) {
    $editteacherId = intval($_POST['editteacherId']);
    $teacherid = $conn->real_escape_string($_POST['editteacherid']);
    $name = $conn->real_escape_string($_POST['editName']);
    $email = $conn->real_escape_string($_POST['editEmail']);
    $password = $_POST['editPassword'] ? password_hash($_POST['editPassword'], PASSWORD_DEFAULT) : null; // Hash the password if provided

    // Prepare the SQL query to update the teacher
    $sql = "UPDATE teachers SET teacherid='$teacherid', name='$name', email='$email'";

    if ($password) {
        $sql .= ", password='$password'"; // Update password only if provided
    }

    $sql .= " WHERE id=$editteacherId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "teacher updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Ensure that the script exits after the response is sent
}

// Handle the deletion of teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteteacher'])) {
    $idsToDelete = $_POST['deleteteacher'];
    
    // Sanitize the input by ensuring the IDs are integers
    $idsToDelete = array_map('intval', $idsToDelete);
    $idsString = implode(',', $idsToDelete);

    // Construct the SQL query to delete the teachers
    $sql = "DELETE FROM teachers WHERE id IN ($idsString)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Selected teacher(s) deleted successfully."]);
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap /5.1.3/css/bootstrap.min.css" rel="stylesheet">
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
                    <h1 class="mt-4">Teachers</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"></li>
                    </ol>
                    <div id="message" class="alert" style="display: none;"></div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Teachers
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="me-2">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addteacher">Add teacher</button>
                                </div>
                                <button type="button" class="btn btn-danger" id="deleteSelectedButton">Delete Selected</button>
                            </div>
                            <form id="teacherDeleteForm" method="POST">
                                <table id="datatablesSimple" class="table">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Teacher ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($teachers as $teacher): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="deleteteacher[]" value="<?php echo $teacher['id']; ?>">
                                            </td>
                                            <td><?php echo htmlspecialchars($teacher['teacherid']); ?></td>
                                            <td><?php echo htmlspecialchars($teacher['name']); ?></td>
                                            <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm editteacherButton" data-id="<?php echo $teacher['id']; ?>" data-teacherid="<?php echo htmlspecialchars($teacher['teacherid']); ?>" data-name="<?php echo htmlspecialchars($teacher['name']); ?>" data-email="<?php echo htmlspecialchars($teacher['email']); ?>">Edit</button>
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
<div class="modal fade" id="addteacher" tabindex="-1" aria-labelledby="addteacherLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addteacherLabel">Add Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="teacherForm">
                    <div class="mb-3">
                        <label for="teacherid" class="form-label">Teacher ID</label>
                        <input type="text" class="form-control" id="teacherid" placeholder="Enter Teacher ID" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter Password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitteacher">Add teacher</button>
            </div>
        </div>
    </div>
</div>


    <!-- Edit teacher Modal -->
    <div class="modal fade" id="editteacher" tabindex="-1" aria-labelledby="editteacherLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editteacherLabel">Edit teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editteacherForm">
                        <input type="hidden" id="editteacherId" />
                        <div class="mb-3">
                            <label for="editteacherid" class="form-label">Teacher ID</label>
                            <input type="text" class="form-control" id="editteacherid" placeholder="Enter Teacher ID">
                        </div>
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" placeholder="Enter Name">
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" placeholder="Enter Email">
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="editPassword" placeholder="Enter New Password (leave blank to keep current)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitEditteacher">Save Changes</button>
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
                    Are you sure you want to delete the selected teacher?
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
    document.getElementById('submitteacher').addEventListener('click', function() {
    const teacherid = document.getElementById('teacherid').value;
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Validate input
    if (teacherid && name && email && password) {
        // Send AJAX request to PHP
        $.ajax({
            type: "POST",
            url: "", // This is the same page where the PHP is processed
            data: {
                teacherid: teacherid,
                name: name,
                email: email,
                password: password
            },
            success: function(response) {
                const result = JSON.parse(response); // Parse the JSON response
                const messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                if (result.status === "success") {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.innerHTML = result.message;
                    setTimeout(function () {
                        location.reload(); // Reload the page to show the newly added teacher
                    }, 1000); // Delay 1 second before reload
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = result.message;
                }
                $('#addteacher').modal('hide'); // Hide the modal after submission
                document.getElementById('teacherForm').reset(); // Reset the form
            },
            error: function (jqXHR, textStatus, errorThrown) {
                const messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = 'Error adding teacher.';
                console.error("Error adding teacher:", textStatus, errorThrown);
            }
        });
    } else {
        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert alert-warning';
        messageDiv.innerHTML = 'Please fill in all fields.';
    }
});
// Event listener for edit buttons
document.querySelectorAll('.editteacherButton').forEach(button => {
    button.addEventListener('click', function () {
        const teacherId = this.getAttribute('data-id');
        const teacherid = this.getAttribute('data-teacherid');
        const name = this.getAttribute('data-name');
        const email = this.getAttribute('data-email');

        // Pre-fill the edit modal fields
        document.getElementById('editteacherId').value = teacherId;
        document.getElementById('editteacherid').value = teacherid;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;

        // Show the edit modal
        $('#editteacher').modal('show');
    });
});

// Handle the edit form submission
document.getElementById('submitEditteacher').addEventListener('click', function () {
    const editteacherId = document.getElementById('editteacherId').value;
    const editteacherid = document.getElementById('editteacherid').value;
    const editName = document.getElementById('editName').value;
    const editEmail = document.getElementById('editEmail').value;
    const editPassword = document.getElementById('editPassword').value;

    // Validate input
    if (editteacherid && editName && editEmail) {
        // Send AJAX request to PHP
        $.ajax({
            type: "POST",
            url: "", // This is the same page where the PHP is processed
            data: {
                editteacherId: editteacherId,
                editteacherid: editteacherid,
                editName: editName,
                editEmail: editEmail,
                editPassword: editPassword
            },
            success: function (response) {
                const result = JSON.parse(response); // Parse the JSON response
                const messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                if (result.status === "success") {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.innerHTML = result.message;
                    setTimeout(function () {
                        location.reload(); // Reload the page to show the updated teacher
                    }, 1000); // Delay 1 second before reload
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = result.message;
                }
                $('#editteacher').modal('hide'); // Hide the modal after submission
            },
            error: function (jqXHR, textStatus, errorThrown) {
                const messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = 'Error updating teacher.';
                console.error("Error updating teacher:", textStatus, errorThrown);
            }
        }); 
    } else {
        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert alert-warning';
        messageDiv.innerHTML = 'Please fill in all fields.';
    }
});

   // Event listener for the delete button
   document.getElementById('deleteSelectedButton').addEventListener('click', function () {
    const checkboxes = document.querySelectorAll('input[name="deleteteacher[]"]:checked');
    if (checkboxes.length === 0) {
        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert alert-warning';
        messageDiv.innerHTML = 'Please select at least one teacher to delete.';
        return;
    }
    $('#deleteConfirmationModal').modal('show');
});


    // Confirm deletion
    document.getElementById('confirmDeleteButton').addEventListener('click', function () {
    const selectedTeachers = [];
    document.querySelectorAll('input[name="deleteteacher[]"]:checked').forEach((checkbox) => {
        selectedTeachers.push(checkbox.value);
    });

    if (selectedTeachers.length === 0) {
        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert alert-warning';
        messageDiv.innerHTML = 'Please select at least one teacher to delete.';
        return;
    }

    $.ajax({
        type: "POST",
        url: "",  // Your current page, where the PHP logic is implemented
        data: { deleteteacher: selectedTeachers },  // Send selected teacher IDs
        success: function (response) {
            const result = JSON.parse(response);
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            if (result.status === "success") {
                messageDiv.className = 'alert alert-success';
                messageDiv.innerHTML = result.message;
                setTimeout(function () {
                    location.reload();  // Reload the page to reflect changes
                }, 1000);
            } else {
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = result.message;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error deleting teacher:", textStatus, errorThrown);
            const messageDiv = document.getElementById('message');
            messageDiv.className = 'alert alert-danger';
            messageDiv.innerHTML = 'Error deleting teacher.';
            messageDiv.style.display = 'block';
            $('#deleteConfirmationModal').modal('hide');
        }
    });
});

    </script>
</body>
</html>