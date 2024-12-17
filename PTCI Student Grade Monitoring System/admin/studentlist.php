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

// Fetch students from the database
$students = [];
$result = $conn->query("SELECT s.id, s.studentid, s.fullname, ay.school_year AS academic_year, yl.level AS year_level, d.course AS department 
FROM students s 
JOIN academic_years ay ON s.academic_year = ay.id 
JOIN year_levels yl ON s.year_level = yl.id 
JOIN departments d ON s.department = d.id");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Fetch academic years, year levels, and departments from the database
$academic_years = [];
$year_levels = [];
$departments = [];

// Fetch academic years
$result = $conn->query("SELECT * FROM academic_years");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $academic_years[] = $row;
    }
}

// Fetch year levels
$result = $conn->query("SELECT * FROM year_levels");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $year_levels[] = $row;
    }
}

// Fetch departments
$result = $conn->query("SELECT * FROM departments");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// Handle the form submission for adding student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['studentid']) && isset($_POST['fullname']) && isset($_POST['password']) && isset($_POST['academic_year']) && isset($_POST['year_level']) && isset($_POST['department'])) {
    $studentid = $conn->real_escape_string($_POST['studentid']);
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $password = $conn->real_escape_string($_POST['password']);
    $academic_year = $conn->real_escape_string($_POST['academic_year']);
    $year_level = $conn->real_escape_string($_POST['year_level']);
    $department = $conn->real_escape_string($_POST['department']);
    
    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO students (studentid, fullname, password, academic_year, year_level, department) VALUES ('$studentid', '$fullname', '$hashedPassword', '$academic_year', '$year_level', '$department')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "New student added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit;
}

// Handle the deletion of students
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteStudents'])) {
    $idsToDelete = $_POST['deleteStudents'];
    $idsToDelete = array_map('intval', $idsToDelete); // Sanitize IDs
    $idsString = implode(',', $idsToDelete);
    $sql = "DELETE FROM students WHERE id IN ($idsString)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Selected students deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Ensure the script stops here
}

// Handle the editing of student data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editStudent'])) {
    $studentId = intval($_POST['studentId']);
    $studentIdNew = $conn->real_escape_string($_POST['studentIdNew']);
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $academic_year = $conn->real_escape_string($_POST['academic_year']);
    $year_level = $conn->real_escape_string($_POST['year_level']);
    $department = $conn->real_escape_string($_POST['department']);
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // If a new password is provided , hash it
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE students SET studentid = '$studentIdNew', fullname = '$fullname', password = '$hashedPassword', academic_year = '$academic_year', year_level = '$year_level', department = '$department' WHERE id = $studentId";
    } else {
        $sql = "UPDATE students SET studentid = '$studentIdNew', fullname = '$fullname', academic_year = '$academic_year', year_level = '$year_level', department = '$department' WHERE id = $studentId";
    }

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Student updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit;
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
                    <h1 class="mt-4">Students</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"></li>
                    </ol>
                    <div id="message" class="alert" style="display: none;"></div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Students
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="me-2">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStudent">Add Student</button>
                                </div>
                                <button type="button" class="btn btn-danger" id="deleteSelectedButton">Delete Selected</button>
                            </div>
                            <form id="studentDeleteForm" method="POST">
                            <table id="datatablesSimple" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Student ID</th>
                                        <th>Full Name</th>
                                        <th>Academic Year</th>
                                        <th>Year Level</th>
                                        <th>Department</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
    <?php foreach ($students as $student): ?>
        <tr>
            <td>
                <input type="checkbox" name="deleteStudents[]" value="<?php echo $student['id']; ?>">
            </td>
            <td><?php echo htmlspecialchars($student['studentid']); ?></td> <!-- This should be the correct field -->
            <td><?php echo htmlspecialchars($student['fullname']); ?></td>
            <td><?php echo htmlspecialchars($student['academic_year']); ?></td>
            <td><?php echo htmlspecialchars($student['year_level']); ?></td>
            <td><?php echo htmlspecialchars($student['department']); ?></td>
            <td>
                <button type="button" class="btn btn-warning btn-sm editStudent" data-id="<?php echo $student['id']; ?>" data-studentid="<?php echo htmlspecialchars($student['studentid']); ?>" data-fullname="<?php echo htmlspecialchars($student['fullname']); ?>" data-academic_year="<?php echo htmlspecialchars($student['academic_year']); ?>" data-year_level="<?php echo htmlspecialchars($student['year_level']); ?>" data-department="<?php echo htmlspecialchars($student['department']); ?>">Edit</button>
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

    <!-- Modal for Add Student -->
<div class="modal fade" id="addStudent" tabindex="-1" aria-labelledby=" addStudentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentLabel">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="studentForm">
                    <div class="mb-3">
                        <label for="studentid" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="studentid" placeholder="Enter Student ID" required>
                    </div>
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullname" placeholder="Enter Full Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter Password" required>
                    </div>
                    <div class="mb-3">
                        <label for="academic_year" class="form-label">Academic Year</label>
                        <select class="form-control" id="academic_year" required>
                            <option value="">Select Academic Year</option>
                            <?php foreach ($academic_years as $year): ?>
                                <option value="<?php echo htmlspecialchars($year['id']); ?>"><?php echo htmlspecialchars($year['school_year']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="year_level" class="form-label">Year Level</label>
                        <select class="form-control" id="year_level" required>
                            <option value="">Select Year Level</option>
                            <?php foreach ($year_levels as $level): ?>
                                <option value="<?php echo htmlspecialchars($level['id']); ?>"><?php echo htmlspecialchars($level['level']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-control" id="department" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo htmlspecialchars($department['id']); ?>"><?php echo htmlspecialchars($department['course']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitStudent">Add Student</button>
            </div>
        </div>
    </div>
</div>

    <!-- Edit Student Modal -->
<div class="modal fade" id="editStudent" tabindex="-1" aria-labelledby="editStudentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentLabel">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStudentForm">
                    <input type="hidden" id="editStudentId">
                    <div class="mb-3">
                        <label for="editStudentIdNew" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="editStudentIdNew" required>
                    </div>
                    <div class="mb-3">
                        <label for="editFullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="editFullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAcademicYear" class="form-label">Academic Year</label>
                        <select class="form-control" id="editAcademicYear" required>
                            <option value="">Select Academic Year</option>
                            <?php foreach ($academic_years as $year): ?>
                                <option value="<?php echo htmlspecialchars($year['id']); ?>"><?php echo htmlspecialchars($year['school_year']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editYearLevel" class="form-label">Year Level</label>
                        <select class="form-control" id="editYearLevel" required>
                            <option value="">Select Year Level</option>
                            <?php foreach ($year_levels as $level): ?>
                                <option value="<?php echo htmlspecialchars($level['id']); ?>"><?php echo htmlspecialchars($level['level']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editDepartment" class="form-label">Department</label>
                        <select class="form-control" id="editDepartment" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo htmlspecialchars($department['id']); ?>"><?php echo htmlspecialchars($department['course']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="editPassword" placeholder="Enter New Password (leave blank to keep current)">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitEditStudent">Save Changes</button>
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
                    Are you sure you want to delete the selected students?
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
    // Add student form submit
    document.getElementById('submitStudent').addEventListener('click', function() {
        const studentid = document.getElementById('studentid').value;
        const fullname = document.getElementById('fullname').value;
        const password = document.getElementById('password').value;
        const academic_year = document.getElementById('academic_year').value;
        const year_level = document.getElementById('year_level').value;
        const department = document.getElementById('department').value;

        if (studentid && fullname && password && academic_year && year_level && department) {
            $.ajax({
                type: "POST",
                url: "", // Ensure this points to the correct PHP file
                data: { studentid: studentid, fullname: fullname, password: password, academic_year: academic_year, year_level: year_level, department: department },
                success: function(response) {
                    const result = JSON.parse(response);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    if (result.status === "success") {
                        messageDiv.className = 'alert alert-success';
                        messageDiv.innerHTML = result.message;
                        setTimeout(function() {
                            location.reload(); // Reload the page
                        }, 1000);
                    } else {
                        messageDiv.className = 'alert alert-danger';
                        messageDiv.innerHTML = result.message;
                    }
                    $('#addStudent').modal('hide');
                    document.getElementById('studentForm').reset();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error adding student:", textStatus, errorThrown);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = ' block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Error adding student.';
                }
            });
        } else {
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please enter all fields.';
        }
    });

    // Delete selected students
    document.getElementById('deleteSelectedButton').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="deleteStudents[]"]:checked');
        
        if (checkboxes.length === 0) {
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please select at least one student to delete.';
            return;
        }

        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm delete
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        $.ajax({
            type: "POST",
            url: "",
            data: $('#studentDeleteForm').serialize(),
            success: function(response) {
                const result = JSON.parse(response);
                const messageDiv = document.getElementById('message');
                $('#deleteConfirmationModal').modal('hide');
                messageDiv.style.display = 'block';
                messageDiv.className = result.status === "success" ? 'alert alert-success' : 'alert alert-danger';
                messageDiv.innerHTML = result.message;
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error deleting students:", textStatus, errorThrown);
                const messageDiv = document.getElementById('message');
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = 'Error deleting students.';
                messageDiv.style.display = 'block';
                $('#deleteConfirmationModal').modal('hide');
            }
        });
    });

    // Open edit modal and populate fields
    $(document).on('click', '.editStudent', function() {
        const studentId = $(this).data('id');
        const studentIdNew = $(this).data('studentid');
        const fullname = $(this).data('fullname');
        const academic_year = $(this).data('academic_year');
        const year_level = $(this).data('year_level');
        const department = $(this).data('department');

        $('#editStudentId').val(studentId);
        $('#editStudentIdNew').val(studentIdNew);
        $('#editFullname').val(fullname);
        $('#editAcademicYear').val(academic_year);
        $('#editYearLevel').val(year_level);
        $('#editDepartment').val(department);

        $('#editStudent').modal('show');
    });

    // Handle edit student
    $('#submitEditStudent').on('click', function() {
        const studentId = $('#editStudentId').val();
        const studentIdNew = $('#editStudentIdNew').val();
        const fullname = $('#editFullname').val();
        const academic_year = $('#editAcademicYear').val();
        const year_level = $('#editYearLevel').val();
        const department = $('#editDepartment').val();
        const password = $('#editPassword').val();

        // If password is not empty, it will be updated
        $.ajax({
            type: "POST",
            url: "", // Ensure this points to the correct PHP file
            data: { 
                editStudent: true, 
                studentId: studentId, 
                studentIdNew: studentIdNew, 
                fullname: fullname, 
                academic_year: academic_year, 
                year_level: year_level, 
                department: department,
                password: password
            },
            success: function(response) {
                const result = JSON.parse(response);
                const messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                if (result.status === "success") {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.innerHTML = result.message;
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = result.message;
                }
                $('#editStudent').modal('hide');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error editing student:", textStatus, errorThrown);
                const messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = 'Error editing student: ' + jqXHR.responseText;
            }
        });
    });
    </script>
</body>
</html>