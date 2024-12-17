<?php
session_start();

// Get user input
$userid = $_POST['userid'];
$password = $_POST['password'];

// Database connection
$conn = mysqli_connect("localhost", "root", "", "sgms_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check admin credentials
$admin_query = "SELECT * FROM admins WHERE username = ? AND password = ?";
$stmt = $conn->prepare($admin_query);
$stmt->bind_param("ss", $userid, $password);
$stmt->execute();
$admin_result = $stmt->get_result();

if ($admin_result->num_rows > 0) {
    $_SESSION['admin_login'] = true;
    $_SESSION['userid'] = $userid;
    header("Location: admin/dashboard.php");
    exit();
}

// Check teacher credentials
$teacher_query = "SELECT * FROM teachers WHERE teacherid = ? AND password = ?";
$stmt = $conn->prepare($teacher_query);
$stmt->bind_param("ss", $userid, $password);
$stmt->execute();
$teacher_result = $stmt->get_result();

if ($teacher_result->num_rows > 0) {
    $_SESSION['teacher_login'] = true;
    $_SESSION['userid'] = $userid;
    header("Location: teacher/dashboard.php");
    exit();
}

// Check student credentials
$student_query = "SELECT * FROM students WHERE studentid = ? AND password = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("ss", $userid, $password);
$stmt->execute();
$student_result = $stmt->get_result();

if ($student_result->num_rows > 0) {
    $_SESSION['student_login'] = true;
    $_SESSION['userid'] = $userid;
    header("Location: student/dashboard.php");
    exit();
}

// If no valid credentials found
$_SESSION['error'] = "Invalid username or password";
header("Location: index.php");
exit();

$stmt->close();
$conn->close();
?>
