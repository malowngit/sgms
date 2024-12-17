<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<body>
    <?php
        $_SESSION["username"] = $_POST["username"];
    ?>
    <script>
        window.location.href = "dashboard.php";
    </script>
</body>
</html>