<?php
session_start();
include '../conn/connection.php';

$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacherid = $_POST['teacherid'];
    $password = $_POST['password'];

    if (empty($teacherid) || empty($password)) {
        $error[] = "teacher ID and password are required.";
    }

    if (empty($error)) {
        $stmt = $conn->prepare("SELECT * FROM teachers WHERE teacherid = ?");
        $stmt->bind_param("s", $teacherid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['teacherid'] = $user['teacherid'];
                $_SESSION['name'] = $user['name'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error[] = "Invalid password.";
            }
        } else {
            $error[] = "Teacher ID not found.";
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
	<title>Teacher Login</title>
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
		
		.container {
			width: 40%;
			height: 60vh;
			display: flex;
			justify-content: center;
			align-items: center;
			margin: 0 auto;
		}
		
		.login-container {
			background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
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
            background-color: #00A67A;
            border-color: #00A67A;
            color: #fff;
        }
		
		.btn-secondary {
			font-size: large;
			background-color: #082818;
			border-color: #082818;
		}
		.btn:hover {
			background-color: #04614C;
		}
        .backcolor {
			background-color: #9DD9C7;
		}
	</style>
</head>
<body>
<img src=" " style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; object-fit: cover; z-index: -1;" class="backcolor"> 
    <div class="container d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="login-container text-center">
                <img src="../img/ptci.png" alt="Logo" class="logo">
                <h2 class="text-center">Login</h2>
                <form action="" method="POST">
                    <hr class="divider" />
                    <?php
                    if (!empty($error)) {
                        foreach ($error as $err) {
                            echo '<span class="error-msg" style="color: red;">' . $err . '</span><br>';
                        }
                    }
                    ?>
                    <div class="form-group">
                        <input type="text" name="teacherid" class="form-control" placeholder="Teacher ID" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <hr class="divider" />
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                    <a href="../index.php" class="btn btn-secondary btn-block">Back</a>
                    <hr class="divider" />
                    <a href="teacherregister.php" class="btn-link">Doesn't have an account? Register</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>