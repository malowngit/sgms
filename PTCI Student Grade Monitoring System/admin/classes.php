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

// Fetch classes from the database with joins to get meaningful data
$classes = [];
$result = $conn->query("SELECT c.id, c.class_code, c.name, c.units, ay.school_year AS academic_year, semester AS semester, yl.level AS year_level 
    FROM classes c 
    JOIN academic_years ay ON c.academic_year_id = ay.id 
    JOIN semesters s ON c.semester_id = s.id 
    JOIN year_levels yl ON c.year_level_id = yl.id
");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}

// Fetch school years, semesters, and year levels
$academic_years = $conn->query("SELECT * FROM academic_years");
$semesters = $conn->query("SELECT * FROM semesters");
$year_levels = $conn->query("SELECT * FROM year_levels");

// Handle the form submission for adding class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['class_code'], $_POST['class_name'], $_POST['units'], $_POST['academic_year_id'], $_POST['semester_id'], $_POST['year_level_id'])) {
    $class_code = $conn->real_escape_string($_POST['class_code']);
    $class_name = $conn->real_escape_string($_POST['class_name']);
    $units = (int)$_POST['units'];
    $academic_year_id = (int)$_POST['academic_year_id'];
    $semester_id = (int)$_POST['semester_id'];
    $year_level_id = (int)$_POST['year_level_id'];

    $sql = "INSERT INTO classes (class_code, name, units, academic_year_id, semester_id, year_level_id) VALUES ('$class_code', '$class_name', $units, $academic_year_id, $semester_id, $year_level_id)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "New class added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Make sure to exit after sending the response
}

// Handle the deletion of classes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteClasses'])) {
    $idsToDelete = $_POST['deleteClasses'];
    $idsToDelete = array_map('intval', $idsToDelete); // Sanitize IDs
    $idsString = implode(',', $idsToDelete);
    $sql = "DELETE FROM classes WHERE id IN ($idsString)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Selected classes deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
    exit; // Make sure to exit after sending the response
}

// Handle the edit class submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editClass'])) {
    $classId = intval($_POST['classId']);
    $classCode = $conn->real_escape_string($_POST['classCode']);
    $className = $conn->real_escape_string($_POST['className']);
    $units = intval($_POST['units']);
    $academic_year_id = (int)$_POST['academic_year_id'];
    $semester_id = (int)$_POST['semester_id'];
    $year_level_id = (int)$_POST['year_level_id'];

    $sql = "UPDATE classes SET class_code = '$classCode', name = '$className', units = $units, academic_year_id = $academic_year_id, semester_id = $semester_id, year_level_id = $year_level_id WHERE id = $classId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Class updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => " Error: " . $conn->error]);
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
                    <h1 class="mt-4">Classes</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"></li>
                    </ol>
                    <div id="message" class="alert" style="display: none;"></div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Classes
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="me-2">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addClass">Add Class</button>
                                </div>
                                <button type="button" class="btn btn-danger" id="deleteSelectedButton">Delete Selected</button>
                            </div>
                            <form id="classDeleteForm" method="POST">
                            <table id="datatablesSimple" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Class Code</th>
                                        <th>Class</th>
                                        <th>Units</th>
                                        <th>School Year</th>
                                        <th>Semester</th>
                                        <th>Year Level</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($classes as $class): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="deleteClasses[]" value="<?php echo $class['id']; ?>">
                                        </td>
                                        <td><?php echo htmlspecialchars($class['class_code']); ?></td>
                                        <td><?php echo htmlspecialchars($class['name']); ?></td>
                                        <td><?php echo htmlspecialchars($class['units']); ?></td>
                                        <td><?php echo htmlspecialchars($class['academic_year']); ?></td> <!-- Displaying the actual school year -->
                                        <td><?php echo htmlspecialchars($class['semester']); ?></td> <!-- Displaying the actual semester -->
                                        <td><?php echo htmlspecialchars($class['year_level']); ?></td> <!-- Displaying the actual year level -->
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm editClass" data-id="<?php echo $class['id']; ?>" data-code="<?php echo htmlspecialchars($class['class_code']); ?>" data-name="<?php echo htmlspecialchars($class['name']); ?>" data-units="<?php echo htmlspecialchars($class['units']); ?>" data-school-year="<?php echo htmlspecialchars($class['academic_year']); ?>" data-semester="<?php echo htmlspecialchars($class['semester']); ?>" data-year-level="<?php echo htmlspecialchars($class['year_level']); ?>">Edit</button>
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
    <div class="modal fade" id="addClass" tabindex="-1" aria-labelledby="addClassLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassLabel">Add Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="classForm">
                        <div class="mb-3">
                            <label for="class_code" class="form-label">Class Code</label>
                            <input type="text" class="form-control" id="class_code" placeholder="Enter Class Code" required>
                        </div>
                        <div class="mb-3">
                            <label for="class_name" class="form-label">Class</label>
                            <input type="text" class="form-control" id="class_name" placeholder="Enter Class" required>
                        </div>
                        <div class="mb-3">
                            <label for="units" class="form-label">Units</label>
                            <input type="number" class="form-control" id="units" placeholder="Enter Units" required>
                        </div>
                        <div class="mb-3">
                            <label for="academic_year_id" class="form-label">School Year</label>
                            <select class="form-control" id="academic_year_id" required>
                                <?php while ($academic_year = $academic_years->fetch_assoc()): ?>
                                    <option value="<?php echo $academic_year['id']; ?>"><?php echo $academic_year['school_year']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semester_id" class="form-label">Semester</label>
                            <select class="form-control" id="semester_id" required>
                                <?php while ($semester = $semesters->fetch_assoc()): ?>
                                    <option value="<?php echo $semester['id']; ?>"><?php echo $semester['course']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="year_level_id" class="form-label">Year Level</label>
                            <select class="form-control" id="year_level_id" required>
                                <?php while ($year_level = $year_levels->fetch_assoc()): ?>
                                    <option value="<?php echo $year_level['id']; ?>"><?php echo $year_level['level']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitClass">Add Class</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Class Modal -->
    <div class="modal fade" id="editClass" tabindex="-1" aria-labelledby="editClassLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassLabel">Edit Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editClassForm">
                        <input type="hidden" id="editClassId">
                        <div class="mb-3">
                            <label for="editClassCode" class="form-label">Class Code</label>
                            <input type="text" class="form-control" id="editClassCode" required>
                        </div>
                        <div class="mb-3">
                            <label for="editClassName" class="form-label">Class</label>
                            <input type="text" class="form-control" id="editClassName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUnits" class="form-label">Units</label>
                            <input type="number" class="form-control" id="editUnits" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSchoolYearId" class="form-label">School Year</label>
                            <select class="form-control" id="editSchoolYearId" required>
                                <?php while ($academic_year = $academic_years->fetch_assoc()): ?>
                                    <option value="<?php echo $academic_year['id']; ?>"><?php echo $academic_year['academic_year']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editSemesterId" class="form-label">Semester</label>
                            <select class="form-control" id="editSemesterId" required>
                                <?php while ($semester = $semesters->fetch_assoc()): ?>
                                    <option value="<?php echo $semester['id']; ?>"><?php echo $semester['semester']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editYearLevelId" class=" form-label">Year Level</label>
                            <select class="form-control" id="editYearLevelId" required>
                                <?php while ($year_level = $year_levels->fetch_assoc()): ?>
                                    <option value="<?php echo $year_level['id']; ?>"><?php echo $year_level['level']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitEditClass">Save Changes</button>
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
                    Are you sure you want to delete the selected classes?
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
    document.getElementById('submitClass').addEventListener('click', function() {
        const class_code = document.getElementById('class_code').value;
        const class_name = document.getElementById('class_name').value;
        const units = document.getElementById('units').value;
        const academic_year_id = document.getElementById('academic_year_id').value;
        const semester_id = document.getElementById('semester_id').value;
        const year_level_id = document.getElementById('year_level_id').value;

        if (class_code && class_name && units && academic_year_id && semester_id && year_level_id) {
            $.ajax({
                type: "POST",
                url: "", // Ensure this points to the correct PHP file
                data: { class_code: class_code, class_name: class_name, units: units, academic_year_id: academic_year_id, semester_id: semester_id, year_level_id: year_level_id },
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
                    $('#addClass').modal('hide');
                    document.getElementById('classForm').reset();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error adding class:", textStatus, errorThrown);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Error adding class.';
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
    document.getElementById('deleteSelectedButton').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="deleteClasses[]"]:checked');
        
        if (checkboxes.length === 0) {
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = 'Please select at least one class to delete.';
            return;
        }

        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm deletion
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        $.ajax({
            type: "POST",
            url: "", // Ensure this points to the correct PHP file
            data: $('#classDeleteForm').serialize(),
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
                console.error("Error deleting classes:", textStatus, errorThrown);
                const messageDiv = document.getElementById('message');
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = 'Error deleting classes.';
                messageDiv.style.display = 'block';
                $('#deleteConfirmationModal').modal('hide');
            }
        });
    });

    // Open edit modal and populate fields
    $(document).on('click', '.editClass', function() {
        const classId = $(this).data('id');
        const classCode = $(this).data('code');
        const className = $(this).data('name');
        const units = $(this).data('units');
        const schoolYear = $(this).data('school-year');
        const semester = $(this).data('semester');
        const yearLevel = $(this).data('year-level');

        $('#editClassId').val(classId);
        $('#editClassCode').val(classCode);
        $('#editClassName').val(className);
        $('#editUnits').val(units);
        $('#editSchoolYearId').val(schoolYear); // Ensure this is the ID
        $('#editSemesterId').val(semester); // Ensure this is the ID
        $('#editYearLevelId').val(yearLevel); // Ensure this is the ID

        $('#editClass').modal('show');
    });

    // Handle the edit class submission
    $('#submitEditClass').on('click', function() {
        const classId = $('#editClassId').val();
        const classCode = $('#editClassCode').val();
        const className = $('#editClassName').val();
        const units = $('#editUnits').val();
        const academic_year_id = $('#editSchoolYearId').val();
        const semester_id = $('#editSemesterId').val();
        const year_level_id = $('#editYearLevelId').val();

        if (classId && classCode && className && units && academic_year_id && semester_id && year_level_id) {
            $.ajax({
                type: "POST",
                url: "", // Ensure this is the correct file path
                data: { editClass: true, classId: classId, classCode: classCode, className: className, units: units, academic_year_id: academic_year_id, semester_id: semester_id, year_level_id: year_level_id },
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
                    $('#editClass').modal('hide');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error editing class:", textStatus, errorThrown);
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Error editing class: ' + jqXHR.responseText;
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