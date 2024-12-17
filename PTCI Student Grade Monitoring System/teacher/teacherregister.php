<?php
include '../conn/connection.php';

$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacherid = $_POST['teacherid'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($teacherid) || empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error[] = "All fields are required.";
    }

    if ($password !== $confirm_password) {
        $error[] = "Passwords do not match.";
    }

    $stmt = $conn->prepare("SELECT * FROM teachers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error[] = "Email is already registered.";
    }

    if (empty($error)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO teachers (teacherid, name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $teacherid, $name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href='teacherlogin.php';</script>";
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
	<title>teacher Register</title>
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 100%;
		}
		
		.logo {
			width: 100px;
			margin: 20px auto;
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
        <div class="col-md-6">
		<div class="register-container text-center">
                <img src="../img/ptci.png" alt="Logo" class="logo">
                <h2 class="text-center">Register</h2>
                <form action=" " method="POST">
                    <a><hr class="divider" /></a>
                    <div class="form-group">
                        <input type="text" name="teacherid" class="form-control" placeholder="Teacher ID" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                    <a href="teacherlogin.php" class="btn btn-secondary btn-block">Back</a>
					<a><hr class="divider" /></a>
                    <a href="teacherlogin.php" class="btn-link">Already have an account? Login</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>