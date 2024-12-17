<?php
include '../conn/connection.php';

$error = [];

// Fetch academic years
$academic_years = [];
$stmt = $conn->prepare("SELECT * FROM academic_years");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $academic_years[] = $row;
}

// Fetch year levels
$year_levels = [];
$stmt = $conn->prepare("SELECT * FROM year_levels");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $year_levels[] = $row;
}

// Fetch departments
$departments = [];
$stmt = $conn->prepare("SELECT * FROM departments");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentid = $_POST['studentid'];
    $fullname = $_POST['fullname'];
    $academic_year = $_POST['academic_year'];
    $year_level = $_POST['year_level'];
    $department = $_POST['department'];
    $password = $_POST['password'];

    // Validate input fields
    if (empty($studentid) || empty($fullname) || empty($academic_year) || empty($year_level) || empty($department) || empty($password)) {
        $error[] = "All fields are required.";
    }

    // Check if student ID is already registered
    $stmt = $conn->prepare("SELECT * FROM unverified_students WHERE studentid = ?");
    $stmt->bind_param("s", $studentid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error[] = "Student ID is already registered.";
    }

    if (empty($error)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into unverified_students table
        $stmt = $conn->prepare("INSERT INTO unverified_students (studentid, fullname, academic_year, year_level, department, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiis", $studentid, $fullname, $academic_year, $year_level, $department, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You will be verified soon.'); window.location.href='studentlogin.php';</script>";
        } else {
            $error[] = "Registration failed. Please try again.";
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .register-container {
            background-color: rgba(255, 255, 255, 0.8); 
            padding: 30px; 
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0 0, 0); 
            width: 100%;
        }
        
        .register-container form .error-msg {
            margin: 10px;
            display: block;
            background-color: red;
            color: white;
            border-radius: 5px;
            font-size: 20px;
        }
        
        .logo {
            width: 100px;
            margin: 20px auto ;
            display: block;
        }
        
        .btn {
            margin-bottom: 10px;
        }
        
        .btn-primary {
            font-size: large;
            background-color: #24ce48;
            border-color: #24ce48;
            color: #fff;
        }
        
        .btn-secondary {
            font-size: large;
            background-color: #000000;
            border-color: #000000;
        }
        
        .btn:hover {
            background-color: #04614C;
        }

        .backcolor {
            background-color: lightgrey;
        }
    </style>
</head>
<body>
    <img src=" " style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; object-fit: cover; z-index: -1;" class="backcolor"> 
    <div class="container d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="register-container text-center">
                <img src="../img/ptci.png" alt="Logo" class="logo"> 
                <h2 class="text-center">Register</h2>
                <form action="" method="POST">
                    <hr class="divider" />
                    <div class="form-group">
                        <input type="text" name="studentid" class="form-control" placeholder="Student ID" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <select name="academic_year" class="form-control" required>
                            <option value="">Select Academic Year</option>
                            <?php foreach ($academic_years as $year): ?>
                                <option value="<?php echo $year['id']; ?>"><?php echo $year['school_year']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="year_level" class="form-control" required>
                            <option value="">Select Year Level</option>
                            <?php foreach ($year_levels as $level): ?>
                                <option value="<?php echo $level['id']; ?>"><?php echo $level['level']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="department" class="form-control" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>"><?php echo $dept['course']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="submit" name="submit" value="Register" class="btn btn-primary btn-block">
                    <a href="studentlogin.php" class="btn btn-secondary btn-block">Back</a>
                    <hr class="divider" />
                    <a href="studentlogin.php" class="btn-link">Already have an account? Login</a>
                </form>
                <?php if (!empty($error)): ?>
                    <div class="error-msg">
                        <?php foreach ($error as $err): ?>
                            <p><?php echo $err; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>